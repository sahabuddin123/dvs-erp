<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Subscription extends Admin_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('saas_model');
    }

    public function index()
    {
        $school_id = get_loggedin_branch_id();
        if (empty($school_id) && is_superadmin_loggedin()) {
            redirect(base_url('saas/school'));
        }

        $subscription = $this->db->select('sub.*, pkg.name as plan_name, pkg.price, pkg.discount, pkg.student_limit, pkg.staff_limit, pkg.teacher_limit, pkg.parents_limit, pkg.period_type, pkg.period_value, pkg.permission as package_permissions')
            ->from('saas_subscriptions sub')
            ->join('saas_package pkg', 'pkg.id = sub.package_id', 'left')
            ->where('sub.school_id', $school_id)
            ->get()->row_array();

        // Calculate limits usage
        $student_count = $this->db->where('branch_id', $school_id)->count_all_results('enroll');
        $teacher_count = $this->db->select('COUNT(staff.id) as num')->from('staff')
            ->join('login_credential', 'login_credential.user_id = staff.id', 'inner')
            ->where('login_credential.role', 3)
            ->where('staff.branch_id', $school_id)->get()->row()->num;
        $staff_count = $this->db->select('COUNT(staff.id) as num')->from('staff')
            ->join('login_credential', 'login_credential.user_id = staff.id', 'inner')
            ->where_not_in('login_credential.role', array(1, 3, 6, 7))
            ->where('staff.branch_id', $school_id)->get()->row()->num;
        $parents_count = $this->db->where('branch_id', $school_id)->count_all_results('parent');

        $this->data['subscription'] = $subscription;
        $this->data['usage'] = array(
            'students' => $student_count,
            'teachers' => $teacher_count,
            'staff' => $staff_count,
            'parents' => $parents_count,
        );
        $this->data['packages'] = $this->saas_model->getPackages();
        $this->data['transactions'] = $this->db->select('trx.*, pkg.name as plan_name')
            ->from('saas_subscriptions_transactions trx')
            ->join('saas_subscriptions sub', 'sub.id = trx.subscriptions_id', 'left')
            ->join('saas_package pkg', 'pkg.id = trx.package_id', 'left')
            ->where('sub.school_id', $school_id)
            ->order_by('trx.id', 'DESC')->get()->result_array();

        $this->data['title'] = translate('subscription');
        $this->data['sub_page'] = 'subscription/index';
        $this->data['main_menu'] = 'subscription';
        $this->load->view('layout/index', $this->data);
    }

    public function payment($package_id = '')
    {
        $school_id = get_loggedin_branch_id();
        $package = $this->saas_model->getPackage($package_id);
        if (empty($package)) {
            redirect(base_url('subscription/index'));
        }

        $this->data['package'] = $package;
        $this->data['school_id'] = $school_id;
        $this->data['offline_types'] = $this->saas_model->getOfflinePaymentTypes();
        $this->data['payment_config'] = $this->db->get_where('payment_config', array('branch_id' => 1))->row_array();
        $this->data['settings'] = $this->saas_model->getSettings();
        $this->data['title'] = translate('upgrade_plan');
        $this->data['sub_page'] = 'subscription/payment';
        $this->data['main_menu'] = 'subscription';
        $this->load->view('layout/index', $this->data);
    }

    public function offline_payment_submit()
    {
        if ($_POST) {
            $school_id = get_loggedin_branch_id();
            $package_id = $this->input->post('package_id');
            $payment_type = $this->input->post('payment_type');
            $reference = $this->input->post('reference');
            $amount = $this->input->post('amount');
            $note = $this->input->post('note');

            $orig_file = null;
            $enc_file = null;
            if (!empty($_FILES['proof_file']['name'])) {
                $path = 'uploads/offline_payments/';
                if (!file_exists($path)) {
                    @mkdir($path, 0755, true);
                }
                $config['upload_path'] = $path;
                $config['allowed_types'] = 'jpg|jpeg|png|pdf';
                $config['encrypt_name'] = true;
                $this->load->library('upload', $config);
                if ($this->upload->do_upload('proof_file')) {
                    $uData = $this->upload->data();
                    $orig_file = $uData['orig_name'];
                    $enc_file = $uData['file_name'];
                }
            }

            $paymentData = array(
                'payment_type' => $payment_type,
                'payment_date' => date('Y-m-d'),
                'reference' => $reference,
                'amount' => (float)$amount,
                'submit_date' => date('Y-m-d H:i:s'),
                'orig_file_name' => $orig_file,
                'enc_file_name' => $enc_file,
                'note' => $note,
                'status' => 1, // Pending approval
            );
            $this->db->insert('saas_offline_payments', $paymentData);

            // Auto-upgrade if automatic_approval enabled
            $settings = $this->saas_model->getSettings();
            if (!empty($settings['automatic_approval'])) {
                $this->saas_model->updateSchoolPlan($school_id, $package_id);
                set_alert('success', translate('offline_payment_approved_and_plan_upgraded') ?? 'Payment proof submitted and subscription plan upgraded successfully!');
            } else {
                set_alert('success', translate('offline_payment_submitted_for_approval') ?? 'Payment proof submitted successfully! Your plan will be activated after admin verification.');
            }
            redirect(base_url('subscription/index'));
        }
    }

    /* Fixed - 404 Page Not Found After Plan Subscription Payment */
    public function payment_success()
    {
        $school_id = get_loggedin_branch_id();
        $package_id = $this->input->get('package_id') ? $this->input->get('package_id') : $this->input->post('package_id');
        $trx_id = $this->input->get('trx_id') ? $this->input->get('trx_id') : 'ONLINE_' . strtoupper(uniqid());

        if (!empty($package_id)) {
            $this->saas_model->updateSchoolPlan($school_id, $package_id);
        }

        $this->data['trx_id'] = $trx_id;
        $this->data['package'] = !empty($package_id) ? $this->saas_model->getPackage($package_id) : null;
        $this->data['title'] = translate('subscription_payment_success');
        $this->data['sub_page'] = 'subscription/payment_success';
        $this->data['main_menu'] = 'subscription';
        $this->load->view('layout/index', $this->data);
    }

    public function payment_cancel()
    {
        set_alert('error', translate('subscription_payment_cancelled') ?? 'Subscription payment process was cancelled.');
        redirect(base_url('subscription/index'));
    }
}

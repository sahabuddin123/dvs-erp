<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Saas extends Admin_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('saas_model');
        if (!is_superadmin_loggedin()) {
            access_denied();
        }
    }

    public function index()
    {
        redirect(base_url('saas/school'));
    }

    /* School Subscription Management */
    public function school()
    {
        if ($this->input->post('submit') == 'save') {
            $this->form_validation->set_rules('school_name', translate('school_name'), 'trim|required');
            $this->form_validation->set_rules('branch_name', translate('branch_name'), 'trim|required');
            $this->form_validation->set_rules('admin_name', translate('admin_name'), 'trim|required');
            $this->form_validation->set_rules('email', translate('email'), 'trim|required|valid_email');
            $this->form_validation->set_rules('mobileno', translate('mobile_no'), 'trim|required');
            $this->form_validation->set_rules('package_id', translate('plan'), 'trim|required');

            if ($this->form_validation->run() == true) {
                $postData = $this->input->post();
                $result = $this->saas_model->addSchoolWithSubscription($postData);
                if ($result['status']) {
                    set_alert('success', translate('information_has_been_saved_successfully'));
                } else {
                    set_alert('error', $result['message']);
                }
                redirect(base_url('saas/school'));
            }
        }

        $filter = $this->input->get('filter') ? $this->input->get('filter') : 'all';
        $this->data['filter'] = $filter;
        $this->data['school_list'] = $this->saas_model->getSchoolList($filter);
        $this->data['packages'] = $this->saas_model->getPackages();
        $this->data['title'] = translate('school_subscription');
        $this->data['sub_page'] = 'saas/school';
        $this->data['main_menu'] = 'saas';
        $this->load->view('layout/index', $this->data);
    }

    /* Update / Change School Plan Modal Action */
    public function update_school_plan()
    {
        if ($_POST) {
            $school_id = $this->input->post('school_id');
            $package_id = $this->input->post('package_id');
            if (!empty($school_id) && !empty($package_id)) {
                $this->saas_model->updateSchoolPlan($school_id, $package_id);
                set_alert('success', translate('information_has_been_updated_successfully'));
            } else {
                set_alert('error', translate('required_fields_missing'));
            }
            redirect(base_url('saas/school'));
        }
    }

    /* Plan & Package Management */
    public function package()
    {
        if ($this->input->post('submit') == 'save') {
            $this->form_validation->set_rules('name', translate('name'), 'trim|required');
            $this->form_validation->set_rules('price', translate('price'), 'trim|required|numeric');
            $this->form_validation->set_rules('student_limit', translate('student_limit'), 'trim|required|numeric');
            $this->form_validation->set_rules('staff_limit', translate('staff_limit'), 'trim|required|numeric');
            $this->form_validation->set_rules('teacher_limit', translate('teacher_limit'), 'trim|required|numeric');
            $this->form_validation->set_rules('parents_limit', translate('parents_limit'), 'trim|required|numeric');
            $this->form_validation->set_rules('period_type', translate('period_type'), 'trim|required');

            if ($this->form_validation->run() == true) {
                $modules = $this->input->post('modules');
                $modules_json = !empty($modules) ? json_encode(array_values($modules)) : '[]';

                $arrayData = array(
                    'name' => $this->input->post('name'),
                    'price' => $this->input->post('price'),
                    'discount' => (float)$this->input->post('discount'),
                    'student_limit' => (float)$this->input->post('student_limit'),
                    'staff_limit' => (float)$this->input->post('staff_limit'),
                    'teacher_limit' => (float)$this->input->post('teacher_limit'),
                    'parents_limit' => (float)$this->input->post('parents_limit'),
                    'period_type' => (int)$this->input->post('period_type'),
                    'period_value' => (int)$this->input->post('period_value'),
                    'free_trial' => $this->input->post('free_trial') ? 1 : 0,
                    'recommended' => $this->input->post('recommended') ? 1 : 0,
                    'show_onwebsite' => $this->input->post('show_onwebsite') ? 1 : 0,
                    'status' => 1,
                    'permission' => $modules_json,
                );

                $this->saas_model->savePackage($arrayData);
                set_alert('success', translate('information_has_been_saved_successfully'));
                redirect(base_url('saas/package'));
            }
        }

        $this->data['packages'] = $this->saas_model->getPackages();
        $this->data['modules'] = $this->saas_model->getAllModules();
        $this->data['title'] = translate('plan');
        $this->data['sub_page'] = 'saas/package';
        $this->data['main_menu'] = 'saas';
        $this->load->view('layout/index', $this->data);
    }

    public function package_edit($id = '')
    {
        $package = $this->saas_model->getPackage($id);
        if (empty($package)) {
            redirect(base_url('saas/package'));
        }

        if ($this->input->post('submit') == 'update') {
            $this->form_validation->set_rules('name', translate('name'), 'trim|required');
            $this->form_validation->set_rules('price', translate('price'), 'trim|required|numeric');
            $this->form_validation->set_rules('student_limit', translate('student_limit'), 'trim|required|numeric');
            $this->form_validation->set_rules('staff_limit', translate('staff_limit'), 'trim|required|numeric');
            $this->form_validation->set_rules('teacher_limit', translate('teacher_limit'), 'trim|required|numeric');
            $this->form_validation->set_rules('parents_limit', translate('parents_limit'), 'trim|required|numeric');
            $this->form_validation->set_rules('period_type', translate('period_type'), 'trim|required');

            if ($this->form_validation->run() == true) {
                $modules = $this->input->post('modules');
                $modules_json = !empty($modules) ? json_encode(array_values($modules)) : '[]';

                $arrayData = array(
                    'name' => $this->input->post('name'),
                    'price' => $this->input->post('price'),
                    'discount' => (float)$this->input->post('discount'),
                    'student_limit' => (float)$this->input->post('student_limit'),
                    'staff_limit' => (float)$this->input->post('staff_limit'),
                    'teacher_limit' => (float)$this->input->post('teacher_limit'),
                    'parents_limit' => (float)$this->input->post('parents_limit'),
                    'period_type' => (int)$this->input->post('period_type'),
                    'period_value' => (int)$this->input->post('period_value'),
                    'free_trial' => $this->input->post('free_trial') ? 1 : 0,
                    'recommended' => $this->input->post('recommended') ? 1 : 0,
                    'show_onwebsite' => $this->input->post('show_onwebsite') ? 1 : 0,
                    'permission' => $modules_json,
                );

                $this->saas_model->savePackage($arrayData, $id);
                set_alert('success', translate('information_has_been_updated_successfully'));
                redirect(base_url('saas/package'));
            }
        }

        $this->data['package'] = $package;
        $this->data['modules'] = $this->saas_model->getAllModules();
        $this->data['title'] = translate('edit_package');
        $this->data['sub_page'] = 'saas/package_edit';
        $this->data['main_menu'] = 'saas';
        $this->load->view('layout/index', $this->data);
    }

    public function package_delete($id = '')
    {
        $res = $this->saas_model->deletePackage($id);
        if ($res) {
            set_alert('success', translate('information_deleted'));
        } else {
            set_alert('error', translate('package_in_use_cannot_delete'));
        }
        redirect(base_url('saas/package'));
    }

    /* Pending School Registration Requests */
    public function pending_request()
    {
        $this->data['requests'] = $this->saas_model->getPendingRequests();
        $this->data['title'] = translate('pending_request');
        $this->data['sub_page'] = 'saas/pending_request';
        $this->data['main_menu'] = 'saas';
        $this->load->view('layout/index', $this->data);
    }

    public function approve_request()
    {
        $id = $this->input->post('request_id');
        if (!empty($id)) {
            $this->saas_model->approveRequest($id);
            set_alert('success', translate('school_subscription_approved_successfully'));
        }
        redirect(base_url('saas/pending_request'));
    }

    public function reject_request()
    {
        $id = $this->input->post('request_id');
        $reason = $this->input->post('reason');
        if (!empty($id)) {
            $this->saas_model->rejectRequest($id, $reason);
            set_alert('success', translate('school_subscription_rejected_successfully'));
        }
        redirect(base_url('saas/pending_request'));
    }

    /* Subscription Transactions History */
    public function transactions()
    {
        $this->data['transactions'] = $this->saas_model->getTransactions();
        $this->data['title'] = translate('subscription_transactions_history');
        $this->data['sub_page'] = 'saas/transactions';
        $this->data['main_menu'] = 'saas';
        $this->load->view('layout/index', $this->data);
    }

    /* General Settings */
    public function settings_general()
    {
        if ($this->input->post('submit') == 'save') {
            $postData = $this->input->post();
            unset($postData['submit']);

            // Handle slider image uploads
            if (!empty($_FILES['slider_image']['name'])) {
                $slider_img = $this->uploadImage('uploads/saas_images/', 'slider_image');
                if ($slider_img) {
                    $postData['slider_image'] = $slider_img;
                }
            }
            if (!empty($_FILES['slider_bg_image']['name'])) {
                $bg_img = $this->uploadImage('uploads/saas_images/', 'slider_bg_image');
                if ($bg_img) {
                    $postData['slider_bg_image'] = $bg_img;
                }
            }
            if (!empty($_FILES['overly_image']['name'])) {
                $overly_img = $this->uploadImage('uploads/saas_images/', 'overly_image');
                if ($overly_img) {
                    $postData['overly_image'] = $overly_img;
                }
            }

            $this->saas_model->saveSettings($postData);
            set_alert('success', translate('the_configuration_has_been_updated'));
            redirect(base_url('saas/settings_general'));
        }

        $this->data['settings'] = $this->saas_model->getSettings();
        $this->data['title'] = translate('general_settings');
        $this->data['sub_page'] = 'saas/settings_general';
        $this->data['main_menu'] = 'saas_setting';
        $this->load->view('layout/index', $this->data);
    }

    /* Website Settings (Theme colors, Social links) */
    public function settings_website()
    {
        if ($this->input->post('submit') == 'save') {
            $postData = $this->input->post();
            unset($postData['submit']);
            $this->saas_model->saveSettings($postData);
            set_alert('success', translate('the_configuration_has_been_updated'));
            redirect(base_url('saas/settings_website'));
        }

        $this->data['settings'] = $this->saas_model->getSettings();
        $this->data['title'] = translate('website_settings');
        $this->data['sub_page'] = 'saas/settings_website';
        $this->data['main_menu'] = 'saas_setting';
        $this->load->view('layout/index', $this->data);
    }

    /* Payment Settings (Paytm, bKash, Nagad, Offline types) */
    public function settings_payment()
    {
        if ($this->input->post('submit') == 'save_gateways') {
            $pay_config = array(
                'paytm_merchantmid' => $this->input->post('paytm_merchantmid'),
                'paytm_merchantkey' => $this->input->post('paytm_merchantkey'),
                'paytm_merchant_website' => $this->input->post('paytm_merchant_website'),
                'paytm_industry_type' => $this->input->post('paytm_industry_type'),
                'paytm_status' => $this->input->post('paytm_status') ? 1 : 0,

                'bkash_app_key' => $this->input->post('bkash_app_key'),
                'bkash_app_secret' => $this->input->post('bkash_app_secret'),
                'bkash_username' => $this->input->post('bkash_username'),
                'bkash_password' => $this->input->post('bkash_password'),
                'bkash_sandbox' => $this->input->post('bkash_sandbox') ? 1 : 0,
                'bkash_status' => $this->input->post('bkash_status') ? 1 : 0,

                'nagad_merchant_id' => $this->input->post('nagad_merchant_id'),
                'nagad_merchant_number' => $this->input->post('nagad_merchant_number'),
                'nagad_public_key' => $this->input->post('nagad_public_key'),
                'nagad_private_key' => $this->input->post('nagad_private_key'),
                'nagad_sandbox' => $this->input->post('nagad_sandbox') ? 1 : 0,
                'nagad_status' => $this->input->post('nagad_status') ? 1 : 0,
            );

            // Update payment_config for SaaS (branch_id = 0 or 1)
            $exists = $this->db->get_where('payment_config', array('branch_id' => 1))->row_array();
            if ($exists) {
                $this->db->where('id', $exists['id']);
                $this->db->update('payment_config', $pay_config);
            } else {
                $pay_config['branch_id'] = 1;
                $this->db->insert('payment_config', $pay_config);
            }

            set_alert('success', translate('the_configuration_has_been_updated'));
            redirect(base_url('saas/settings_payment'));
        }

        if ($this->input->post('submit') == 'add_offline_type') {
            $name = $this->input->post('type_name');
            $note = $this->input->post('note');
            if (!empty($name)) {
                $this->saas_model->saveOfflinePaymentType(array('name' => $name, 'note' => $note));
                set_alert('success', translate('information_has_been_saved_successfully'));
            }
            redirect(base_url('saas/settings_payment'));
        }

        $this->data['payment_config'] = $this->db->get_where('payment_config', array('branch_id' => 1))->row_array();
        $this->data['offline_types'] = $this->saas_model->getOfflinePaymentTypes();
        $this->data['settings'] = $this->saas_model->getSettings();
        $this->data['title'] = translate('payment_settings');
        $this->data['sub_page'] = 'saas/settings_payment';
        $this->data['main_menu'] = 'saas_setting';
        $this->load->view('layout/index', $this->data);
    }

    public function delete_offline_type($id = '')
    {
        $this->saas_model->deleteOfflinePaymentType($id);
        set_alert('success', translate('information_deleted'));
        redirect(base_url('saas/settings_payment'));
    }

    /* Email Settings & Saas Email Test */
    public function settings_email()
    {
        if ($this->input->post('submit') == 'save_template') {
            $id = $this->input->post('template_id');
            $subject = $this->input->post('subject');
            $body = $this->input->post('template_body');
            $notified = $this->input->post('notified') ? 1 : 0;

            $this->saas_model->saveEmailTemplate($id, $subject, $body, $notified);
            set_alert('success', translate('information_has_been_updated_successfully'));
            redirect(base_url('saas/settings_email'));
        }

        $this->data['templates'] = $this->saas_model->getEmailTemplates();
        $this->data['title'] = translate('email_settings');
        $this->data['sub_page'] = 'saas/settings_email';
        $this->data['main_menu'] = 'saas_setting';
        $this->load->view('layout/index', $this->data);
    }

    /* Saas Email Test Action */
    public function email_test()
    {
        if ($_POST) {
            $test_email = $this->input->post('test_email');
            if (filter_var($test_email, FILTER_VALIDATE_EMAIL)) {
                $this->load->library('email');
                $this->email->from('no-reply@dreamvalleyschool.com', 'DVS School ERP SaaS');
                $this->email->to($test_email);
                $this->email->subject('SaaS Email Configuration Test');
                $this->email->message('Hello! This is a test email sent from your DVS School ERP SaaS Module to confirm your mail delivery configuration is working perfectly.');
                
                if (@$this->email->send()) {
                    set_alert('success', translate('test_email_sent_successfully') ?? 'Test email sent successfully to ' . $test_email);
                } else {
                    set_alert('error', translate('failed_to_send_email') ?? 'Failed to send test email. Please check your SMTP configuration.');
                }
            } else {
                set_alert('error', translate('invalid_email_address'));
            }
            redirect(base_url('saas/settings_email'));
        }
    }

    private function uploadImage($path, $field)
    {
        if (!file_exists($path)) {
            @mkdir($path, 0755, true);
        }
        $config['upload_path'] = $path;
        $config['allowed_types'] = 'gif|jpg|jpeg|png|webp|svg';
        $config['encrypt_name'] = true;
        $this->load->library('upload', $config);
        if ($this->upload->do_upload($field)) {
            $data = $this->upload->data();
            return $data['file_name'];
        }
        return false;
    }
}


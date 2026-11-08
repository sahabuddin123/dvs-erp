<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Two_factor_auth extends Admin_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('two_fa_model');
        $this->load->library('totp_lib');
    }

    /**
     * Default entry point: Admin/SuperAdmin sees Settings, others see Setup
     */
    public function index()
    {
        if (is_superadmin_loggedin() || is_admin_loggedin()) {
            $this->settings();
        } else {
            $this->setup();
        }
    }

    /**
     * 2FA Settings Page (Matching Screenshot 1)
     */
    public function settings()
    {
        if (!is_superadmin_loggedin() && !is_admin_loggedin()) {
            access_denied();
        }

        if ($_POST) {
            $this->save_settings();
            return;
        }

        $branch_id = $this->application_model->get_branch_id();
        $this->data['settings'] = $this->two_fa_model->get_settings($branch_id);
        $this->data['title'] = translate('two_factor_authentication');
        $this->data['sub_page'] = 'two_factor_auth/index';
        $this->data['main_menu'] = 'two_factor_auth';
        $this->load->view('layout/index', $this->data);
    }

    /**
     * Save 2FA Settings Action
     */
    public function save_settings()
    {
        if (!is_superadmin_loggedin() && !is_admin_loggedin()) {
            access_denied();
        }

        $branch_id = $this->application_model->get_branch_id();
        $is_active = (int)$this->input->post('is_active');
        $remember_browser = (int)$this->input->post('remember_browser');
        $cookie_expiry = trim((string)$this->input->post('cookie_expiry')) ?: '1 Year';
        $email_instruction = trim((string)$this->input->post('email_instruction'));
        $app_instruction = trim((string)$this->input->post('app_instruction'));

        $data = [
            'branch_id'         => $branch_id,
            'is_active'         => $is_active,
            'remember_browser'  => $remember_browser,
            'cookie_expiry'     => $cookie_expiry,
            'email_instruction' => $email_instruction,
            'app_instruction'   => $app_instruction,
            'updated_at'        => date('Y-m-d H:i:s')
        ];

        $this->two_fa_model->save_settings($data);
        set_alert('success', translate('information_has_been_updated_successfully'));
        redirect(base_url('two_factor_auth/settings'));
    }

    /**
     * My 2FA Setup Page (Matching Screenshot 2)
     */
    public function setup()
    {
        $user_id = get_loggedin_id();
        if (empty($user_id)) {
            $user_id = $this->session->userdata('loggedin_id');
        }

        $user_config = $this->two_fa_model->get_user_config($user_id);
        $user_cred = $this->db->get_where('login_credential', ['id' => $user_id])->row();
        $user_email = $this->session->userdata('loggedin_email');

        if (empty($user_email) && !empty($user_cred)) {
            // Retrieve email from student or staff
            if ($user_cred->role == 7) {
                $stu = $this->db->select('email')->where('id', $user_cred->user_id)->get('student')->row();
                $user_email = !empty($stu->email) ? $stu->email : '';
            } else {
                $stf = $this->db->select('email')->where('id', $user_cred->user_id)->get('staff')->row();
                $user_email = !empty($stf->email) ? $stf->email : '';
            }
        }

        $school_name = get_global_setting('institute_name');
        if (empty($school_name)) {
            $school_name = 'Dream Valley School';
        }

        $account_name = !empty($user_cred->username) ? $user_cred->username : 'user_' . $user_id;
        $otp_url = $this->totp_lib->getOtpAuthUrl($school_name, $account_name, $user_config->secret_key);
        $qr_base64 = $this->totp_lib->getQrCodeBase64($otp_url);

        $saved_browsers = $this->two_fa_model->get_remembered_browsers($user_id);

        $this->data['user_config'] = $user_config;
        $this->data['user_email'] = $user_email;
        $this->data['secret_key'] = $user_config->secret_key;
        $this->data['qr_base64'] = $qr_base64;
        $this->data['saved_browsers'] = $saved_browsers;
        $this->data['title'] = translate('two_factor_authentication');
        $this->data['sub_page'] = 'two_factor_auth/setup';
        $this->data['main_menu'] = 'two_factor_auth';
        $this->load->view('layout/index', $this->data);
    }

    /**
     * Alias for My Security
     */
    public function my_security()
    {
        $this->setup();
    }

    /**
     * AJAX: Send Verification Code to Email before enabling/disabling Email 2FA
     */
    public function send_email_code()
    {
        $user_id = get_loggedin_id();
        if (empty($user_id)) {
            $user_id = $this->session->userdata('loggedin_id');
        }

        $user_email = $this->input->post('email');
        if (empty($user_email)) {
            $user_email = $this->session->userdata('loggedin_email');
        }

        if (empty($user_email)) {
            echo json_encode(['status' => 'fail', 'message' => translate('no_email_address_found')]);
            exit();
        }

        $user_name = $this->session->userdata('name');
        $this->two_fa_model->send_email_verify_code($user_id, $user_email, $user_name);
        echo json_encode(['status' => 'success', 'message' => translate('verification_code_sent_to_email')]);
        exit();
    }

    /**
     * AJAX: Verify Code and Toggle Authenticator APP 2FA
     * Cannot enable or disable without code verification!
     */
    public function verify_toggle_app()
    {
        $user_id = get_loggedin_id();
        if (empty($user_id)) {
            $user_id = $this->session->userdata('loggedin_id');
        }

        $code = trim((string)$this->input->post('code'));
        $action = trim((string)$this->input->post('action')) ?: 'enable';

        if (empty($code)) {
            echo json_encode(['status' => 'fail', 'message' => translate('please_enter_verification_code')]);
            exit();
        }

        $result = $this->two_fa_model->verify_and_toggle_app($user_id, $code, $action);
        echo json_encode($result);
        exit();
    }

    /**
     * AJAX: Verify Code and Toggle Email 2FA
     * Cannot enable or disable without code verification!
     */
    public function verify_toggle_email()
    {
        $user_id = get_loggedin_id();
        if (empty($user_id)) {
            $user_id = $this->session->userdata('loggedin_id');
        }

        $code = trim((string)$this->input->post('code'));
        $action = trim((string)$this->input->post('action')) ?: 'enable';

        if (empty($code)) {
            echo json_encode(['status' => 'fail', 'message' => translate('please_enter_verification_code')]);
            exit();
        }

        $result = $this->two_fa_model->verify_and_toggle_email($user_id, $code, $action);
        echo json_encode($result);
        exit();
    }

    /**
     * Remove a saved/remembered browser
     */
    public function remove_browser($id = '')
    {
        $user_id = get_loggedin_id();
        if (empty($user_id)) {
            $user_id = $this->session->userdata('loggedin_id');
        }

        if (!empty($id)) {
            $this->two_fa_model->remove_remembered_browser($id, $user_id);
            set_alert('success', translate('saved_browser_removed_successfully'));
        }

        $ref = $this->input->server('HTTP_REFERER');
        if (!empty($ref)) {
            redirect($ref);
        } else {
            redirect(base_url('two_factor_auth/setup'));
        }
    }

    /**
     * Authentication Activity Logs
     */
    public function logs()
    {
        if (!is_superadmin_loggedin() && !is_admin_loggedin()) {
            access_denied();
        }

        $this->data['logs'] = $this->two_fa_model->get_logs(200);
        $this->data['title'] = translate('two_factor_authentication') . ' - ' . translate('activity_logs');
        $this->data['sub_page'] = 'two_factor_auth/logs';
        $this->data['main_menu'] = 'two_factor_auth';
        $this->load->view('layout/index', $this->data);
    }
}

<?php
defined('BASEPATH') or exit('No direct script access allowed');

/**
 * Two Factor Authentication Addon Controller
 * Manages 2FA settings, role enforcement, activity logs, and personal user preferences
 */
class Two_factor_auth extends Admin_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('two_fa_model');
    }

    /* Main Settings Page */
    public function index()
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
        $this->data['roles'] = $this->app_lib->getRoles('all');
        $this->data['logs'] = $this->two_fa_model->get_logs(50);
        $this->data['title'] = translate('two_factor_authentication');
        $this->data['sub_page'] = 'two_factor_auth/index';
        $this->data['main_menu'] = 'two_factor_auth';
        $this->load->view('layout/index', $this->data);
    }

    /* Save Settings Action */
    public function save_settings()
    {
        if (!is_superadmin_loggedin() && !is_admin_loggedin()) {
            access_denied();
        }

        $branch_id = $this->application_model->get_branch_id();
        $is_active = $this->input->post('is_active') ? 1 : 0;
        $auth_method = $this->input->post('auth_method') ?: 'email';
        $roles_enforced = $this->input->post('roles_enforced');
        if (!is_array($roles_enforced)) {
            $roles_enforced = [];
        }
        $otp_expire = (int)$this->input->post('otp_expire') ?: 10;
        if ($otp_expire < 1) {
            $otp_expire = 10;
        }
        $master_bypass_pin = trim((string)$this->input->post('master_bypass_pin')) ?: '998877';

        $data = [
            'branch_id'         => $branch_id,
            'is_active'         => $is_active,
            'auth_method'       => $auth_method,
            'roles_enforced'    => json_encode(array_values($roles_enforced)),
            'otp_expire'        => $otp_expire,
            'master_bypass_pin' => $master_bypass_pin,
            'updated_at'        => date('Y-m-d H:i:s')
        ];

        $this->two_fa_model->save_settings($data);
        set_alert('success', translate('information_has_been_updated_successfully'));
        redirect(base_url('two_factor_auth'));
    }

    /* Authentication Logs */
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

    /* Personal User Security Profile (Accessible by all logged in users via topbar) */
    public function my_security()
    {
        $user_id = get_loggedin_id();
        if (empty($user_id)) {
            $user_id = $this->session->userdata('loggedin_id');
        }

        if ($_POST) {
            $is_enabled = $this->input->post('is_enabled') ? 1 : 0;
            $exist = $this->db->get_where('two_fa_user_config', ['user_id' => $user_id])->row();
            if ($exist) {
                $this->db->where('user_id', $user_id)->update('two_fa_user_config', ['is_enabled' => $is_enabled]);
            } else {
                $this->db->insert('two_fa_user_config', ['user_id' => $user_id, 'is_enabled' => $is_enabled]);
            }
            set_alert('success', translate('information_has_been_updated_successfully'));
            redirect(base_url('two_factor_auth/my_security'));
        }

        $this->data['user_config'] = $this->db->get_where('two_fa_user_config', ['user_id' => $user_id])->row();
        $this->data['title'] = translate('two_factor_authentication');
        $this->data['sub_page'] = 'two_factor_auth/my_security';
        $this->data['main_menu'] = 'two_factor_auth';
        $this->load->view('layout/index', $this->data);
    }
}


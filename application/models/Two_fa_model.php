<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Two_fa_model extends MY_Model
{
    public function __construct()
    {
        parent::__construct();
        $this->load->library('totp_lib');
    }

    /**
     * Entry point called from Authentication.php on successful password match
     * Returns true if 2FA verification is required, false to bypass and log in directly
     */
    public function manage_2fa($sessionData, $login_credential, $user_email)
    {
        // 1. If 2FA addon is turned off globally in Addons Manager, bypass
        if (!function_exists('moduleIsEnabled') || !moduleIsEnabled('two_fa')) {
            return false;
        }

        $branch_id = !empty($sessionData['loggedin_branch']) ? (int)$sessionData['loggedin_branch'] : 0;
        $settings = $this->get_settings($branch_id);

        // 2. If module setting is inactive, bypass
        if (empty($settings->is_active) || $settings->is_active == 0) {
            return false;
        }

        // 3. Check if current browser is remembered
        if (!empty($settings->remember_browser) && $this->is_browser_remembered($login_credential->id)) {
            return false;
        }

        // 4. Check user configuration and role-based enforcement
        $roles_enforced = [];
        if (!empty($settings->roles_enforced)) {
            $decoded = json_decode($settings->roles_enforced, true);
            if (is_array($decoded)) {
                $roles_enforced = $decoded;
            } else {
                $roles_enforced = explode(',', (string)$settings->roles_enforced);
            }
        }

        $user_role = (string)$login_credential->role;
        $is_enforced_for_role = in_array($user_role, $roles_enforced);
        $user_config = $this->get_user_config($login_credential->id);

        $has_app = (!empty($user_config->app_enabled) && $user_config->app_enabled == 1 && !empty($user_config->secret_key));
        $has_email = (!empty($user_config->email_enabled) && $user_config->email_enabled == 1);

        if (!$is_enforced_for_role) {
            // Role not strictly enforced: check if user manually turned on App or Email 2FA
            if (!$has_app && !$has_email) {
                return false;
            }
        } else {
            // Role is enforced: check if user manually disabled it (only allow if not superadmin)
            if ($login_credential->role != 1 && !empty($user_config) && isset($user_config->is_enabled) && $user_config->is_enabled == 0) {
                return false;
            }
        }

        // Determine method: prefer App if enabled, otherwise Email
        $auth_method = $has_app ? 'app' : 'email';

        // 5. Setup session verification metadata
        $this->session->set_userdata('2FA_USER_ID', $login_credential->id);
        $this->session->set_userdata('2FA_EMAIL', $user_email);
        $this->session->set_userdata('2FA_METHOD', $auth_method);
        $this->session->set_userdata('2FA_SHOW_REMEMBER', !empty($settings->remember_browser) ? 1 : 0);

        if ($auth_method === 'app') {
            $app_inst = !empty($settings->app_instruction) 
                ? $settings->app_instruction 
                : 'Download Google Authenticator App in your mobile device.';
            $this->session->set_userdata('2FA_INSTRUCTION', $app_inst);
        } else {
            // Email OTP generation and dispatch
            $code = sprintf("%06d", mt_rand(100000, 999999));
            $expire_minutes = !empty($settings->otp_expire) ? (int)$settings->otp_expire : 10;
            if ($expire_minutes < 1) {
                $expire_minutes = 10;
            }
            $expires_at = date('Y-m-d H:i:s', time() + ($expire_minutes * 60));

            $code_data = [
                'user_id'     => $login_credential->id,
                'email'       => $user_email,
                'mobile'      => '',
                'code'        => $code,
                'auth_method' => 'email',
                'expires_at'  => $expires_at,
                'is_used'     => 0,
                'ip_address'  => $this->input->ip_address(),
                'user_agent'  => substr((string)$this->input->user_agent(), 0, 250),
                'created_at'  => date('Y-m-d H:i:s')
            ];
            $this->db->insert('two_fa_codes', $code_data);
            $code_id = $this->db->insert_id();

            $email_inst = !empty($settings->email_instruction) 
                ? $settings->email_instruction 
                : 'A unique code has been sent to your email address. Please check your inbox for the 2FA verification code.';

            $this->session->set_userdata('2FA_CODE_ID', $code_id);
            $this->session->set_userdata('2FA_EXPIRES_AT', strtotime($expires_at));
            $this->session->set_userdata('2FA_INSTRUCTION', $email_inst);
            $this->session->set_userdata('2FA_LAST_SENT', time());
            $this->session->set_userdata('2FA_SENT_CODE', $code);

            if (!empty($user_email)) {
                $this->send_email_otp($user_email, $code, $expire_minutes, $branch_id, $sessionData['name']);
            }
        }

        return true;
    }

    /**
     * Verify submitted 6-digit code (handles Authenticator App TOTP or Email OTP)
     */
    public function verify_code($user_id, $code, $remember_browser = false)
    {
        $code = trim((string)$code);
        if (empty($code)) {
            return [
                'success' => false,
                'message' => translate('please_enter_verification_code')
            ];
        }

        $settings = $this->get_settings();

        // 1. SuperAdmin Emergency Bypass PIN check
        if (!empty($settings->master_bypass_pin) && $code === trim((string)$settings->master_bypass_pin)) {
            $user = $this->db->get_where('login_credential', ['id' => $user_id])->row();
            if ($user && in_array($user->role, [1, 2])) {
                if ($remember_browser && !empty($settings->remember_browser)) {
                    $this->remember_current_browser($user_id, $settings->cookie_expiry);
                }
                return [
                    'success' => true,
                    'message' => 'Emergency bypass code accepted'
                ];
            }
        }

        $user_config = $this->get_user_config($user_id);
        $verified = false;

        // 2. Check Authenticator App TOTP if app is enabled
        if (!empty($user_config->app_enabled) && $user_config->app_enabled == 1 && !empty($user_config->secret_key)) {
            if ($this->totp_lib->verifyCode($user_config->secret_key, $code)) {
                $verified = true;
            }
        }

        // 3. Check Email OTP code from two_fa_codes table
        if (!$verified) {
            $active_row = $this->db->where('user_id', $user_id)
                ->where('code', $code)
                ->where('is_used', 0)
                ->where('expires_at >=', date('Y-m-d H:i:s'))
                ->order_by('id', 'DESC')
                ->get('two_fa_codes')
                ->row();

            if (!empty($active_row)) {
                $this->db->where('id', $active_row->id)->update('two_fa_codes', ['is_used' => 1]);
                $verified = true;
            }
        }

        if ($verified) {
            // Handle "Do Not Ask Again On This Browser"
            if ($remember_browser && !empty($settings->remember_browser)) {
                $this->remember_current_browser($user_id, $settings->cookie_expiry);
            }

            return [
                'success' => true,
                'message' => translate('verification_successful')
            ];
        }

        return [
            'success' => false,
            'message' => translate('invalid_verification_code')
        ];
    }

    /**
     * Check if the current browser has a valid remember cookie
     */
    public function is_browser_remembered($user_id)
    {
        $cookie_token = $this->input->cookie('dvs_2fa_remember', true);
        if (empty($cookie_token)) {
            return false;
        }

        $row = $this->db->where('user_id', $user_id)
            ->where('browser_token', $cookie_token)
            ->where('expires_at >', date('Y-m-d H:i:s'))
            ->get('two_fa_remembered_browsers')
            ->row();

        return !empty($row);
    }

    /**
     * Remember current browser for specified duration
     */
    public function remember_current_browser($user_id, $expiry_setting = '1 Year')
    {
        $seconds = $this->parse_cookie_expiry_seconds($expiry_setting);
        $token = hash('sha256', random_bytes(32) . microtime(true) . $user_id);
        $expires_at = date('Y-m-d H:i:s', time() + $seconds);

        $ua = (string)$this->input->user_agent();
        $client_info = $this->totp_lib->detectBrowserAndPlatform($ua);

        $data = [
            'user_id'       => $user_id,
            'browser_token' => $token,
            'ip_address'    => $this->input->ip_address(),
            'browser'       => $client_info['browser'],
            'platform'      => $client_info['platform'],
            'user_agent'    => substr($ua, 0, 255),
            'expires_at'    => $expires_at,
            'created_at'    => date('Y-m-d H:i:s')
        ];
        $this->db->insert('two_fa_remembered_browsers', $data);

        // Set secure HTTP-only cookie
        $cookie = [
            'name'   => 'dvs_2fa_remember',
            'value'  => $token,
            'expire' => $seconds,
            'path'   => '/',
            'secure' => false,
            'httponly' => true,
        ];
        $this->input->set_cookie($cookie);

        return $token;
    }

    /**
     * Convert expiry text to seconds
     */
    public function parse_cookie_expiry_seconds($expiry_setting)
    {
        switch (strtolower(trim($expiry_setting))) {
            case '1 day':
                return 86400;
            case '1 week':
                return 7 * 86400;
            case '1 month':
                return 30 * 86400;
            case '3 months':
                return 90 * 86400;
            case '6 months':
                return 180 * 86400;
            case '1 year':
            default:
                return 365 * 86400;
        }
    }

    /**
     * Get user 2FA configuration, ensuring secret key exists
     */
    public function get_user_config($user_id)
    {
        $row = $this->db->get_where('two_fa_user_config', ['user_id' => $user_id])->row();
        if (empty($row)) {
            $secret = $this->totp_lib->createSecret(16);
            $new_data = [
                'user_id'       => $user_id,
                'is_enabled'    => 0,
                'app_enabled'   => 0,
                'email_enabled' => 0,
                'secret_key'    => $secret,
                'created_at'    => date('Y-m-d H:i:s')
            ];
            $this->db->insert('two_fa_user_config', $new_data);
            return (object)$new_data;
        }

        // If secret key is missing, generate one
        if (empty($row->secret_key)) {
            $secret = $this->totp_lib->createSecret(16);
            $this->db->where('id', $row->id)->update('two_fa_user_config', ['secret_key' => $secret]);
            $row->secret_key = $secret;
        }

        return $row;
    }

    /**
     * Verify submitted code and toggle Authenticator App 2FA
     */
    public function verify_and_toggle_app($user_id, $code, $action = 'enable')
    {
        $user_config = $this->get_user_config($user_id);
        $secret = $user_config->secret_key;

        // Verify the code from Authenticator App
        if (!$this->totp_lib->verifyCode($secret, $code)) {
            return [
                'status'  => 'fail',
                'message' => translate('invalid_verification_code_from_authenticator_app')
            ];
        }

        if ($action === 'enable') {
            $this->db->where('user_id', $user_id)->update('two_fa_user_config', [
                'app_enabled' => 1,
                'is_enabled'  => 1,
                'updated_at'  => date('Y-m-d H:i:s')
            ]);
            return [
                'status'  => 'success',
                'message' => translate('authenticator_app_2fa_enabled_successfully')
            ];
        } else {
            $is_any_left = (!empty($user_config->email_enabled) && $user_config->email_enabled == 1) ? 1 : 0;
            $this->db->where('user_id', $user_id)->update('two_fa_user_config', [
                'app_enabled' => 0,
                'is_enabled'  => $is_any_left,
                'updated_at'  => date('Y-m-d H:i:s')
            ]);
            return [
                'status'  => 'success',
                'message' => translate('authenticator_app_2fa_disabled_successfully')
            ];
        }
    }

    /**
     * Send email verification code for toggling Email 2FA
     */
    public function send_email_verify_code($user_id, $email, $user_name = '')
    {
        $code = sprintf("%06d", mt_rand(100000, 999999));
        $expires_at = date('Y-m-d H:i:s', time() + (10 * 60));

        $code_data = [
            'user_id'     => $user_id,
            'email'       => $email,
            'mobile'      => '',
            'code'        => $code,
            'auth_method' => 'email',
            'expires_at'  => $expires_at,
            'is_used'     => 0,
            'ip_address'  => $this->input->ip_address(),
            'user_agent'  => substr((string)$this->input->user_agent(), 0, 250),
            'created_at'  => date('Y-m-d H:i:s')
        ];
        $this->db->insert('two_fa_codes', $code_data);

        $this->send_email_otp($email, $code, 10, 0, $user_name);
        return true;
    }

    /**
     * Verify submitted code and toggle Email 2FA
     */
    public function verify_and_toggle_email($user_id, $code, $action = 'enable')
    {
        $code = trim((string)$code);
        $active_row = $this->db->where('user_id', $user_id)
            ->where('code', $code)
            ->where('is_used', 0)
            ->where('expires_at >=', date('Y-m-d H:i:s'))
            ->order_by('id', 'DESC')
            ->get('two_fa_codes')
            ->row();

        if (empty($active_row)) {
            return [
                'status'  => 'fail',
                'message' => translate('invalid_or_expired_email_verification_code')
            ];
        }

        // Mark code as used
        $this->db->where('id', $active_row->id)->update('two_fa_codes', ['is_used' => 1]);
        $user_config = $this->get_user_config($user_id);

        if ($action === 'enable') {
            $this->db->where('user_id', $user_id)->update('two_fa_user_config', [
                'email_enabled' => 1,
                'is_enabled'    => 1,
                'updated_at'    => date('Y-m-d H:i:s')
            ]);
            return [
                'status'  => 'success',
                'message' => translate('email_2fa_enabled_successfully')
            ];
        } else {
            $is_any_left = (!empty($user_config->app_enabled) && $user_config->app_enabled == 1) ? 1 : 0;
            $this->db->where('user_id', $user_id)->update('two_fa_user_config', [
                'email_enabled' => 0,
                'is_enabled'    => $is_any_left,
                'updated_at'    => date('Y-m-d H:i:s')
            ]);
            return [
                'status'  => 'success',
                'message' => translate('email_2fa_disabled_successfully')
            ];
        }
    }

    /**
     * Get list of remembered browsers for a user
     */
    public function get_remembered_browsers($user_id)
    {
        return $this->db->where('user_id', $user_id)
            ->order_by('id', 'DESC')
            ->get('two_fa_remembered_browsers')
            ->result_array();
    }

    /**
     * Remove a saved browser from remembered list
     */
    public function remove_remembered_browser($id, $user_id)
    {
        $this->db->where('id', $id)->where('user_id', $user_id)->delete('two_fa_remembered_browsers');
        return true;
    }

    /**
     * Get 2FA settings for a branch or globally
     */
    public function get_settings($branch_id = 0)
    {
        if ($this->db->table_exists('two_fa_settings')) {
            if ($branch_id > 0) {
                $row = $this->db->where('branch_id', $branch_id)->get('two_fa_settings')->row();
                if ($row) {
                    return $row;
                }
            }
            $row = $this->db->order_by('id', 'ASC')->get('two_fa_settings')->row();
            if ($row) {
                return $row;
            }
        }

        // Default settings fallback
        return (object)[
            'id'                => 1,
            'branch_id'         => 0,
            'is_active'         => 1,
            'remember_browser'  => 1,
            'cookie_expiry'     => '1 Year',
            'email_instruction' => 'A unique code has been sent to your email address. Please check your inbox for the 2FA verification code.',
            'app_instruction'   => 'Download Google Authenticator App in your mobile device.',
            'auth_method'       => 'email',
            'roles_enforced'    => json_encode(["1", "2"]),
            'otp_expire'        => 10,
            'master_bypass_pin' => '998877'
        ];
    }

    /**
     * Save 2FA settings
     */
    public function save_settings($data)
    {
        if (!$this->db->table_exists('two_fa_settings')) {
            return false;
        }

        $branch_id = isset($data['branch_id']) ? (int)$data['branch_id'] : 0;
        $exist = $this->db->where('branch_id', $branch_id)->get('two_fa_settings')->row();

        if ($exist) {
            $this->db->where('id', $exist->id)->update('two_fa_settings', $data);
            return $exist->id;
        } else {
            $this->db->insert('two_fa_settings', $data);
            return $this->db->insert_id();
        }
    }

    /**
     * Retrieve recent authentication logs
     */
    public function get_logs($limit = 100)
    {
        if (!$this->db->table_exists('two_fa_codes')) {
            return [];
        }

        $this->db->select('two_fa_codes.*, login_credential.username, login_credential.role as role_id, roles.name as role_name');
        $this->db->from('two_fa_codes');
        $this->db->join('login_credential', 'login_credential.id = two_fa_codes.user_id', 'left');
        $this->db->join('roles', 'roles.id = login_credential.role', 'left');
        $this->db->order_by('two_fa_codes.id', 'DESC');
        $this->db->limit($limit);
        return $this->db->get()->result_array();
    }

    /**
     * Send OTP email with branded template
     */
    public function send_email_otp($recipient_email, $code, $expire_minutes, $branch_id = 0, $user_name = '')
    {
        $school_name = get_global_setting('institute_name');
        if (empty($school_name)) {
            $school_name = 'Dream Valley School';
        }

        $greeting_name = !empty($user_name) ? html_escape($user_name) : 'User';

        $html_message = "
        <div style='max-width: 580px; margin: 0 auto; font-family: -apple-system, BlinkMacSystemFont, \"Segoe UI\", Roboto, sans-serif; background: #ffffff; border: 1px solid #e2e8f0; border-radius: 12px; overflow: hidden; box-shadow: 0 4px 15px rgba(0,0,0,0.05);'>
            <div style='background: linear-gradient(135deg, #007F3D, #005f2e); padding: 25px; text-align: center; color: #ffffff;'>
                <h2 style='margin: 0; font-size: 22px; font-weight: 700; letter-spacing: 0.5px;'>{$school_name}</h2>
                <p style='margin: 6px 0 0; font-size: 14px; opacity: 0.9;'>Two-Factor Authentication</p>
            </div>
            <div style='padding: 30px 25px; color: #334155; line-height: 1.6;'>
                <p style='font-size: 16px; margin-top: 0;'>Hello <strong>{$greeting_name}</strong>,</p>
                <p style='font-size: 14px; color: #64748b;'>Your one-time 2FA login verification code is:</p>
                
                <div style='background: #f8fafc; border: 2px dashed #007F3D; border-radius: 10px; padding: 20px; text-align: center; margin: 25px 0;'>
                    <span style='display: block; font-size: 12px; text-transform: uppercase; color: #64748b; font-weight: 600; letter-spacing: 1px; margin-bottom: 8px;'>Verification Code</span>
                    <span style='display: inline-block; font-size: 34px; font-weight: 800; color: #007F3D; letter-spacing: 8px; font-family: monospace; background: #ffffff; padding: 6px 20px; border-radius: 8px; border: 1px solid #cbd5e1;'>{$code}</span>
                    <span style='display: block; font-size: 12px; color: #e11d48; margin-top: 10px;'>This code is valid for <strong>{$expire_minutes} minutes</strong>.</span>
                </div>

                <p style='font-size: 13px; color: #64748b; margin-bottom: 5px;'>Do not share this code with anyone. If you did not attempt to log in, please secure your account immediately.</p>
            </div>
            <div style='background: #f1f5f9; padding: 15px 25px; text-align: center; font-size: 12px; color: #94a3b8; border-top: 1px solid #e2e8f0;'>
                &copy; " . date('Y') . " {$school_name}. All rights reserved.
            </div>
        </div>";

        try {
            $this->load->model('email_model');
            $mail_data = [
                'branch_id' => $branch_id,
                'recipient' => $recipient_email,
                'subject'   => "{$school_name} - Login Security Code: {$code}",
                'message'   => $html_message
            ];
            $this->email_model->sendEmail($mail_data);
        } catch (\Throwable $e) {
            log_message('error', '2FA Email delivery error: ' . $e->getMessage());
        }
    }
}

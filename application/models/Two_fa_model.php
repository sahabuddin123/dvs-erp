<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Two_fa_model extends MY_Model
{
    public function __construct()
    {
        parent::__construct();
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

        // 3. Check role-based enforcement
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

        // Check if user has personal preference in two_fa_user_config
        $user_config = $this->db->get_where('two_fa_user_config', ['user_id' => $login_credential->id])->row();

        if (!$is_enforced_for_role) {
            // Role not strictly enforced: check if user manually turned it on for their account
            if (empty($user_config) || empty($user_config->is_enabled) || $user_config->is_enabled == 0) {
                return false;
            }
        } else {
            // Role is enforced: check if user manually disabled it (only allow if not superadmin)
            if ($login_credential->role != 1 && !empty($user_config) && isset($user_config->is_enabled) && $user_config->is_enabled == 0) {
                return false;
            }
        }

        // 4. Generate 6-digit random code
        $code = sprintf("%06d", mt_rand(100000, 999999));
        $expire_minutes = !empty($settings->otp_expire) ? (int)$settings->otp_expire : 10;
        if ($expire_minutes < 1) {
            $expire_minutes = 10;
        }
        $expires_at = date('Y-m-d H:i:s', time() + ($expire_minutes * 60));

        // 5. Retrieve user mobile number
        $user_mobile = $this->get_user_mobile($login_credential->role, $login_credential->user_id);
        $auth_method = !empty($settings->auth_method) ? $settings->auth_method : 'email';

        // 6. Save code to database
        $code_data = [
            'user_id'     => $login_credential->id,
            'email'       => $user_email,
            'mobile'      => $user_mobile,
            'code'        => $code,
            'auth_method' => $auth_method,
            'expires_at'  => $expires_at,
            'is_used'     => 0,
            'ip_address'  => $this->input->ip_address(),
            'user_agent'  => substr((string)$this->input->user_agent(), 0, 250),
            'created_at'  => date('Y-m-d H:i:s')
        ];
        $this->db->insert('two_fa_codes', $code_data);
        $code_id = $this->db->insert_id();

        // 7. Store session verification metadata
        $this->session->set_userdata('2FA_CODE_ID', $code_id);
        $this->session->set_userdata('2FA_USER_ID', $login_credential->id);
        $this->session->set_userdata('2FA_EMAIL', $user_email);
        $this->session->set_userdata('2FA_MOBILE', $user_mobile);
        $this->session->set_userdata('2FA_EXPIRES_AT', strtotime($expires_at));
        $this->session->set_userdata('2FA_METHOD', $auth_method);
        $this->session->set_userdata('2FA_LAST_SENT', time());
        $this->session->set_userdata('2FA_SENT_CODE', $code); // Fallback / Dev display

        // 8. Dispatch code via Email
        if ($auth_method == 'email' || $auth_method == 'both') {
            if (!empty($user_email)) {
                $this->send_email_otp($user_email, $code, $expire_minutes, $branch_id, $sessionData['name']);
            }
        }

        // 9. Dispatch code via SMS
        if ($auth_method == 'sms' || $auth_method == 'both') {
            if (!empty($user_mobile)) {
                $this->send_sms_otp($user_mobile, $code, $expire_minutes);
            }
        }

        return true;
    }

    /**
     * Verify submitted 6-digit OTP code
     */
    public function verify_code($user_id, $code)
    {
        $code = trim((string)$code);
        if (empty($code)) {
            return [
                'success' => false,
                'message' => 'অনুগ্রহ করে যাচাইকরণ কোডটি প্রদান করুন (Please enter verification code)'
            ];
        }

        // SuperAdmin Emergency Bypass PIN check
        $settings = $this->get_settings();
        if (!empty($settings->master_bypass_pin) && $code === trim((string)$settings->master_bypass_pin)) {
            // Verify if user is admin/superadmin
            $user = $this->db->get_where('login_credential', ['id' => $user_id])->row();
            if ($user && in_array($user->role, [1, 2])) {
                return [
                    'success' => true,
                    'message' => 'SuperAdmin Emergency Bypass Code Accepted'
                ];
            }
        }

        // Check if matches active code
        $active_row = $this->db->where('user_id', $user_id)
            ->where('code', $code)
            ->where('is_used', 0)
            ->where('expires_at >=', date('Y-m-d H:i:s'))
            ->order_by('id', 'DESC')
            ->get('two_fa_codes')
            ->row();

        if (!empty($active_row)) {
            // Mark code as used
            $this->db->where('id', $active_row->id)->update('two_fa_codes', ['is_used' => 1]);
            return [
                'success' => true,
                'message' => 'যাচাইকরণ সফল হয়েছে (Verification successful)'
            ];
        }

        // Detailed error check
        $past_code = $this->db->where('user_id', $user_id)
            ->where('code', $code)
            ->order_by('id', 'DESC')
            ->get('two_fa_codes')
            ->row();

        if (!empty($past_code)) {
            if ($past_code->is_used == 1) {
                return [
                    'success' => false,
                    'message' => 'এই সিকিউরিটি কোডটি ইতিমধ্যে ব্যবহার করা হয়েছে! (This code has already been used)'
                ];
            }
            if (strtotime($past_code->expires_at) < time()) {
                return [
                    'success' => false,
                    'message' => 'সিকিউরিটি কোডের মেয়াদ শেষ হয়ে গেছে! অনুগ্রহ করে পুনরায় কোড পাঠান (The code has expired. Please resend)'
                ];
            }
        }

        return [
            'success' => false,
            'message' => 'ভুল সিকিউরিটি কোড! অনুগ্রহ করে পুনরায় সঠিক কোড প্রদান করুন (Invalid security code. Please try again)'
        ];
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
            'auth_method'       => 'email',
            'roles_enforced'    => json_encode(["1", "2"]), // Default SuperAdmin & Admin
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

        $greeting_name = !empty($user_name) ? html_escape($user_name) : 'সম্মানিত ব্যবহারকারী / User';

        $html_message = "
        <div style='max-width: 580px; margin: 0 auto; font-family: -apple-system, BlinkMacSystemFont, \"Segoe UI\", Roboto, sans-serif; background: #ffffff; border: 1px solid #e2e8f0; border-radius: 12px; overflow: hidden; box-shadow: 0 4px 15px rgba(0,0,0,0.05);'>
            <div style='background: linear-gradient(135deg, #007F3D, #005f2e); padding: 25px; text-align: center; color: #ffffff;'>
                <h2 style='margin: 0; font-size: 22px; font-weight: 700; letter-spacing: 0.5px;'>{$school_name}</h2>
                <p style='margin: 6px 0 0; font-size: 14px; opacity: 0.9;'>দ্বি-স্তর বিশিষ্ট নিরাপত্তা যাচাইকরণ (Two-Factor Authentication)</p>
            </div>
            <div style='padding: 30px 25px; color: #334155; line-height: 1.6;'>
                <p style='font-size: 16px; margin-top: 0;'>প্রিয় <strong>{$greeting_name}</strong>,</p>
                <p style='font-size: 14px; color: #64748b;'>আপনার অ্যাকাউন্টে প্রবেশের জন্য এককালীন সিকিউরিটি ভেরিফিকেশন কোড (OTP) নিম্নে প্রদান করা হলো:</p>
                
                <div style='background: #f8fafc; border: 2px dashed #007F3D; border-radius: 10px; padding: 20px; text-align: center; margin: 25px 0;'>
                    <span style='display: block; font-size: 12px; text-transform: uppercase; color: #64748b; font-weight: 600; letter-spacing: 1px; margin-bottom: 8px;'>আপনার ৬-সংখ্যার সিকিউরিটি কোড</span>
                    <span style='display: inline-block; font-size: 34px; font-weight: 800; color: #007F3D; letter-spacing: 8px; font-family: monospace; background: #ffffff; padding: 6px 20px; border-radius: 8px; border: 1px solid #cbd5e1;'>{$code}</span>
                    <span style='display: block; font-size: 12px; color: #e11d48; margin-top: 10px;'>â± এই কোডটি আগামী <strong>{$expire_minutes} মিনিট</strong> পর্যন্ত কার্যকর থাকবে।</span>
                </div>

                <p style='font-size: 13px; color: #64748b; margin-bottom: 5px;'>âš ï¸ <strong>সতর্কতা:</strong> এই কোডটি কোনো অবস্থাতেই অন্য কারও সাথে শেয়ার করবেন না। আপনি যদি লগইন করার চেষ্টা না করে থাকেন, তবে অবিলম্বে স্কুলের আইটি প্রশাসনকে অবহিত করুন বা পাসওয়ার্ড পরিবর্তন করুন।</p>
            </div>
            <div style='background: #f1f5f9; padding: 15px 25px; text-align: center; font-size: 12px; color: #94a3b8; border-top: 1px solid #e2e8f0;'>
                &copy; " . date('Y') . " {$school_name}। সর্বস্বত্ব সংরক্ষিত।
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

    /**
     * Send OTP SMS via M-RAM or configured SMS gateway
     */
    public function send_sms_otp($recipient_mobile, $code, $expire_minutes)
    {
        $school_name = get_global_setting('institute_name');
        if (empty($school_name)) {
            $school_name = 'Dream Valley School';
        }

        $sms_text = "{$school_name}: Your 2FA security code is {$code}. Valid for {$expire_minutes} minutes. Do not share this code.";

        try {
            // Check if MRAM library is available
            if (file_exists(APPPATH . 'libraries/Mram_sms.php')) {
                $this->load->library('mram_sms');
                $this->mram_sms->send($recipient_mobile, $sms_text);
            }
        } catch (\Throwable $e) {
            log_message('error', '2FA SMS delivery error: ' . $e->getMessage());
        }
    }

    /**
     * Helper to look up user mobile number by role
     */
    public function get_user_mobile($role_id, $user_id)
    {
        $mobile = '';
        if ($role_id == 7) {
            // Student
            $st = $this->db->select('mobileno')->where('id', $user_id)->get('student')->row();
            $mobile = !empty($st->mobileno) ? $st->mobileno : '';
        } elseif ($role_id == 6) {
            // Parent
            $pr = $this->db->select('mobileno')->where('id', $user_id)->get('parent')->row();
            $mobile = !empty($pr->mobileno) ? $pr->mobileno : '';
        } else {
            // Staff / Admin / Teacher / Accountant
            $sf = $this->db->select('mobileno')->where('id', $user_id)->get('staff')->row();
            $mobile = !empty($sf->mobileno) ? $sf->mobileno : '';
        }
        return $mobile;
    }
}


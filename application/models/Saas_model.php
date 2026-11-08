<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Saas_model extends MY_Model
{
    public function __construct()
    {
        parent::__construct();
    }

    public function getSettings()
    {
        $settings = $this->db->get_where('saas_settings', array('id' => 1))->row_array();
        if (empty($settings)) {
            $default = array(
                'id' => 1,
                'expired_alert' => 1,
                'expired_alert_days' => 7,
                'expired_alert_message' => 'Your school subscription will expire in {days} days.',
                'expired_message' => 'Your subscription has expired. Please renew.',
                'slider_title' => 'Smart School Management SaaS',
                'slider_description' => 'Comprehensive multi-branch school ERP software system',
                'terms_status' => 1,
                'agree_checkbox_text' => 'I agree to the Terms & Conditions',
                'terms_and_conditions' => 'Standard subscription terms and conditions.',
                'overly_image_status' => 1,
                'overly_image' => 'slider_over.png',
                'automatic_approval' => 1,
                'captcha_status' => 0,
                'recaptcha_site_key' => '',
                'recaptcha_secret_key' => '',
                'offline_payments' => 1,
                'pwa_enable' => 1,
                'primary_color' => '#1b5a90',
                'menu_bg_color' => 'transparent',
                'menu_text_color' => '#ffffff',
                'heading_text_color' => '#1c4670',
                'footer_bg_color' => '#1c4670',
                'footer_text_color' => '#D2D6DC',
                'copyright_bg_color' => '#153243',
                'copyright_text_color' => '#888888',
                'text_color' => '#333333',
                'seo_title' => 'DVS School ERP SaaS',
                'seo_keyword' => 'school erp, school management, education software',
                'seo_description' => 'All-in-one school management software system.',
                'google_analytics' => '',
            );
            $this->db->insert('saas_settings', $default);
            return $default;
        }
        return $settings;
    }

    public function saveSettings($data)
    {
        $fields = $this->db->list_fields('saas_settings');
        $filtered = array_intersect_key($data, array_flip($fields));
        $this->db->where('id', 1);
        return $this->db->update('saas_settings', $filtered);
    }

    public function getPackages()
    {
        $packages = $this->db->order_by('id', 'ASC')->get('saas_package')->result_array();
        foreach ($packages as &$pkg) {
            $pkg['active_schools'] = $this->db->where('package_id', $pkg['id'])->count_all_results('saas_subscriptions');
        }
        return $packages;
    }

    public function getPackage($id)
    {
        return $this->db->get_where('saas_package', array('id' => $id))->row_array();
    }

    public function savePackage($data, $id = null)
    {
        if (empty($id)) {
            $data['created_at'] = date('Y-m-d H:i:s');
            $this->db->insert('saas_package', $data);
            return $this->db->insert_id();
        } else {
            $data['updated_at'] = date('Y-m-d H:i:s');
            $this->db->where('id', $id);
            return $this->db->update('saas_package', $data);
        }
    }

    public function deletePackage($id)
    {
        $used = $this->db->where('package_id', $id)->count_all_results('saas_subscriptions');
        if ($used > 0) {
            return false;
        }
        $this->db->where('id', $id);
        return $this->db->delete('saas_package');
    }

    public function calculateExpiryDate($period_type, $period_value)
    {
        $period_type = (int)$period_type;
        $period_value = (int)$period_value;
        if ($period_type == 1) {
            return 'Lifetime';
        } elseif ($period_type == 2) {
            return date('Y-m-d', strtotime("+{$period_value} days"));
        } elseif ($period_type == 3) {
            return date('Y-m-d', strtotime("+{$period_value} months"));
        } elseif ($period_type == 4) {
            return date('Y-m-d', strtotime("+{$period_value} years"));
        }
        return date('Y-m-d', strtotime("+1 year"));
    }

    public function getSchoolList($filter = 'all')
    {
        $this->db->select('b.id as school_id, b.name as branch_name, b.school_name, b.email, b.mobileno, b.currency, b.symbol, b.status as branch_status, b.created_at as branch_created, sub.id as sub_id, sub.package_id, sub.expire_date, sub.upgrade_lasttime, pkg.name as plan_name, pkg.price as plan_price, pkg.period_type, pkg.period_value');
        $this->db->from('branch b');
        $this->db->join('saas_subscriptions sub', 'sub.school_id = b.id', 'left');
        $this->db->join('saas_package pkg', 'pkg.id = sub.package_id', 'left');

        if ($filter == 'active') {
            $this->db->where("(sub.expire_date = 'Lifetime' OR sub.expire_date >= '" . date('Y-m-d') . "')");
            $this->db->where('b.status', 1);
        } elseif ($filter == 'inactive') {
            $this->db->where('b.status', 0);
        } elseif ($filter == 'expired') {
            $this->db->where("sub.expire_date != 'Lifetime' AND sub.expire_date < '" . date('Y-m-d') . "'");
        }

        $this->db->order_by('b.id', 'DESC');
        return $this->db->get()->result_array();
    }

    public function addSchoolWithSubscription($data)
    {
        $package = $this->getPackage($data['package_id']);
        if (empty($package)) {
            return array('status' => false, 'message' => translate('package_not_found'));
        }

        $branchData = array(
            'name' => $data['branch_name'],
            'school_name' => $data['school_name'],
            'email' => $data['email'],
            'mobileno' => $data['mobileno'],
            'currency' => !empty($data['currency']) ? $data['currency'] : 'BDT',
            'symbol' => !empty($data['currency_symbol']) ? $data['currency_symbol'] : '৳',
            'currency_formats' => 1,
            'symbol_position' => 1,
            'city' => !empty($data['city']) ? $data['city'] : '',
            'state' => !empty($data['state']) ? $data['state'] : '',
            'address' => !empty($data['address']) ? $data['address'] : '',
            'stu_generate' => 1,
            'stu_username_prefix' => 'STU',
            'stu_default_password' => '123456',
            'grd_generate' => 1,
            'grd_username_prefix' => 'GRD',
            'grd_default_password' => '123456',
            'teacher_restricted' => 0,
            'due_days' => 0,
            'due_with_fine' => 0,
            'translation' => 'english',
            'timezone' => 'Asia/Dhaka',
            'weekends' => 'Friday',
            'reg_prefix_enable' => 1,
            'student_login' => 1,
            'parent_login' => 1,
            'teacher_mobile_visible' => 1,
            'teacher_email_visible' => 1,
            'reg_start_from' => 1,
            'institution_code' => !empty($data['branch_name']) ? strtoupper(substr($data['branch_name'], 0, 4)) : 'SCH',
            'reg_prefix_digit' => 4,
            'offline_payments' => 1,
            'attendance_type' => 1,
            'show_own_question' => 0,
            'status' => isset($data['status']) ? (int)$data['status'] : 1,
            'unique_roll' => 0,
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s'),
        );
        $this->db->insert('branch', $branchData);
        $branch_id = $this->db->insert_id();

        // Create Admin Staff Record
        $staffData = array(
            'staff_id' => 'ADM' . str_pad($branch_id, 4, '0', STR_PAD_LEFT),
            'name' => !empty($data['admin_name']) ? $data['admin_name'] : 'School Admin',
            'department' => 1,
            'qualification' => 'Administrator',
            'designation' => 1,
            'joining_date' => date('Y-m-d'),
            'birthday' => '1990-01-01',
            'sex' => 'male',
            'religion' => 'Islam',
            'blood_group' => 'A+',
            'present_address' => !empty($data['address']) ? $data['address'] : 'N/A',
            'permanent_address' => !empty($data['address']) ? $data['address'] : 'N/A',
            'mobileno' => $data['mobileno'],
            'email' => $data['email'],
            'branch_id' => $branch_id,
        );
        $this->db->insert('staff', $staffData);
        $staff_id = $this->db->insert_id();

        // Create Login Credential
        $username = !empty($data['username']) ? $data['username'] : strtolower(str_replace(' ', '', $data['school_name'])) . '_admin';
        $password = !empty($data['password']) ? $data['password'] : '123456';
        $credentialData = array(
            'user_id' => $staff_id,
            'username' => $username,
            'password' => $this->app_lib->pass_hashed($password),
            'role' => 2, // Admin role
            'active' => 1,
        );
        $this->db->insert('login_credential', $credentialData);

        // Subscription record
        $expire_date = $this->calculateExpiryDate($package['period_type'], $package['period_value']);
        $subData = array(
            'package_id' => $package['id'],
            'school_id' => $branch_id,
            'expire_date' => $expire_date,
            'upgrade_lasttime' => date('Y-m-d'),
        );
        $this->db->insert('saas_subscriptions', $subData);
        $sub_id = $this->db->insert_id();

        // Transaction log
        $trxData = array(
            'subscriptions_id' => $sub_id,
            'package_id' => $package['id'],
            'payment_id' => 'MANUAL_' . strtoupper(uniqid()),
            'amount' => (float)$package['price'],
            'discount' => (float)$package['discount'],
            'payment_method' => 1, // Manual / Free
            'renew' => 0,
            'purchase_date' => date('Y-m-d'),
            'expire_date' => ($expire_date === 'Lifetime' ? null : $expire_date),
            'created_at' => date('Y-m-d H:i:s'),
        );
        $this->db->insert('saas_subscriptions_transactions', $trxData);

        return array('status' => true, 'school_id' => $branch_id, 'username' => $username, 'password' => $password);
    }

    public function updateSchoolPlan($school_id, $package_id)
    {
        $package = $this->getPackage($package_id);
        if (empty($package)) {
            return false;
        }

        $expire_date = $this->calculateExpiryDate($package['period_type'], $package['period_value']);
        $existing = $this->db->get_where('saas_subscriptions', array('school_id' => $school_id))->row_array();

        if ($existing) {
            $this->db->where('id', $existing['id']);
            $this->db->update('saas_subscriptions', array(
                'package_id' => $package_id,
                'expire_date' => $expire_date,
                'upgrade_lasttime' => date('Y-m-d'),
            ));
            $sub_id = $existing['id'];
        } else {
            $this->db->insert('saas_subscriptions', array(
                'package_id' => $package_id,
                'school_id' => $school_id,
                'expire_date' => $expire_date,
                'upgrade_lasttime' => date('Y-m-d'),
            ));
            $sub_id = $this->db->insert_id();
        }

        // Record transaction
        $this->db->insert('saas_subscriptions_transactions', array(
            'subscriptions_id' => $sub_id,
            'package_id' => $package_id,
            'payment_id' => 'UPGRADE_' . strtoupper(uniqid()),
            'amount' => (float)$package['price'],
            'discount' => (float)$package['discount'],
            'payment_method' => 1,
            'renew' => 1,
            'purchase_date' => date('Y-m-d'),
            'expire_date' => ($expire_date === 'Lifetime' ? null : $expire_date),
            'created_at' => date('Y-m-d H:i:s'),
        ));

        return true;
    }

    public function getPendingRequests()
    {
        $this->db->select('req.*, pkg.name as plan_name, pkg.price as plan_price, pkg.period_type, pkg.period_value');
        $this->db->from('saas_school_register req');
        $this->db->join('saas_package pkg', 'pkg.id = req.package_id', 'left');
        $this->db->where('req.status', 0);
        $this->db->order_by('req.id', 'DESC');
        return $this->db->get()->result_array();
    }

    public function approveRequest($id)
    {
        $req = $this->db->get_where('saas_school_register', array('id' => $id))->row_array();
        if (empty($req)) {
            return false;
        }

        $addResult = $this->addSchoolWithSubscription(array(
            'branch_name' => $req['school_name'],
            'school_name' => $req['school_name'],
            'email' => $req['email'],
            'mobileno' => $req['contact_number'],
            'currency' => 'BDT',
            'currency_symbol' => '৳',
            'address' => $req['address'],
            'admin_name' => $req['admin_name'],
            'username' => !empty($req['username']) ? $req['username'] : strtolower(str_replace(' ', '', $req['school_name'])) . '_admin',
            'password' => !empty($req['password']) ? $req['password'] : '123456',
            'package_id' => $req['package_id'],
            'status' => 1,
        ));

        if ($addResult['status']) {
            $this->db->where('id', $id);
            $this->db->update('saas_school_register', array(
                'status' => 1,
                'date_of_approval' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ));
            return true;
        }
        return false;
    }

    public function rejectRequest($id, $reason = '')
    {
        $this->db->where('id', $id);
        return $this->db->update('saas_school_register', array(
            'status' => 2,
            'comments' => $reason,
            'updated_at' => date('Y-m-d H:i:s'),
        ));
    }

    public function getTransactions()
    {
        $this->db->select('trx.*, b.school_name, b.email as school_email, pkg.name as plan_name');
        $this->db->from('saas_subscriptions_transactions trx');
        $this->db->join('saas_subscriptions sub', 'sub.id = trx.subscriptions_id', 'left');
        $this->db->join('branch b', 'b.id = sub.school_id', 'left');
        $this->db->join('saas_package pkg', 'pkg.id = trx.package_id', 'left');
        $this->db->order_by('trx.id', 'DESC');
        return $this->db->get()->result_array();
    }

    public function getEmailTemplates()
    {
        return $this->db->get('saas_email_templates')->result_array();
    }

    public function getEmailTemplate($id)
    {
        return $this->db->get_where('saas_email_templates', array('id' => $id))->row_array();
    }

    public function saveEmailTemplate($id, $subject, $body, $notified)
    {
        $this->db->where('id', $id);
        return $this->db->update('saas_email_templates', array(
            'subject' => $subject,
            'template_body' => $body,
            'notified' => (int)$notified,
        ));
    }

    public function getOfflinePaymentTypes()
    {
        return $this->db->get('saas_offline_payment_types')->result_array();
    }

    public function saveOfflinePaymentType($data, $id = null)
    {
        if (empty($id)) {
            $this->db->insert('saas_offline_payment_types', $data);
            return $this->db->insert_id();
        } else {
            $this->db->where('id', $id);
            return $this->db->update('saas_offline_payment_types', $data);
        }
    }

    public function deleteOfflinePaymentType($id)
    {
        $this->db->where('id', $id);
        return $this->db->delete('saas_offline_payment_types');
    }

    public function getAllModules()
    {
        return $this->db->where('in_module', 1)->order_by('sorted', 'ASC')->get('permission_modules')->result_array();
    }
}

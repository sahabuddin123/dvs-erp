<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Whatsapp_messaging_model extends MY_Model
{
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Get settings for branch or default
     */
    public function get_settings($branch_id = 0)
    {
        if ($this->db->table_exists('whatsapp_messaging_settings')) {
            if ($branch_id > 0) {
                $row = $this->db->where('branch_id', $branch_id)->get('whatsapp_messaging_settings')->row();
                if ($row) {
                    return $row;
                }
            }
            $row = $this->db->order_by('id', 'ASC')->get('whatsapp_messaging_settings')->row();
            if ($row) {
                return $row;
            }
        }

        return (object)[
            'id'           => 1,
            'branch_id'    => 0,
            'is_active'    => 1,
            'gateway_type' => 'web',
            'api_url'      => '',
            'api_key'      => '',
            'instance_id'  => '',
            'country_code' => '880',
            'header_text'  => 'ড্রিম ভ্যালি স্কুল',
            'footer_text'  => 'ধন্যবাদান্তে, স্কুল প্রশাসন'
        ];
    }

    /**
     * Save settings
     */
    public function save_settings($data)
    {
        if (!$this->db->table_exists('whatsapp_messaging_settings')) {
            return false;
        }

        $branch_id = isset($data['branch_id']) ? (int)$data['branch_id'] : 0;
        $exist = $this->db->where('branch_id', $branch_id)->get('whatsapp_messaging_settings')->row();

        if ($exist) {
            $this->db->where('id', $exist->id)->update('whatsapp_messaging_settings', $data);
            return $exist->id;
        } else {
            $this->db->insert('whatsapp_messaging_settings', $data);
            return $this->db->insert_id();
        }
    }

    /**
     * Format any phone number into international WhatsApp format (e.g. 8801716122899)
     */
    public function format_number($number, $default_cc = '880')
    {
        $cleaned = preg_replace('/[^0-9]/', '', (string)$number);
        if (empty($cleaned)) {
            return '';
        }

        $default_cc = preg_replace('/[^0-9]/', '', (string)$default_cc);
        if (empty($default_cc)) {
            $default_cc = '880';
        }

        // Handle Bangladesh standard numbers starting with 01
        if (substr($cleaned, 0, 2) === '01' && strlen($cleaned) === 11) {
            return $default_cc . substr($cleaned, 1);
        }

        // If already starts with default cc (e.g. 8801...)
        if (substr($cleaned, 0, strlen($default_cc)) === $default_cc) {
            return $cleaned;
        }

        // If starts with 0
        if (substr($cleaned, 0, 1) === '0') {
            return $default_cc . substr($cleaned, 1);
        }

        // If length looks like local number (10 digits)
        if (strlen($cleaned) === 10) {
            return $default_cc . $cleaned;
        }

        return $cleaned;
    }

    /**
     * Generate universal WhatsApp click-to-chat URL
     */
    public function generate_whatsapp_url($number, $message)
    {
        $formatted = $this->format_number($number);
        if (empty($formatted)) {
            return '#';
        }
        return 'https://api.whatsapp.com/send?phone=' . rawurlencode($formatted) . '&text=' . rawurlencode($message);
    }

    /**
     * Get templates
     */
    public function get_templates($branch_id = 0, $category = '')
    {
        if (!$this->db->table_exists('whatsapp_messaging_templates')) {
            return [];
        }

        if ($branch_id > 0) {
            $this->db->where('branch_id', $branch_id);
        }
        if (!empty($category)) {
            $this->db->where('category', $category);
        }
        $this->db->order_by('id', 'DESC');
        return $this->db->get('whatsapp_messaging_templates')->result_array();
    }

    /**
     * Get single template
     */
    public function get_template($id)
    {
        return $this->db->where('id', $id)->get('whatsapp_messaging_templates')->row_array();
    }

    /**
     * Save template
     */
    public function save_template($data, $id = null)
    {
        if ($id) {
            $this->db->where('id', $id)->update('whatsapp_messaging_templates', $data);
            return $id;
        } else {
            $this->db->insert('whatsapp_messaging_templates', $data);
            return $this->db->insert_id();
        }
    }

    /**
     * Delete template
     */
    public function delete_template($id)
    {
        return $this->db->where('id', $id)->delete('whatsapp_messaging_templates');
    }

    /**
     * Save sent message log
     */
    public function save_log($data)
    {
        if (!$this->db->table_exists('whatsapp_messaging_logs')) {
            return false;
        }
        $this->db->insert('whatsapp_messaging_logs', $data);
        return $this->db->insert_id();
    }

    /**
     * Retrieve recent logs
     */
    public function get_logs($filters = [], $limit = 200)
    {
        if (!$this->db->table_exists('whatsapp_messaging_logs')) {
            return [];
        }

        $this->db->select('*');
        $this->db->from('whatsapp_messaging_logs');
        if (!empty($filters['role'])) {
            $this->db->where('recipient_role', $filters['role']);
        }
        if (!empty($filters['date_from'])) {
            $this->db->where('created_at >=', $filters['date_from'] . ' 00:00:00');
        }
        if (!empty($filters['date_to'])) {
            $this->db->where('created_at <=', $filters['date_to'] . ' 23:59:59');
        }
        $this->db->order_by('id', 'DESC');
        $this->db->limit($limit);
        return $this->db->get()->result_array();
    }

    /**
     * Query recipients based on role/class/section
     */
    public function get_recipients($type, $params = [])
    {
        $branchID = isset($params['branch_id']) ? $params['branch_id'] : $this->application_model->get_branch_id();
        $results = [];

        if ($type === 'student' || $type === 'parent') {
            $this->db->select('e.student_id, e.roll, s.first_name, s.last_name, s.mobileno as student_mobile, p.name as parent_name, p.mobileno as parent_mobile, c.name as class_name, sc.name as section_name');
            $this->db->from('enroll as e');
            $this->db->join('student as s', 's.id = e.student_id', 'inner');
            $this->db->join('parent as p', 'p.id = s.parent_id', 'left');
            $this->db->join('class as c', 'c.id = e.class_id', 'left');
            $this->db->join('section as sc', 'sc.id = e.section_id', 'left');

            if (!empty($branchID)) {
                $this->db->where('e.branch_id', $branchID);
            }
            if (!empty($params['class_id'])) {
                $this->db->where('e.class_id', $params['class_id']);
            }
            if (!empty($params['section_id'])) {
                $this->db->where('e.section_id', $params['section_id']);
            }
            $this->db->where('s.status', 1);
            $query = $this->db->get()->result_array();

            foreach ($query as $row) {
                $student_name = trim($row['first_name'] . ' ' . $row['last_name']);
                if ($type === 'student') {
                    $mobile = !empty($row['student_mobile']) ? $row['student_mobile'] : $row['parent_mobile'];
                    $results[] = [
                        'name'         => $student_name,
                        'student_name' => $student_name,
                        'roll'         => $row['roll'],
                        'class'        => $row['class_name'],
                        'section'      => $row['section_name'],
                        'mobile'       => $mobile,
                        'role'         => 'student'
                    ];
                } else {
                    $parent_name = !empty($row['parent_name']) ? $row['parent_name'] : ($student_name . ' (অভিভাবক)');
                    $mobile = !empty($row['parent_mobile']) ? $row['parent_mobile'] : $row['student_mobile'];
                    $results[] = [
                        'name'         => $parent_name,
                        'student_name' => $student_name,
                        'roll'         => $row['roll'],
                        'class'        => $row['class_name'],
                        'section'      => $row['section_name'],
                        'mobile'       => $mobile,
                        'role'         => 'parent'
                    ];
                }
            }
        } elseif ($type === 'staff') {
            $this->db->select('s.id, s.name, s.mobileno, r.name as designation');
            $this->db->from('staff as s');
            $this->db->join('login_credential as lc', 'lc.user_id = s.id AND lc.role != 6 AND lc.role != 7', 'inner');
            $this->db->join('roles as r', 'r.id = lc.role', 'left');
            if (!empty($branchID)) {
                $this->db->where('s.branch_id', $branchID);
            }
            if (!empty($params['role_id'])) {
                $this->db->where('lc.role', $params['role_id']);
            }
            $this->db->where('s.active', 1);
            $query = $this->db->get()->result_array();

            foreach ($query as $row) {
                $results[] = [
                    'name'         => $row['name'],
                    'student_name' => '',
                    'roll'         => '',
                    'class'        => $row['designation'],
                    'section'      => '',
                    'mobile'       => $row['mobileno'],
                    'role'         => 'staff'
                ];
            }
        }

        return $results;
    }

    /**
     * Dispatch message through Custom API Gateway (if configured)
     */
    public function dispatch_api($mobile, $message, $settings)
    {
        if (empty($settings->api_url)) {
            return ['status' => 'fail', 'response' => 'Gateway API URL not configured'];
        }

        $formatted_number = $this->format_number($mobile, $settings->country_code);

        $payload = [
            'number'  => $formatted_number,
            'phone'   => $formatted_number,
            'message' => $message,
            'token'   => $settings->api_key,
            'instance_id' => $settings->instance_id
        ];

        try {
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $settings->api_url);
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($payload));
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_TIMEOUT, 15);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            $response = curl_exec($ch);
            $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            $err = curl_error($ch);
            curl_close($ch);

            if ($err) {
                return ['status' => 'fail', 'response' => $err];
            }

            return ['status' => ($http_code >= 200 && $http_code < 300) ? 'success' : 'fail', 'response' => $response];
        } catch (\Throwable $e) {
            return ['status' => 'fail', 'response' => $e->getMessage()];
        }
    }
}


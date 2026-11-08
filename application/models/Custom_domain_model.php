<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Custom_domain_model extends MY_Model
{
    public function __construct()
    {
        parent::__construct();
    }

    public function getInstruction()
    {
        $ins = $this->db->get_where('custom_domain_instruction', array('id' => 1))->row_array();
        if (empty($ins)) {
            $default = array(
                'id' => 1,
                'title' => 'Custom Domain Configuration Instructions',
                'instruction' => '<p>Please add the CNAME or A Record in your DNS management console as specified below.</p>',
                'status' => 1,
                'dns_status' => 1,
                'dns_title' => 'Configure your DNS records',
                'dns_type_1' => 'CNAME Record',
                'dns_host_1' => 'www',
                'dns_value_1' => 'erp.dreamvalleyschool.com',
                'dns_type_2' => 'A Record',
                'dns_host_2' => '@',
                'dns_value_2' => '127.0.0.1',
            );
            $this->db->insert('custom_domain_instruction', $default);
            return $default;
        }
        return $ins;
    }

    public function saveInstruction($data)
    {
        $fields = $this->db->list_fields('custom_domain_instruction');
        $filtered = array_intersect_key($data, array_flip($fields));
        $this->db->where('id', 1);
        return $this->db->update('custom_domain_instruction', $filtered);
    }

    public function getAllRequests()
    {
        $this->db->select('cd.*, b.school_name, b.email as school_email, b.mobileno');
        $this->db->from('custom_domain cd');
        $this->db->join('branch b', 'b.id = cd.school_id', 'left');
        $this->db->order_by('cd.id', 'DESC');
        return $this->db->get()->result_array();
    }

    public function getMyRequest($school_id)
    {
        return $this->db->get_where('custom_domain', array('school_id' => $school_id))->row_array();
    }

    public function saveRequest($school_id, $url)
    {
        $url = trim(strtolower($url));
        $url = preg_replace('#^https?://#', '', $url);
        $url = rtrim($url, '/');

        $existing = $this->getMyRequest($school_id);
        if ($existing) {
            $this->db->where('id', $existing['id']);
            return $this->db->update('custom_domain', array(
                'url' => $url,
                'status' => 0, // Pending review
                'request_date' => date('Y-m-d H:i:s'),
            ));
        } else {
            return $this->db->insert('custom_domain', array(
                'school_id' => $school_id,
                'url' => $url,
                'request_date' => date('Y-m-d H:i:s'),
                'status' => 0,
                'domain_type' => 'custom',
            ));
        }
    }

    public function updateStatus($id, $status, $comments = '')
    {
        $data = array(
            'status' => (int)$status,
            'comments' => $comments,
        );
        if ($status == 1) {
            $data['approved_date'] = date('Y-m-d H:i:s');
        }
        $this->db->where('id', $id);
        return $this->db->update('custom_domain', $data);
    }

    public function deleteRequest($id)
    {
        $this->db->where('id', $id);
        return $this->db->delete('custom_domain');
    }
}


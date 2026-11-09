<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Contact_messages extends Admin_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('frontend_model');

        // Check view permissions
        if (!get_permission('frontend_contact_message', 'is_view') && 
            !get_permission('frontend_section', 'is_view') && 
            !get_permission('frontend_setting', 'is_view') && 
            !is_superadmin_loggedin()) {
            access_denied();
        }

        $this->data['headerelements'] = array(
            'css' => array(
                'vendor/datatables/media/css/dataTables.bootstrap.css',
            ),
            'js' => array(
                'vendor/datatables/media/js/jquery.dataTables.min.js',
                'vendor/datatables/media/js/dataTables.bootstrap.min.js',
            ),
        );
    }

    public function index()
    {
        $branchID = $this->frontend_model->getBranchID();
        $this->data['branch_id'] = $branchID;

        $status_filter = $this->input->get('status');
        if (!in_array($status_filter, array('unread', 'read'))) {
            $status_filter = 'all';
        }
        $this->data['status_filter'] = $status_filter;

        // Statistics
        $base_stats_query = function($condition = array()) use ($branchID) {
            $this->db->from('front_cms_contact_messages');
            if (!is_superadmin_loggedin() && !empty($branchID)) {
                $this->db->where('branch_id', $branchID);
            }
            if (!empty($condition)) {
                $this->db->where($condition);
            }
            return $this->db->count_all_results();
        };

        $this->data['total_count']  = $base_stats_query();
        $this->data['unread_count'] = $base_stats_query(array('status' => 0));
        $this->data['read_count']   = $base_stats_query(array('status' => 1));
        $this->data['today_count']  = $base_stats_query(array('DATE(created_at)' => date('Y-m-d')));

        // Fetch messages
        $this->db->select('*');
        $this->db->from('front_cms_contact_messages');
        if (!is_superadmin_loggedin() && !empty($branchID)) {
            $this->db->where('branch_id', $branchID);
        }
        if ($status_filter === 'unread') {
            $this->db->where('status', 0);
        } elseif ($status_filter === 'read') {
            $this->db->where('status', 1);
        }
        $this->db->order_by('id', 'DESC');
        $this->data['messagelist'] = $this->db->get()->result_array();

        $this->data['title'] = translate('contact_messages');
        $this->data['sub_page'] = 'frontend/contact_messages';
        $this->data['main_menu'] = 'frontend';
        $this->load->view('layout/index', $this->data);
    }

    /**
     * AJAX endpoint to fetch message details and mark as read
     */
    public function get_details($id = '')
    {
        if (empty($id)) {
            echo json_encode(array('status' => 'fail', 'message' => 'Invalid ID'));
            return;
        }

        $branchID = $this->frontend_model->getBranchID();
        $this->db->where('id', $id);
        if (!is_superadmin_loggedin() && !empty($branchID)) {
            $this->db->where('branch_id', $branchID);
        }
        $message = $this->db->get('front_cms_contact_messages')->row_array();

        if (empty($message)) {
            echo json_encode(array('status' => 'fail', 'message' => 'Message not found'));
            return;
        }

        // Mark as read automatically when viewed
        if ($message['status'] == 0) {
            $this->db->where('id', $id)->update('front_cms_contact_messages', array('status' => 1));
            $message['status'] = 1;
        }

        $message['formatted_date'] = date('d M Y, h:i A', strtotime($message['created_at']));
        $message['safe_name'] = html_escape($message['name']);
        $message['safe_email'] = html_escape($message['email']);
        $message['safe_phone'] = html_escape($message['phone']);
        $message['safe_subject'] = html_escape($message['subject']);
        $message['safe_message'] = nl2br(html_escape($message['message']));
        $message['safe_ip'] = html_escape($message['ip_address'] ?? 'N/A');

        echo json_encode(array('status' => 'success', 'data' => $message));
    }

    /**
     * Toggle read/unread status
     */
    public function mark_status($id = '', $status = 1)
    {
        $status = ($status == 1) ? 1 : 0;
        $branchID = $this->frontend_model->getBranchID();

        $this->db->where('id', $id);
        if (!is_superadmin_loggedin() && !empty($branchID)) {
            $this->db->where('branch_id', $branchID);
        }
        $this->db->update('front_cms_contact_messages', array('status' => $status));

        if ($this->input->is_ajax_request()) {
            echo json_encode(array('status' => 'success'));
        } else {
            set_alert('success', translate('information_has_been_updated_successfully'));
            redirect(base_url('frontend/contact_messages'));
        }
    }

    /**
     * Delete message
     */
    public function delete($id = '')
    {
        if (!get_permission('frontend_contact_message', 'is_delete') && 
            !get_permission('frontend_section', 'is_add') && 
            !is_superadmin_loggedin()) {
            access_denied();
        }

        $branchID = $this->frontend_model->getBranchID();
        $this->db->where('id', $id);
        if (!is_superadmin_loggedin() && !empty($branchID)) {
            $this->db->where('branch_id', $branchID);
        }
        $this->db->delete('front_cms_contact_messages');

        if ($this->input->is_ajax_request()) {
            echo json_encode(array('status' => 'success'));
        } else {
            set_alert('success', translate('information_deleted'));
            redirect(base_url('frontend/contact_messages'));
        }
    }
}

<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Custom_domain extends Admin_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('custom_domain_model');
    }

    /* Superadmin: View all custom domain requests */
    public function list()
    {
        if (!is_superadmin_loggedin()) {
            access_denied();
        }

        $this->data['domain_requests'] = $this->custom_domain_model->getAllRequests();
        $this->data['title'] = translate('custom_domain');
        $this->data['sub_page'] = 'custom_domain/list';
        $this->data['main_menu'] = 'custom_domain';
        $this->load->view('layout/index', $this->data);
    }

    /* Superadmin: Configure DNS instructions */
    public function dns_instruction()
    {
        if (!is_superadmin_loggedin()) {
            access_denied();
        }

        if ($this->input->post('submit') == 'save') {
            $postData = $this->input->post();
            unset($postData['submit']);
            $postData['status'] = isset($postData['status']) ? 1 : 0;
            $postData['dns_status'] = isset($postData['dns_status']) ? 1 : 0;

            $this->custom_domain_model->saveInstruction($postData);
            set_alert('success', translate('the_configuration_has_been_updated'));
            redirect(base_url('custom_domain/dns_instruction'));
        }

        $this->data['instruction'] = $this->custom_domain_model->getInstruction();
        $this->data['title'] = translate('dns_instruction');
        $this->data['sub_page'] = 'custom_domain/dns_instruction';
        $this->data['main_menu'] = 'custom_domain';
        $this->load->view('layout/index', $this->data);
    }

    /* School Admin: View and submit own custom domain request */
    public function mylist()
    {
        $school_id = get_loggedin_branch_id();
        if (empty($school_id) || is_superadmin_loggedin()) {
            redirect(base_url('custom_domain/list'));
        }

        if ($this->input->post('submit') == 'request') {
            $this->form_validation->set_rules('url', translate('domain_url') ?? 'Custom Domain URL', 'trim|required');
            if ($this->form_validation->run() == true) {
                $url = $this->input->post('url');
                $this->custom_domain_model->saveRequest($school_id, $url);
                set_alert('success', translate('domain_request_submitted_successfully') ?? 'Custom domain request submitted successfully! Our administrators will review and configure DNS routing.');
                redirect(base_url('custom_domain/mylist'));
            }
        }

        $this->data['my_request'] = $this->custom_domain_model->getMyRequest($school_id);
        $this->data['instruction'] = $this->custom_domain_model->getInstruction();
        $this->data['title'] = translate('custom_domain');
        $this->data['sub_page'] = 'custom_domain/mylist';
        $this->data['main_menu'] = 'domain_request';
        $this->load->view('layout/index', $this->data);
    }

    /* Superadmin: Update status of a domain request */
    public function status_update()
    {
        if (!is_superadmin_loggedin()) {
            access_denied();
        }

        if ($_POST) {
            $id = $this->input->post('request_id');
            $status = $this->input->post('status');
            $comments = $this->input->post('comments');
            $this->custom_domain_model->updateStatus($id, $status, $comments);
            set_alert('success', translate('information_has_been_updated_successfully'));
            redirect(base_url('custom_domain/list'));
        }
    }

    public function delete($id = '')
    {
        if (!is_superadmin_loggedin()) {
            access_denied();
        }

        $this->custom_domain_model->deleteRequest($id);
        set_alert('success', translate('information_deleted'));
        redirect(base_url('custom_domain/list'));
    }
}


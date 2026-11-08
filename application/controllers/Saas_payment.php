<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Saas_payment extends MY_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('saas_model');
    }

    public function checkout($gateway = '', $package_id = '')
    {
        $school_id = get_loggedin_branch_id();
        $package = $this->saas_model->getPackage($package_id);
        if (empty($package)) {
            redirect(base_url('subscription/index'));
        }

        $net_amount = max(0, (float)$package['price'] - (float)$package['discount']);

        if ($gateway == 'bkash') {
            $this->data['gateway_name'] = 'bKash';
            $this->data['package'] = $package;
            $this->data['amount'] = $net_amount;
            $this->data['school_id'] = $school_id;
            $this->load->view('saas/payment_gateway_mock', $this->data);
        } elseif ($gateway == 'nagad') {
            $this->data['gateway_name'] = 'Nagad';
            $this->data['package'] = $package;
            $this->data['amount'] = $net_amount;
            $this->data['school_id'] = $school_id;
            $this->load->view('saas/payment_gateway_mock', $this->data);
        } elseif ($gateway == 'paytm') {
            $this->data['gateway_name'] = 'Paytm';
            $this->data['package'] = $package;
            $this->data['amount'] = $net_amount;
            $this->data['school_id'] = $school_id;
            $this->load->view('saas/payment_gateway_mock', $this->data);
        } else {
            // Instant trial or direct activation
            $trx_id = 'DIRECT_' . strtoupper(uniqid());
            $this->saas_model->updateSchoolPlan($school_id, $package['id']);
            redirect(base_url('subscription/payment_success?package_id=' . $package['id'] . '&trx_id=' . $trx_id));
        }
    }

    /* Gateway return webhook/callback: Fixed - 404 Page Not Found */
    public function callback($gateway = '')
    {
        $package_id = $this->input->post('package_id') ? $this->input->post('package_id') : $this->input->get('package_id');
        $school_id = $this->input->post('school_id') ? $this->input->post('school_id') : get_loggedin_branch_id();
        $trx_id = $this->input->post('trx_id') ? $this->input->post('trx_id') : strtoupper($gateway) . '_' . strtoupper(uniqid());

        if (!empty($school_id) && !empty($package_id)) {
            $this->saas_model->updateSchoolPlan($school_id, $package_id);
        }

        redirect(base_url('subscription/payment_success?package_id=' . $package_id . '&trx_id=' . $trx_id));
    }

    public function success()
    {
        $package_id = $this->input->get('package_id');
        $trx_id = $this->input->get('trx_id');
        redirect(base_url('subscription/payment_success?package_id=' . $package_id . '&trx_id=' . $trx_id));
    }

    public function cancel()
    {
        redirect(base_url('subscription/payment_cancel'));
    }
}


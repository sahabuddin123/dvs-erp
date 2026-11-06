<?php
defined('BASEPATH') or exit('No direct script access allowed');

/**
 * Thermal Print Addon Controller
 * Renders small size, cost-effective POS thermal receipts for fees, payments, and vouchers
 */
class Thermal_print extends Admin_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('thermal_print_model');
        // Check if module is enabled
        if (function_exists('moduleIsEnabled') && !moduleIsEnabled('thermal_print')) {
            access_denied();
        }
    }

    public function index()
    {
        $this->settings();
    }

    /**
     * Settings & Live Preview
     */
    public function settings()
    {
        if (!is_superadmin_loggedin() && !is_admin_loggedin()) {
            access_denied();
        }

        $branch_id = $this->application_model->get_branch_id();

        if ($_POST) {
            $is_active = $this->input->post('is_active') ? 1 : 0;
            $printer_width = $this->input->post('printer_width') ?: '80mm';
            $show_logo = $this->input->post('show_logo') ? 1 : 0;
            $show_barcode = $this->input->post('show_barcode') ? 1 : 0;
            $show_qr = $this->input->post('show_qr') ? 1 : 0;
            $header_title = trim((string)$this->input->post('header_title'));
            $header_subtitle = trim((string)$this->input->post('header_subtitle'));
            $address = trim((string)$this->input->post('address'));
            $contact_info = trim((string)$this->input->post('contact_info'));
            $footer_text = trim((string)$this->input->post('footer_text'));
            $auto_print = $this->input->post('auto_print') ? 1 : 0;

            $data = [
                'branch_id'       => $branch_id,
                'is_active'       => $is_active,
                'printer_width'   => $printer_width,
                'show_logo'       => $show_logo,
                'show_barcode'    => $show_barcode,
                'show_qr'         => $show_qr,
                'header_title'    => $header_title,
                'header_subtitle' => $header_subtitle,
                'address'         => $address,
                'contact_info'    => $contact_info,
                'footer_text'     => $footer_text,
                'auto_print'      => $auto_print,
                'updated_at'      => date('Y-m-d H:i:s')
            ];

            $this->thermal_print_model->save_settings($data);
            set_alert('success', translate('information_has_been_updated_successfully'));
            redirect(base_url('thermal_print/settings'));
        }

        $this->data['settings'] = $this->thermal_print_model->get_settings($branch_id);
        $this->data['branch_id'] = $branch_id;
        $this->data['title'] = 'Thermal Print সেটিংস ও কনফিগারেশন';
        $this->data['sub_page'] = 'thermal_print/index';
        $this->data['main_menu'] = 'thermal_print';
        $this->load->view('layout/index', $this->data);
    }

    /**
     * AJAX endpoint to render thermal invoice
     */
    public function print_invoice()
    {
        $studentID = $this->input->post('student_id');
        $record = $this->input->post('data');
        $record_array = json_decode((string)$record);

        if (empty($studentID) || empty($record_array)) {
            echo '<div class="alert alert-danger">No invoice data provided.</div>';
            return;
        }

        $branch_id = $this->application_model->get_branch_id();
        $this->data['settings'] = $this->thermal_print_model->get_settings($branch_id);
        $this->data['thermal_data'] = $this->thermal_print_model->get_invoice_thermal_data($studentID, $record_array);
        $this->data['currency_symbol'] = $this->data['global_config']['currency_symbol'];
        $this->load->view('thermal_print/thermal_invoice', $this->data);
    }

    /**
     * AJAX endpoint to render thermal payment receipt
     */
    public function print_receipt()
    {
        $studentID = $this->input->post('student_id');
        $record = $this->input->post('data');
        $record_array = json_decode((string)$record);

        if (empty($studentID) || empty($record_array)) {
            echo '<div class="alert alert-danger">No payment receipt data provided.</div>';
            return;
        }

        $branch_id = $this->application_model->get_branch_id();
        $this->data['settings'] = $this->thermal_print_model->get_settings($branch_id);
        $this->data['thermal_data'] = $this->thermal_print_model->get_receipt_thermal_data($studentID, $record_array);
        $this->data['currency_symbol'] = $this->data['global_config']['currency_symbol'];
        $this->load->view('thermal_print/thermal_receipt', $this->data);
    }

    /**
     * AJAX endpoint to render thermal voucher receipt
     */
    public function print_voucher($voucher_id = '')
    {
        $voucher_id = (int)$voucher_id;
        $branch_id = $this->application_model->get_branch_id();
        $this->data['settings'] = $this->thermal_print_model->get_settings($branch_id);
        $this->data['currency_symbol'] = $this->data['global_config']['currency_symbol'];

        $voucher = $this->db->get_where('voucher', ['id' => $voucher_id])->row_array();
        if (empty($voucher)) {
            echo '<div class="alert alert-danger">Voucher not found.</div>';
            return;
        }

        $this->data['voucher'] = $voucher;
        $this->load->view('thermal_print/thermal_voucher', $this->data);
    }

    /**
     * Test Thermal Print
     */
    public function test_print()
    {
        $branch_id = $this->application_model->get_branch_id();
        $this->data['settings'] = $this->thermal_print_model->get_settings($branch_id);
        $this->data['currency_symbol'] = $this->data['global_config']['currency_symbol'];
        $this->load->view('thermal_print/test_receipt', $this->data);
    }
}

<?php defined('BASEPATH') or exit('No direct script access allowed');

class MY_Controller extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();

        if ($this->config->item('installed') == false) {
            redirect(site_url('install'));
        }
        $get_config = $this->db->get_where('global_settings', array('id' => 1))->row_array();
        
        // cache control
        $this->output->set_header('Last-Modified: ' . gmdate("D, d M Y H:i:s") . ' GMT');
        if (isset($get_config['cache_store']) && $get_config['cache_store'] == 1) {
            $this->output->set_header('Cache-Control: no-cache, must-revalidate, post-check=0, pre-check=0');
        } else {
            $this->output->set_header('Cache-Control: no-store, no-cache, must-revalidate, post-check=0, pre-check=0');
        }
        $this->output->set_header('Pragma: no-cache');
        $this->output->set_header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");

        // checkout branch information
        $branchID = $this->application_model->get_branch_id();
        if (!empty($branchID)) {
            $branch = $this->db->select('currency_formats,symbol_position,symbol,currency,timezone')->where('id', $branchID)->get('branch')->row();
            if (!empty($branch)) {
                $get_config['currency'] = $branch->currency;
                $get_config['currency_symbol'] = $branch->symbol;
                $get_config['currency_formats'] = $branch->currency_formats;
                $get_config['symbol_position'] = $branch->symbol_position;
                if (!empty($branch->timezone)) {
                    $get_config['timezone'] = $branch->timezone;
                }
            }
        }
        $this->data['global_config'] = $get_config;
        $this->data['theme_config'] = $this->db->get_where('theme_settings', array('id' => 1))->row_array();
        date_default_timezone_set(!empty($get_config['timezone']) ? $get_config['timezone'] : 'Asia/Dhaka');
    }

    public function get_payment_config()
    {
        $branchID = $this->application_model->get_branch_id();
        $this->db->where('branch_id', $branchID);
        $this->db->select('*')->from('payment_config');
        $config = $this->db->get()->row_array();
        if (empty($config)) {
            $config = [];
        }

        // Environment overrides for bKash
        if (function_exists('env')) {
            if (empty($config['bkash_app_key']) && env('BKASH_APP_KEY')) {
                $config['bkash_app_key'] = env('BKASH_APP_KEY');
            }
            if (empty($config['bkash_app_secret']) && env('BKASH_APP_SECRET')) {
                $config['bkash_app_secret'] = env('BKASH_APP_SECRET');
            }
            if (empty($config['bkash_username']) && env('BKASH_USERNAME')) {
                $config['bkash_username'] = env('BKASH_USERNAME');
            }
            if (empty($config['bkash_password']) && env('BKASH_PASSWORD')) {
                $config['bkash_password'] = env('BKASH_PASSWORD');
            }
            if (!isset($config['bkash_sandbox']) && env('BKASH_SANDBOX') !== null) {
                $config['bkash_sandbox'] = (int)env('BKASH_SANDBOX', 1);
            }
            if (empty($config['bkash_status']) && env('BKASH_STATUS') !== null) {
                $config['bkash_status'] = (int)env('BKASH_STATUS', 1);
            }

            // Environment overrides for Nagad
            if (empty($config['nagad_merchant_id']) && env('NAGAD_MERCHANT_ID')) {
                $config['nagad_merchant_id'] = env('NAGAD_MERCHANT_ID');
            }
            if (empty($config['nagad_merchant_number']) && env('NAGAD_MERCHANT_NUMBER')) {
                $config['nagad_merchant_number'] = env('NAGAD_MERCHANT_NUMBER');
            }
            if (empty($config['nagad_public_key']) && env('NAGAD_PUBLIC_KEY')) {
                $config['nagad_public_key'] = env('NAGAD_PUBLIC_KEY');
            }
            if (empty($config['nagad_private_key']) && env('NAGAD_PRIVATE_KEY')) {
                $config['nagad_private_key'] = env('NAGAD_PRIVATE_KEY');
            }
            if (!isset($config['nagad_sandbox']) && env('NAGAD_SANDBOX') !== null) {
                $config['nagad_sandbox'] = (int)env('NAGAD_SANDBOX', 1);
            }
            if (empty($config['nagad_status']) && env('NAGAD_STATUS') !== null) {
                $config['nagad_status'] = (int)env('NAGAD_STATUS', 1);
            }
        }

        return $config;
    }

    public function getBranchDetails()
    {
        $branchID = $this->application_model->get_branch_id();
        $this->db->select('*');
        $this->db->where('id', $branchID);
        $this->db->from('branch');
        $r = $this->db->get()->row_array();
        if (empty($r)) {
            return ['stu_generate' => "", 'grd_generate' => ""];
        } else {
            return $r;
        }
    }

    public function photoHandleUpload($str, $fields)
    {
        $allowedExts = array_map('trim', array_map('strtolower', explode(',', $this->data['global_config']['image_extension'])));
        $allowedSizeKB = $this->data['global_config']['image_size'];
        $allowedSize = floatval(1024 * $allowedSizeKB);
        if (isset($_FILES["$fields"]) && !empty($_FILES["$fields"]['name'])) {
            $file_size = $_FILES["$fields"]["size"];
            $file_name = $_FILES["$fields"]["name"];
            $extension = pathinfo($file_name, PATHINFO_EXTENSION);
            if ($files = filesize($_FILES["$fields"]['tmp_name'])) {
                if (!in_array(strtolower($extension), $allowedExts)) {
                    $this->form_validation->set_message('photoHandleUpload', translate('this_file_type_is_not_allowed'));
                    return false;
                }
                if ($file_size > $allowedSize) {
                    $this->form_validation->set_message('photoHandleUpload', translate('file_size_shoud_be_less_than') . " $allowedSizeKB KB.");
                    return false;
                }
            } else {
                $this->form_validation->set_message('photoHandleUpload', translate('error_reading_the_file'));
                return false;
            }
            return true;
        }
    }

    public function fileHandleUpload($str, $fields)
    {
        $allowedExts = array_map('trim', array_map('strtolower', explode(',', $this->data['global_config']['file_extension'])));
        $allowedSizeKB = $this->data['global_config']['file_size'];
        $allowedSize = floatval(1024 * $allowedSizeKB);
        if (isset($_FILES["$fields"]) && !empty($_FILES["$fields"]['name'])) {
            $file_size = $_FILES["$fields"]["size"];
            $file_name = $_FILES["$fields"]["name"];
            $extension = pathinfo($file_name, PATHINFO_EXTENSION);
            if ($files = filesize($_FILES["$fields"]['tmp_name'])) {
                if (!in_array(strtolower($extension), $allowedExts)) {
                    $this->form_validation->set_message('fileHandleUpload', translate('this_file_type_is_not_allowed'));
                    return false;
                }
                if ($file_size > $allowedSize) {
                    $this->form_validation->set_message('fileHandleUpload', translate('file_size_shoud_be_less_than') . " $allowedSizeKB KB.");
                    return false;
                }
            } else {
                $this->form_validation->set_message('fileHandleUpload', translate('error_reading_the_file'));
                return false;
            }
            return true;
        }
    }
}

class Admin_Controller extends MY_Controller
{
    public function __construct()
    {
        parent::__construct();
        if (!is_loggedin()) {
            $this->session->set_userdata('redirect_url', current_url());
            redirect(base_url('authentication'), 'refresh');
        }
        if (is_single_branch()) {
            if (!empty($_POST) && empty($_POST['branch_id'])) {
                $_POST['branch_id'] = (string)get_single_branch_id();
            }
            if (empty($this->data['branch_id'])) {
                $this->data['branch_id'] = get_single_branch_id();
            }
        }
    }
}

class User_Controller extends MY_Controller
{
    public function __construct()
    {
        parent::__construct();
        if (!is_student_loggedin() && !is_parent_loggedin()) {
            $this->session->set_userdata('redirect_url', current_url());
            redirect(base_url('authentication'), 'refresh');
        }
    }
}

class Authentication_Controller extends MY_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('authentication_model');
    }
}

class Frontend_Controller extends MY_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('home_model');
        $branchID = $this->home_model->getDefaultBranch();
        $cms_setting = $this->db->get_where('front_cms_setting', array('branch_id' => $branchID))->row_array();
        if (empty($cms_setting['cms_active'])) {
            redirect(site_url('authentication'));
        }
        $this->data['cms_setting'] = $cms_setting;
    }
}

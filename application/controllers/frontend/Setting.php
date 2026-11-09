<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Setting extends Admin_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->model('frontend_model');
    }

    public function index()
    {
        // check access permission
        if (!get_permission('frontend_setting', 'is_view')) {
            access_denied();
        }
        $this->ensure_schema();
        $branchID = $this->frontend_model->getBranchID();
        if ($_POST) {
            $branch_id = $this->input->post('branch_id');
            redirect(base_url('frontend/setting?branch_id=' . $branch_id));
        }
        $this->data['headerelements'] = array(
            'css' => array(
                'vendor/dropify/css/dropify.min.css',
                'vendor/jquery-asColorPicker-master/css/asColorPicker.css',
            ),
            'js' => array(
                'vendor/dropify/js/dropify.min.js',
                'vendor/jquery-asColorPicker-master/libs/jquery-asColor.js',
                'vendor/jquery-asColorPicker-master/libs/jquery-asGradient.js',
                'vendor/jquery-asColorPicker-master/dist/jquery-asColorPicker.min.js',
            ),
        );
        $this->data['branch_id'] = $branchID;
        $this->data['setting'] = $this->frontend_model->get('front_cms_setting', array('branch_id' => $branchID), true);
        $this->data['title'] = translate('frontend');
        $this->data['sub_page'] = 'frontend/setting';
        $this->data['main_menu'] = 'frontend';
        $this->load->view('layout/index', $this->data);
    }

    /**
     * Ensure database schema has all columns and compatible data types
     */
    private function ensure_schema()
    {
        try {
            $existing_fields = $this->db->list_fields('front_cms_setting');
            $required_columns = array(
                'application_title_en' => 'VARCHAR(255) NULL DEFAULT NULL',
                'site_tagline'         => 'VARCHAR(255) NULL DEFAULT NULL',
                'site_tagline_en'      => 'VARCHAR(255) NULL DEFAULT NULL',
                'address_en'           => 'TEXT NULL DEFAULT NULL',
                'footer_about_text_en' => 'TEXT NULL DEFAULT NULL',
                'copyright_text_en'    => 'TEXT NULL DEFAULT NULL',
                'header_quick_buttons' => 'TEXT NULL DEFAULT NULL',
            );
            $old_debug = $this->db->db_debug;
            $this->db->db_debug = FALSE;
            foreach ($required_columns as $col => $def) {
                if (!in_array($col, $existing_fields)) {
                    @$this->db->query("ALTER TABLE `front_cms_setting` ADD `$col` $def");
                }
            }
            // Expand restrictive varchar lengths to TEXT so long Bengali text / HTML never causes 500 error
            @$this->db->query("ALTER TABLE `front_cms_setting` MODIFY `footer_about_text` TEXT NULL");
            @$this->db->query("ALTER TABLE `front_cms_setting` MODIFY `copyright_text` TEXT NULL");
            @$this->db->query("ALTER TABLE `front_cms_setting` MODIFY `address` TEXT NULL");
            $this->db->db_debug = $old_debug;
        } catch (Throwable $e) {
            log_message('error', 'front_cms_setting schema check error: ' . $e->getMessage());
        }
    }

    public function save()
    {
        if (!get_permission('frontend_setting', 'is_add')) {
            ajax_access_denied();
        }
        if ($_POST) {
            $this->ensure_schema();
            $branchID = $this->frontend_model->getBranchID();
            if (empty($branchID)) {
                $branchID = 1;
            }
            $this->form_validation->set_rules('application_title', 'Cms Title', 'trim|required');
            $this->form_validation->set_rules('url_alias', 'Cms Url Alias', 'trim|required|callback_unique_url');
            $this->form_validation->set_rules('receive_email_to', 'Receive Email To', 'trim|required|valid_email');
            $this->form_validation->set_rules('working_hours', 'Working Hours', 'trim|required');
            $this->form_validation->set_rules('address', 'Address', 'trim|required');
            $this->form_validation->set_rules('mobile_no', 'Mobile No', 'trim|required');
            $this->form_validation->set_rules('email', 'Email', 'trim|required|valid_email');
            $this->form_validation->set_rules('fax', 'Fax', 'trim|required');
            $this->form_validation->set_rules('footer_about_text', 'Footer About Text', 'trim|required');
            $this->form_validation->set_rules('copyright_text', 'Copyright Text', 'trim|required');
            // theme options
            $this->form_validation->set_rules('primary_color', 'Primary Color', 'trim|required');
            $this->form_validation->set_rules('menu_color', 'Menu Color', 'trim|required');
            $this->form_validation->set_rules('btn_hover', 'Button Hover Color', 'trim|required');
            $this->form_validation->set_rules('text_color', 'Text Color', 'trim|required');
            $this->form_validation->set_rules('secondary_text_color', 'Text Secondary Color', 'trim|required');
            $this->form_validation->set_rules('footer_bg_color', 'Footer Background Color ', 'trim|required');
            $this->form_validation->set_rules('footer_text_color', 'Footer Text Color', 'trim|required');
            $this->form_validation->set_rules('copyright_bg_color', 'Copyright BG Color', 'trim|required');
            $this->form_validation->set_rules('copyright_text_color', 'Copyright Text Color', 'trim|required');
            $this->form_validation->set_rules('border_radius', 'Border Radius', 'trim|required');
            if ($this->form_validation->run() == true) {
                $cms_setting = array(
                    'branch_id' => $branchID,
                    'application_title' => $this->input->post('application_title'),
                    'application_title_en' => $this->input->post('application_title_en'),
                    'site_tagline' => $this->input->post('site_tagline'),
                    'site_tagline_en' => $this->input->post('site_tagline_en'),
                    'url_alias' =>  strtolower(preg_replace('/[^A-Za-z0-9]/', '_', $this->input->post('url_alias'))),
                    'cms_active' => $this->input->post('cms_frontend_status'),
                    'primary_color' => $this->input->post('primary_color'),
                    'menu_color' => $this->input->post('menu_color'),
                    'hover_color' => $this->input->post('btn_hover'),
                    'text_color' => $this->input->post('text_color'),
                    'text_secondary_color' => $this->input->post('secondary_text_color'),
                    'footer_background_color' => $this->input->post('footer_bg_color'),
                    'footer_text_color' => $this->input->post('footer_text_color'),
                    'copyright_bg_color' => $this->input->post('copyright_bg_color'),
                    'copyright_text_color' => $this->input->post('copyright_text_color'),
                    'border_radius' => $this->input->post('border_radius'),

                    'online_admission' => $this->input->post('online_admission'),
                    'captcha_status' => $this->input->post('captcha_status'),
                    'recaptcha_site_key' => $this->input->post('recaptcha_site_key'),
                    'recaptcha_secret_key' => $this->input->post('recaptcha_secret_key'),
                    'address' => $this->input->post('address'),
                    'address_en' => $this->input->post('address_en'),
                    'mobile_no' => $this->input->post('mobile_no'),
                    'fax' => $this->input->post('fax'),
                    'receive_contact_email' => $this->input->post('receive_email_to'),
                    'email' => $this->input->post('email'),
                    'footer_about_text' => $this->input->post('footer_about_text'),
                    'footer_about_text_en' => $this->input->post('footer_about_text_en'),
                    'copyright_text' => $this->input->post('copyright_text', false),
                    'copyright_text_en' => $this->input->post('copyright_text_en', false),
                    'working_hours' => $this->input->post('working_hours'),
                    'google_analytics' => $this->input->post('google_analytics', false),
                    'facebook_url' => $this->input->post('facebook_url'),
                    'twitter_url' => $this->input->post('twitter_url'),
                    'youtube_url' => $this->input->post('youtube_url'),
                    'google_plus' => $this->input->post('google_plus'),
                    'linkedin_url' => $this->input->post('linkedin_url'),
                    'pinterest_url' => $this->input->post('pinterest_url'),
                    'instagram_url' => $this->input->post('instagram_url'),
                );

                // Process Header Quick Buttons JSON
                $raw_buttons = $this->input->post('quick_buttons');
                $cleaned_buttons = array();
                if (is_array($raw_buttons)) {
                    foreach ($raw_buttons as $btn) {
                        $text = trim($btn['text'] ?? '');
                        $text_en = trim($btn['text_en'] ?? '');
                        $url = trim($btn['url'] ?? '');
                        if (!empty($text) || !empty($text_en) || !empty($url)) {
                            $cleaned_buttons[] = array(
                                'text' => $text,
                                'text_en' => $text_en,
                                'url' => $url,
                                'icon' => trim(!empty($btn['icon']) ? $btn['icon'] : 'fas fa-link'),
                                'target' => (!empty($btn['target']) && $btn['target'] == '_blank') ? '_blank' : '_self',
                                'status' => isset($btn['status']) ? intval($btn['status']) : 1,
                            );
                        }
                    }
                }
                $cms_setting['header_quick_buttons'] = json_encode($cleaned_buttons, JSON_UNESCAPED_UNICODE);

                $upload_dir = './uploads/frontend/images/';
                if (!is_dir($upload_dir)) {
                    @mkdir($upload_dir, 0777, true);
                }

                // upload logo
                if (isset($_FILES["logo"]) && !empty($_FILES["logo"]['name'])) {
                    $imageNmae = $_FILES['logo']['name'];
                    $extension = pathinfo($imageNmae, PATHINFO_EXTENSION);
                    $newLogoName = "logo$branchID." . $extension;
                    $image_path = $upload_dir . $newLogoName;
                    if (move_uploaded_file($_FILES['logo']['tmp_name'], $image_path)) {
                        $cms_setting['logo'] = $newLogoName;
                    }
                }

                // upload fav icon
                if (isset($_FILES["fav_icon"]) && !empty($_FILES["fav_icon"]['name'])) {
                    $imageNmae = $_FILES['fav_icon']['name'];
                    $extension = pathinfo($imageNmae, PATHINFO_EXTENSION);
                    $newLogoName = "fav_icon$branchID." . $extension;
                    $image_path = $upload_dir . $newLogoName;
                    if (move_uploaded_file($_FILES['fav_icon']['tmp_name'], $image_path)) {
                        $cms_setting['fav_icon'] = $newLogoName;
                    }
                }

                // upload page background
                if (isset($_FILES["page_background"]) && !empty($_FILES["page_background"]['name'])) {
                    $imageNmae = $_FILES['page_background']['name'];
                    $extension = pathinfo($imageNmae, PATHINFO_EXTENSION);
                    $newBgName = "page_bg$branchID." . $extension;
                    $image_path = $upload_dir . $newBgName;
                    if (move_uploaded_file($_FILES['page_background']['tmp_name'], $image_path)) {
                        $cms_setting['page_background'] = $newBgName;
                    }
                }

                // Safe filtering of columns to prevent MySQL unknown column errors
                $table_fields = $this->db->list_fields('front_cms_setting');
                $filtered_setting = array();
                foreach ($cms_setting as $col => $val) {
                    if (in_array($col, $table_fields)) {
                        $filtered_setting[$col] = $val;
                    }
                }

                try {
                    // update all information in the database
                    $this->db->where(array('branch_id' => $branchID));
                    $get = $this->db->get('front_cms_setting');
                    if ($get->num_rows() > 0) {
                        $this->db->where('id', $get->row()->id);
                        $this->db->update('front_cms_setting', $filtered_setting);
                    } else {
                        // Ensure non-null fields have default values on insert
                        if (!isset($filtered_setting['theme']) || empty($filtered_setting['theme'])) {
                            $filtered_setting['theme'] = 'red';
                        }
                        if (!isset($filtered_setting['fav_icon']) || empty($filtered_setting['fav_icon'])) {
                            $filtered_setting['fav_icon'] = 'fav_icon1.png';
                        }
                        if (!isset($filtered_setting['logo']) || empty($filtered_setting['logo'])) {
                            $filtered_setting['logo'] = 'logo1.png';
                        }
                        $filtered_setting['branch_id'] = $branchID;
                        $this->db->insert('front_cms_setting', $filtered_setting);
                    }

                    set_alert('success', translate('information_has_been_saved_successfully'));
                    $array = array('status' => 'success');
                } catch (Throwable $e) {
                    log_message('error', 'Frontend Setting Save Error: ' . $e->getMessage());
                    $array = array('status' => 'fail', 'error' => array('database_error' => $e->getMessage()));
                }
            } else {
                $error = $this->form_validation->error_array();
                $array = array('status' => 'fail', 'error' => $error);
            }
            echo json_encode($array);
        }
    }


    public function unique_url($alias)
    {
        $branchID = $this->frontend_model->getBranchID();
        if (empty($branchID)) {
            $branchID = 1;
        }
        $this->db->where_not_in('branch_id', (array)$branchID);
        $this->db->where('url_alias', $alias);
        $query = $this->db->get('front_cms_setting');
        if ($query->num_rows() > 0) {
            $this->form_validation->set_message("unique_url", translate('already_taken'));
            return false;
        } else {
            return true;
        }
    }
}

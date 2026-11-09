<?php
defined('BASEPATH') or exit('No direct script access allowed');

/**
 * @package : Ramom school management system
 * @version : 7.0
 * @developed by : RamomCoder
 * @support : ramomcoder@yahoo.com
 * @author url : http://codecanyon.net/user/RamomCoder
 * @filename : Section.php
 * @copyright : Reserved RamomCoder Team
 */

class Section extends Admin_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('frontend_model');
        $this->load->model('student_fields_model');
        $this->load->model('home_model');
        $this->data['headerelements'] = array(
            'css' => array(
                'vendor/summernote/summernote.css',
                'vendor/dropify/css/dropify.min.css',
                'vendor/jquery-asColorPicker-master/css/asColorPicker.css',
            ),
            'js' => array(
                'vendor/summernote/summernote.js',
                'vendor/dropify/js/dropify.min.js',
                'vendor/jquery-asColorPicker-master/libs/jquery-asColor.js',
                'vendor/jquery-asColorPicker-master/libs/jquery-asGradient.js',
                'vendor/jquery-asColorPicker-master/dist/jquery-asColorPicker.min.js',
            ),
        );
        if (!get_permission('frontend_section', 'is_view')) {
            access_denied();
        }
    }

    public function index()
    {
        $this->home();
    }

    // home features
    public function home()
    {
        $branchID                   = $this->frontend_model->getBranchID();
        if (empty($branchID)) {
            $branchID = 1;
        }
        $this->data['branch_id']    = $branchID;
        $this->data['wellcome']     = $this->frontend_model->get('front_cms_home', array('item_type' => 'wellcome', 'branch_id' => $branchID), true);
        $this->data['home_seo']     = $this->frontend_model->get('front_cms_home_seo', array('branch_id' => $branchID), true);
        $this->data['teachers']     = $this->frontend_model->get('front_cms_home', array('item_type' => 'teachers', 'branch_id' => $branchID), true);
        $this->data['testimonial']  = $this->frontend_model->get('front_cms_home', array('item_type' => 'testimonial', 'branch_id' => $branchID), true);
        $this->data['services']     = $this->frontend_model->get('front_cms_home', array('item_type' => 'services', 'branch_id' => $branchID), true);
        $this->data['statistics']   = $this->frontend_model->get('front_cms_home', array('item_type' => 'statistics', 'branch_id' => $branchID), true);
        $this->data['cta']          = $this->frontend_model->get('front_cms_home', array('item_type' => 'cta', 'branch_id' => $branchID), true);
        $this->data['sliderlist']   = $this->frontend_model->get('front_cms_home', array('item_type' => 'slider', 'branch_id' => $branchID));
        $this->data['hero_sec']     = $this->frontend_model->get('front_cms_home', array('item_type' => 'hero', 'branch_id' => $branchID), true);
        $this->data['notice_sec']   = $this->frontend_model->get('front_cms_home', array('item_type' => 'notice', 'branch_id' => $branchID), true);
        $this->data['leadership_sec']= $this->frontend_model->get('front_cms_home', array('item_type' => 'leadership', 'branch_id' => $branchID), true);
        $this->data['events_sec']   = $this->frontend_model->get('front_cms_home', array('item_type' => 'events', 'branch_id' => $branchID), true);
        $this->data['video_sec']    = $this->frontend_model->get('front_cms_home', array('item_type' => 'video_gallery', 'branch_id' => $branchID), true);
        $this->data['gallery_sec']  = $this->frontend_model->get('front_cms_home', array('item_type' => 'gallery', 'branch_id' => $branchID), true);

        // Previews for section tabs
        $this->data['notices_list']  = $this->db->where('branch_id', $branchID)->order_by('id', 'desc')->limit(5)->get('front_cms_news_list')->result_array();
        $this->data['leaders_list']  = $this->db->where('branch_id', $branchID)->order_by('position', 'asc')->get('founder')->result_array();
        $this->data['teachers_list'] = $this->db->where('branch_id', $branchID)->order_by('position', 'asc')->get('teacher')->result_array();
        $this->data['events_list']   = $this->db->where('branch_id', $branchID)->order_by('id', 'desc')->limit(5)->get('event')->result_array();
        $this->data['videos_list']   = $this->db->where('branch_id', $branchID)->where('file_type', 'video')->order_by('id', 'desc')->limit(6)->get('front_cms_gallery_content')->result_array();
        $this->data['photos_list']   = $this->db->where('branch_id', $branchID)->where('file_type', 'image')->order_by('id', 'desc')->limit(6)->get('front_cms_gallery_content')->result_array();

        $this->data['title']        = translate('website_page');
        $this->data['sub_page']     = 'frontend/section_home';
        $this->data['main_menu']    = 'frontend';
        $this->load->view('layout/index', $this->data);
    }

    public function home_slider_save()
    {
        if ($_POST) {
            if (!get_permission('frontend_section', 'is_add') && !get_permission('frontend_slider', 'is_add')) {
                ajax_access_denied();
            }
            $this->form_validation->set_rules('title', translate('title'), 'trim|required');
            if (empty($_FILES['photo']['name']) && empty($this->input->post('old_photo'))) {
                $this->form_validation->set_rules('photo', translate('photo'), 'required');
            }
            if ($this->form_validation->run() !== false) {
                $branchID = $this->frontend_model->getBranchID();
                $post = $this->input->post();
                $post['branch_id'] = $branchID;
                if (!isset($post['button_text_1'])) $post['button_text_1'] = '';
                if (!isset($post['button_url_1'])) $post['button_url_1'] = '';
                if (!isset($post['button_text_2'])) $post['button_text_2'] = '';
                if (!isset($post['button_url_2'])) $post['button_url_2'] = '';
                if (!isset($post['description'])) $post['description'] = '';
                if (!isset($post['position'])) $post['position'] = 'c-center';
                $this->frontend_model->save_slider($post);
                set_alert('success', translate('information_has_been_saved_successfully'));
                $url = base_url('frontend/section/home' . (is_superadmin_loggedin() ? '?branch_id=' . $branchID : '') . '#slider');
                $array = array('status' => 'success', 'url' => $url);
            } else {
                $error = $this->form_validation->error_array();
                $array = array('status' => 'fail', 'error' => $error);
            }
            echo json_encode($array);
            exit();
        }
    }

    public function home_slider_delete($id = '')
    {
        if (!get_permission('frontend_section', 'is_delete') && !get_permission('frontend_slider', 'is_delete')) {
            access_denied();
        }
        $row = $this->db->get_where('front_cms_home', array('id' => $id, 'item_type' => 'slider'))->row_array();
        if (!empty($row)) {
            $elements = json_decode($row['elements'], true);
            $image = !empty($elements['image']) ? $elements['image'] : '';
            if ($this->db->where(array('id' => $id, 'item_type' => 'slider'))->delete('front_cms_home')) {
                $destination = './uploads/frontend/slider/';
                if (!empty($image) && file_exists($destination . $image)) {
                    @unlink($destination . $image);
                }
            }
        }
        set_alert('success', translate('information_deleted'));
        redirect($_SERVER['HTTP_REFERER']);
    }

    public function home_wellcome()
    {
        if ($_POST) {
            // check access permission
            if (!get_permission('frontend_section', 'is_add')) {
                ajax_access_denied();
            }
            $branchID = $this->frontend_model->getBranchID();
            if (empty($branchID)) {
                $branchID = $this->input->post('branch_id');
                if (empty($branchID)) {
                    $branchID = 1;
                }
            }
            $this->form_validation->set_rules('wel_title', 'Title', 'trim|required');
            $this->form_validation->set_rules('subtitle', 'Subtitle', 'trim');
            $this->form_validation->set_rules('description', 'Description', 'trim|required');
            if (!empty($_FILES["photo"]['name'])) {
                $this->form_validation->set_rules('photo', translate('photo'), 'callback_photoHandleUpload[photo]');
            }
            if ($this->form_validation->run() == true) {
                $remove_photo = ($this->input->post('remove_photo') == '1') || (empty($this->input->post('old_photo')) && empty($_FILES['photo']['name']));
                $img_name = '';
                $destination = './uploads/frontend/home_page/';
                $prev_image = $this->input->post('old_photo');

                if ($remove_photo) {
                    if (!empty($prev_image) && file_exists($destination . $prev_image)) {
                        @unlink($destination . $prev_image);
                    }
                    foreach (['jpg', 'png', 'jpeg', 'webp'] as $test_ext) {
                        if (file_exists($destination . 'wellcome' . $branchID . '.' . $test_ext)) {
                            @unlink($destination . 'wellcome' . $branchID . '.' . $test_ext);
                        }
                    }
                    $img_name = '';
                } else {
                    $img_name = $this->uploadImage('wellcome' . $branchID, 'home_page');
                }

                // save information in the database
                $arrayWellcome = array(
                    'branch_id' => $branchID,
                    'title' => $this->input->post('wel_title'),
                    'title_en' => $this->input->post('wel_title_en'),
                    'subtitle' => $this->input->post('subtitle'),
                    'subtitle_en' => $this->input->post('subtitle_en'),
                    'active' => (isset($_POST['isvisible']) ? 1 : 0),
                    'description' => $this->input->post('description'),
                    'description_en' => $this->input->post('description_en'),
                    'color1' => $this->input->post('title_text_color'),
                    'elements' => json_encode(array('image' => $img_name)),
                );
                // save information in the database
                $this->saveHome('wellcome', $branchID, $arrayWellcome);
                set_alert('success', translate('information_has_been_saved_successfully'));
                $url = base_url('frontend/section/home' . (is_superadmin_loggedin() ? '?branch_id=' . $branchID : '') . '#welcome');
                $array = array('status' => 'success', 'url' => $url);
            } else {
                $error = $this->form_validation->error_array();
                $array = array('status' => 'fail', 'error' => $error);
            }
            echo json_encode($array);
        }
    }

    public function home_teachers()
    {
        if ($_POST) {
            // check access permission
            if (!get_permission('frontend_section', 'is_add')) {
                ajax_access_denied();
            }

            $branchID = $this->frontend_model->getBranchID();
            if (empty($branchID)) {
                $branchID = $this->input->post('branch_id') ?: 1;
            }
            $this->form_validation->set_rules('tea_title', 'Title', 'trim|required');
            if ($this->form_validation->run() == true) {
                // save information in the database
                $arrayTeacher = array(
                    'branch_id' => $branchID,
                    'title' => $this->input->post('tea_title'),
                    'title_en' => $this->input->post('tea_title_en'),
                    'subtitle' => $this->input->post('subtitle'),
                    'subtitle_en' => $this->input->post('subtitle_en'),
                    'active' => (isset($_POST['isvisible']) ? 1 : 0),
                );

                // save information in the database
                $this->saveHome('teachers', $branchID, $arrayTeacher);
                set_alert('success', translate('information_has_been_saved_successfully'));
                $url = base_url('frontend/section/home' . (is_superadmin_loggedin() ? '?branch_id=' . $branchID : '') . '#teachers');
                echo json_encode(array('status' => 'success', 'url' => $url));
            } else {
                $error = $this->form_validation->error_array();
                echo json_encode(array('status' => 'fail', 'error' => $error));
            }
        }
    }

    public function home_notice()
    {
        if ($_POST) {
            if (!get_permission('frontend_section', 'is_add')) {
                ajax_access_denied();
            }
            $branchID = $this->frontend_model->getBranchID();
            if (empty($branchID)) {
                $branchID = $this->input->post('branch_id') ?: 1;
            }
            $this->form_validation->set_rules('notice_title', 'Title', 'trim|required');
            if ($this->form_validation->run() == true) {
                $arrayNotice = array(
                    'branch_id' => $branchID,
                    'title' => $this->input->post('notice_title'),
                    'title_en' => $this->input->post('notice_title_en'),
                    'subtitle' => $this->input->post('subtitle'),
                    'subtitle_en' => $this->input->post('subtitle_en'),
                    'active' => (isset($_POST['isvisible']) ? 1 : 0),
                    'elements' => json_encode(array('limit' => intval($this->input->post('notice_limit') ?: 5))),
                );
                $this->saveHome('notice', $branchID, $arrayNotice);
                set_alert('success', translate('information_has_been_saved_successfully'));
                $url = base_url('frontend/section/home' . (is_superadmin_loggedin() ? '?branch_id=' . $branchID : '') . '#notice');
                echo json_encode(array('status' => 'success', 'url' => $url));
            } else {
                echo json_encode(array('status' => 'fail', 'error' => $this->form_validation->error_array()));
            }
        }
    }

    public function home_leadership()
    {
        if ($_POST) {
            if (!get_permission('frontend_section', 'is_add')) {
                ajax_access_denied();
            }
            $branchID = $this->frontend_model->getBranchID();
            if (empty($branchID)) {
                $branchID = $this->input->post('branch_id') ?: 1;
            }
            $this->form_validation->set_rules('lead_title', 'Title', 'trim|required');
            if ($this->form_validation->run() == true) {
                $arrayLeadership = array(
                    'branch_id' => $branchID,
                    'title' => $this->input->post('lead_title'),
                    'title_en' => $this->input->post('lead_title_en'),
                    'subtitle' => $this->input->post('subtitle'),
                    'subtitle_en' => $this->input->post('subtitle_en'),
                    'active' => (isset($_POST['isvisible']) ? 1 : 0),
                );
                $this->saveHome('leadership', $branchID, $arrayLeadership);
                set_alert('success', translate('information_has_been_saved_successfully'));
                $url = base_url('frontend/section/home' . (is_superadmin_loggedin() ? '?branch_id=' . $branchID : '') . '#leadership');
                echo json_encode(array('status' => 'success', 'url' => $url));
            } else {
                echo json_encode(array('status' => 'fail', 'error' => $this->form_validation->error_array()));
            }
        }
    }

    public function home_events()
    {
        if ($_POST) {
            if (!get_permission('frontend_section', 'is_add')) {
                ajax_access_denied();
            }
            $branchID = $this->frontend_model->getBranchID();
            if (empty($branchID)) {
                $branchID = $this->input->post('branch_id') ?: 1;
            }
            $this->form_validation->set_rules('events_title', 'Title', 'trim|required');
            if ($this->form_validation->run() == true) {
                $arrayEvents = array(
                    'branch_id' => $branchID,
                    'title' => $this->input->post('events_title'),
                    'title_en' => $this->input->post('events_title_en'),
                    'subtitle' => $this->input->post('subtitle'),
                    'subtitle_en' => $this->input->post('subtitle_en'),
                    'active' => (isset($_POST['isvisible']) ? 1 : 0),
                );
                $this->saveHome('events', $branchID, $arrayEvents);
                set_alert('success', translate('information_has_been_saved_successfully'));
                $url = base_url('frontend/section/home' . (is_superadmin_loggedin() ? '?branch_id=' . $branchID : '') . '#events');
                echo json_encode(array('status' => 'success', 'url' => $url));
            } else {
                echo json_encode(array('status' => 'fail', 'error' => $this->form_validation->error_array()));
            }
        }
    }

    public function home_video_gallery()
    {
        if ($_POST) {
            if (!get_permission('frontend_section', 'is_add')) {
                ajax_access_denied();
            }
            $branchID = $this->frontend_model->getBranchID();
            if (empty($branchID)) {
                $branchID = $this->input->post('branch_id') ?: 1;
            }
            $this->form_validation->set_rules('vid_title', 'Title', 'trim|required');
            if ($this->form_validation->run() == true) {
                $arrayVideo = array(
                    'branch_id' => $branchID,
                    'title' => $this->input->post('vid_title'),
                    'title_en' => $this->input->post('vid_title_en'),
                    'subtitle' => $this->input->post('subtitle'),
                    'subtitle_en' => $this->input->post('subtitle_en'),
                    'active' => (isset($_POST['isvisible']) ? 1 : 0),
                );
                $this->saveHome('video_gallery', $branchID, $arrayVideo);
                set_alert('success', translate('information_has_been_saved_successfully'));
                $url = base_url('frontend/section/home' . (is_superadmin_loggedin() ? '?branch_id=' . $branchID : '') . '#video_gallery');
                echo json_encode(array('status' => 'success', 'url' => $url));
            } else {
                echo json_encode(array('status' => 'fail', 'error' => $this->form_validation->error_array()));
            }
        }
    }

    public function home_gallery()
    {
        if ($_POST) {
            if (!get_permission('frontend_section', 'is_add')) {
                ajax_access_denied();
            }
            $branchID = $this->frontend_model->getBranchID();
            if (empty($branchID)) {
                $branchID = $this->input->post('branch_id') ?: 1;
            }
            $this->form_validation->set_rules('gal_title', 'Title', 'trim|required');
            if ($this->form_validation->run() == true) {
                $arrayGallery = array(
                    'branch_id' => $branchID,
                    'title' => $this->input->post('gal_title'),
                    'title_en' => $this->input->post('gal_title_en'),
                    'subtitle' => $this->input->post('subtitle'),
                    'subtitle_en' => $this->input->post('subtitle_en'),
                    'active' => (isset($_POST['isvisible']) ? 1 : 0),
                );
                $this->saveHome('gallery', $branchID, $arrayGallery);
                set_alert('success', translate('information_has_been_saved_successfully'));
                $url = base_url('frontend/section/home' . (is_superadmin_loggedin() ? '?branch_id=' . $branchID : '') . '#gallery');
                echo json_encode(array('status' => 'success', 'url' => $url));
            } else {
                echo json_encode(array('status' => 'fail', 'error' => $this->form_validation->error_array()));
            }
        }
    }

    public function home_hero()
    {
        if ($_POST) {
            if (!get_permission('frontend_section', 'is_add')) {
                ajax_access_denied();
            }
            $branchID = $this->frontend_model->getBranchID();
            if (empty($branchID)) {
                $branchID = $this->input->post('branch_id') ?: 1;
            }
            $this->form_validation->set_rules('hero_title', 'Title', 'trim|required');
            if ($this->form_validation->run() == true) {
                $elements = array(
                    'hero_btn_1_text' => $this->input->post('hero_btn_1_text'),
                    'hero_btn_1_url' => $this->input->post('hero_btn_1_url'),
                    'hero_btn_2_text' => $this->input->post('hero_btn_2_text'),
                    'hero_btn_2_url' => $this->input->post('hero_btn_2_url'),
                    'quick_btn_1_text' => $this->input->post('quick_btn_1_text'),
                    'quick_btn_1_url' => $this->input->post('quick_btn_1_url'),
                    'quick_btn_2_text' => $this->input->post('quick_btn_2_text'),
                    'quick_btn_2_url' => $this->input->post('quick_btn_2_url'),
                    'quick_btn_3_text' => $this->input->post('quick_btn_3_text'),
                    'quick_btn_3_url' => $this->input->post('quick_btn_3_url'),
                    'quick_btn_4_text' => $this->input->post('quick_btn_4_text'),
                    'quick_btn_4_url' => $this->input->post('quick_btn_4_url'),
                );
                $arrayHero = array(
                    'branch_id' => $branchID,
                    'title' => $this->input->post('hero_title'),
                    'title_en' => $this->input->post('hero_title_en'),
                    'subtitle' => $this->input->post('subtitle'),
                    'subtitle_en' => $this->input->post('subtitle_en'),
                    'description' => $this->input->post('tagline'),
                    'description_en' => $this->input->post('tagline_en'),
                    'active' => (isset($_POST['isvisible']) ? 1 : 0),
                    'elements' => json_encode($elements),
                );
                $this->saveHome('hero', $branchID, $arrayHero);
                set_alert('success', translate('information_has_been_saved_successfully'));
                $url = base_url('frontend/section/home' . (is_superadmin_loggedin() ? '?branch_id=' . $branchID : '') . '#hero');
                echo json_encode(array('status' => 'success', 'url' => $url));
            } else {
                echo json_encode(array('status' => 'fail', 'error' => $this->form_validation->error_array()));
            }
        }
    }

    function home_testimonial()
    {
        if ($_POST) {
            // check access permission
            if (!get_permission('frontend_section', 'is_add')) {
                ajax_access_denied();
            }

            $branchID = $this->frontend_model->getBranchID();
            $this->form_validation->set_rules('tes_title', 'Title', 'trim|required');
            $this->form_validation->set_rules('tes_description', 'Description', 'trim|required');
            if ($this->form_validation->run() == true) {
                // save information in the database
                $arrayTestimonial = array(
                    'branch_id' => $branchID,
                    'title' => $this->input->post('tes_title'),
                    'active' => (isset($_POST['isvisible']) ? 1 : 0),
                    'description' => $this->input->post('tes_description'),
                );
                // save information in the database
                $this->saveHome('testimonial', $branchID, $arrayTestimonial);

                set_alert('success', translate('information_has_been_saved_successfully'));
                $array = array('status' => 'success');
            } else {
                $error = $this->form_validation->error_array();
                $array = array('status' => 'fail', 'error' => $error);
            }
            echo json_encode($array);
        }
    }

    function home_services()
    {
        if ($_POST) {
            // check access permission
            if (!get_permission('frontend_section', 'is_add')) {
                ajax_access_denied();
            }

            $branchID = $this->frontend_model->getBranchID();
            $this->form_validation->set_rules('ser_title', 'Title', 'trim|required');
            $this->form_validation->set_rules('ser_description', 'Description', 'trim|required');
            if ($this->form_validation->run() == true) {
                // save information in the database
                $arrayServices = array(
                    'branch_id' => $branchID,
                    'title' => $this->input->post('ser_title'),
                    'color1' => $this->input->post('title_text_color'),
                    'color2' => $this->input->post('background_color'),
                    'active' => (isset($_POST['isvisible']) ? 1 : 0),
                    'description' => $this->input->post('ser_description'),
                );
                // save information in the database
                $this->saveHome('services', $branchID, $arrayServices);
                set_alert('success', translate('information_has_been_saved_successfully'));
                $array = array('status' => 'success');
            } else {
                $error = $this->form_validation->error_array();
                $array = array('status' => 'fail', 'error' => $error);
            }
            echo json_encode($array);
        }
    }

    function home_statistics()
    {
        if ($_POST) {
            // check access permission
            if (!get_permission('frontend_section', 'is_add')) {
                ajax_access_denied();
            }

            $branchID = $this->frontend_model->getBranchID();
            if (empty($branchID)) {
                $branchID = $this->input->post('branch_id') ?: 1;
            }
            $this->form_validation->set_rules('sta_title', 'Title', 'trim|required');
            if ($this->form_validation->run() == true) {
                $elements = array(
                    'initial_total_visitors' => intval($this->input->post('initial_total_visitors')),
                    'initial_month_visitors' => intval($this->input->post('initial_month_visitors')),
                    'initial_today_visitors' => intval($this->input->post('initial_today_visitors')),
                    'initial_today_date' => date('Y-m-d'),
                    'initial_month_date' => date('Y-m'),
                    'academic_passed' => intval($this->input->post('academic_passed')),
                    'academic_gpa5' => intval($this->input->post('academic_gpa5')),
                );
                $arrayServices = array(
                    'branch_id' => $branchID,
                    'title' => $this->input->post('sta_title'),
                    'title_en' => $this->input->post('sta_title_en'),
                    'subtitle' => $this->input->post('subtitle'),
                    'subtitle_en' => $this->input->post('subtitle_en'),
                    'active' => (isset($_POST['isvisible']) ? 1 : 0),
                    'description' => $this->input->post('sta_description'),
                    'elements' => json_encode($elements),
                );
                // save information in the database
                $this->saveHome('statistics', $branchID, $arrayServices);
                set_alert('success', translate('information_has_been_saved_successfully'));
                $url = base_url('frontend/section/home' . (is_superadmin_loggedin() ? '?branch_id=' . $branchID : '') . '#statistics');
                $array = array('status' => 'success', 'url' => $url);
            } else {
                $error = $this->form_validation->error_array();
                $array = array('status' => 'fail', 'error' => $error);
            }
            echo json_encode($array);
        }
    }

    function home_cta()
    {
        if ($_POST) {
            // check access permission
            if (!get_permission('frontend_section', 'is_add')) {
                ajax_access_denied();
            }

            $branchID = $this->frontend_model->getBranchID();
            $this->form_validation->set_rules('cta_title', 'Cta Title', 'trim|required');
            $this->form_validation->set_rules('mobile_no', 'Mobile No', 'trim|required');
            $this->form_validation->set_rules('button_text', 'Button Text', 'trim|required');
            $this->form_validation->set_rules('button_url', 'Button Url', 'trim|required');
            if ($this->form_validation->run() == true) {
                $elements_data = array(
                    'mobile_no' => $this->input->post('mobile_no'),
                    'button_text' => $this->input->post('button_text'),
                    'button_url' => $this->input->post('button_url'),
                );
                $array_cta = array(
                    'branch_id' => $branchID,
                    'title' => $this->input->post('cta_title'),
                    'color1' => $this->input->post('background_color'),
                    'color2' => $this->input->post('text_color'),
                    'active' => (isset($_POST['isvisible']) ? 1 : 0),
                    'elements' => json_encode($elements_data),
                );
                // save information in the database
                $this->saveHome('cta', $branchID, $array_cta);
                set_alert('success', translate('information_has_been_saved_successfully'));
                $array = array('status' => 'success');
            } else {
                $error = $this->form_validation->error_array();
                $array = array('status' => 'fail', 'error' => $error); 
            }
            echo json_encode($array);
        }
    }

    function home_options()
    {
        if ($_POST) {
            // check access permission
            if (!get_permission('frontend_section', 'is_add')) {
                ajax_access_denied();
            }
            $branchID = $this->frontend_model->getBranchID();
            if (empty($branchID)) {
                $branchID = $this->input->post('branch_id') ?: 1;
            }
            $this->form_validation->set_rules('page_title', 'Page Title', 'trim|required');
            if ($this->form_validation->run() == true) {
                // save information in the database
                $arraySeo = array(
                    'branch_id' => $branchID,
                    'page_title' => $this->input->post('page_title'),
                    'meta_keyword' => $this->input->post('meta_keyword', true),
                    'meta_description' => $this->input->post('meta_description', true),
                );
                $this->db->where('branch_id', $branchID);
                $get = $this->db->get('front_cms_home_seo');
                if ($get->num_rows() > 0) {
                    $this->db->where('id', $get->row()->id);
                    $this->db->update('front_cms_home_seo', $arraySeo);
                } else {
                    $this->db->insert('front_cms_home_seo', $arraySeo);
                }
                set_alert('success', translate('information_has_been_saved_successfully'));
                $url = base_url('frontend/section/home' . (is_superadmin_loggedin() ? '?branch_id=' . $branchID : '') . '#options');
                $array = array('status' => 'success', 'url' => $url);
            } else {
                $error = $this->form_validation->error_array();
                $array = array('status' => 'fail', 'error' => $error); 
            }
            echo json_encode($array);
        }
    }

    public function teachers()
    {
        $branchID = $this->frontend_model->getBranchID();
        if ($_POST) {
            if (!get_permission('frontend_section', 'is_add')) {
                ajax_access_denied();
            }
            $this->form_validation->set_rules('page_title', 'Page Title', 'trim|required');
            $this->form_validation->set_rules('photo', translate('photo'), 'callback_photoHandleUpload[photo]');
            if (isset($_FILES["photo"]) && empty($_FILES["photo"]['name']) && empty($_POST['old_photo'])) {
                $this->form_validation->set_rules('photo', translate('photo'), 'required');
            }
            if ($this->form_validation->run() == true) {
                // save information in the database
                $arrayData = array(
                    'branch_id' => $branchID,
                    'page_title' => $this->input->post('page_title'),
                    'meta_description' => $this->input->post('meta_description'),
                    'meta_keyword' => $this->input->post('meta_keyword'),
                    'banner_image' => $this->uploadImage('teachers' . $branchID, 'banners'),
                );
                $this->db->where('branch_id', $branchID);
                $get = $this->db->get('front_cms_teachers');
                if ($get->num_rows() > 0) {
                    $this->db->where('id', $get->row()->id);
                    $this->db->update('front_cms_teachers', $arrayData);
                } else {
                    $this->db->insert('front_cms_teachers', $arrayData);
                }
                set_alert('success', translate('information_has_been_saved_successfully'));
                $array = array('status' => 'success');
            } else {
                $error = $this->form_validation->error_array();
                $array = array('status' => 'fail', 'error' => $error); 
            }
            echo json_encode($array);
            exit();
        }
        $this->data['branch_id']    = $branchID;
        $this->data['teachers']     = $this->frontend_model->get('front_cms_teachers', array('branch_id' => $branchID), true);
        $this->data['title']        = translate('website_page');
        $this->data['sub_page']     = 'frontend/section_teachers';
        $this->data['main_menu']    = 'frontend';
        $this->load->view('layout/index', $this->data);
    }

    public function events()
    {
        $branchID = $this->frontend_model->getBranchID();
        $this->data['branch_id'] = $branchID;
        $this->data['events'] = $this->frontend_model->get('front_cms_events', array('branch_id' => $branchID), true);
        $this->data['title'] = translate('website_page');
        $this->data['sub_page'] = 'frontend/section_events';
        $this->data['main_menu'] = 'frontend';
        $this->load->view('layout/index', $this->data);
    }

    public function eventsSave()
    {
        if ($_POST) {
            // check access permission
            if (!get_permission('frontend_section', 'is_add')) {
                access_denied();
            }
            $branchID = $this->frontend_model->getBranchID();
            $this->form_validation->set_rules('title', 'Title', 'trim|required');
            $this->form_validation->set_rules('description', 'Description', 'trim|required');
            if ($this->form_validation->run() == true) {
                // save information in the database
                $arrayData = array(
                    'branch_id' => $branchID,
                    'title' => $this->input->post('title'),
                    'description' => $this->input->post('description', false),
                );
                $this->db->where('branch_id', $branchID);
                $get = $this->db->get('front_cms_events');
                if ($get->num_rows() > 0) {
                    $this->db->where('id', $get->row()->id);
                    $this->db->update('front_cms_events', $arrayData);
                } else {
                    $this->db->insert('front_cms_events', $arrayData);
                }
                set_alert('success', translate('information_has_been_saved_successfully'));
                $array = array('status' => 'success');
            } else {
                $error = $this->form_validation->error_array();
                $array = array('status' => 'fail', 'error' => $error); 
            }
            echo json_encode($array);
        }
    }

    public function eventsOptionSave()
    {
        if ($_POST) {
            // check access permission
            if (!get_permission('frontend_section', 'is_add')) {
                access_denied();
            }
            $branchID = $this->frontend_model->getBranchID();
            $this->form_validation->set_rules('page_title', 'Page Title', 'trim|required');
            $this->form_validation->set_rules('photo', translate('photo'), 'callback_photoHandleUpload[photo]');
            if (isset($_FILES["photo"]) && empty($_FILES["photo"]['name']) && empty($_POST['old_photo'])) {
                $this->form_validation->set_rules('photo', translate('photo'), 'required');
            }
            if ($this->form_validation->run() == true) {
                // save information in the database
                $arrayData = array(
                    'page_title' => $this->input->post('page_title'),
                    'meta_description' => $this->input->post('meta_description'),
                    'meta_keyword' => $this->input->post('meta_keyword'),
                    'banner_image' => $this->uploadImage('event' . $branchID, 'banners'),
                );

                $this->db->where('branch_id', $branchID);
                $get = $this->db->get('front_cms_events');
                if ($get->num_rows() > 0) {
                    $this->db->where('id', $get->row()->id);
                    $this->db->update('front_cms_events', $arrayData);
                } else {
                    $this->db->insert('front_cms_events', $arrayData);
                }
                set_alert('success', translate('information_has_been_saved_successfully'));
                $array = array('status' => 'success');
            } else {
                $error = $this->form_validation->error_array();
                $array = array('status' => 'fail', 'error' => $error); 
            }
            echo json_encode($array);
        }
    }

    public function about()
    {
        $branchID = $this->frontend_model->getBranchID();
        $this->data['branch_id']    = $branchID;
        $this->data['about']        = $this->frontend_model->get('front_cms_about', array('branch_id' => $branchID), true);
        $this->data['service']      = $this->frontend_model->get('front_cms_services', array('branch_id' => $branchID), true);
        $this->data['title']        = translate('website_page');
        $this->data['sub_page']     = 'frontend/section_about';
        $this->data['main_menu']    = 'frontend';
        $this->load->view('layout/index', $this->data);
    }

    public function aboutSave()
    {
        if ($_POST) {
            // check access permission
            if (!get_permission('frontend_section', 'is_add')) {
                ajax_access_denied();
            }

            $this->form_validation->set_rules('title', 'Title', 'trim|required');
            $this->form_validation->set_rules('subtitle', 'Subtitle', 'trim|required');
            $this->form_validation->set_rules('content', 'Content', 'trim|required');
            if ($this->form_validation->run() == true) {
                $branchID = $this->frontend_model->getBranchID();
                // save information in the database
                $arrayData = array(
                    'title' => $this->input->post('title'),
                    'title_en' => $this->input->post('title_en'),
                    'subtitle' => $this->input->post('subtitle'),
                    'subtitle_en' => $this->input->post('subtitle_en'),
                    'content' => $this->input->post('content', false),
                    'content_en' => $this->input->post('content_en', false),
                    'branch_id' => $branchID,
                );
                $about_img = $this->uploadImage('about' . $branchID, 'about');
                if (!empty($about_img)) {
                    $arrayData['about_image'] = $about_img;
                }
                $this->db->where('branch_id', $branchID);
                $get = $this->db->get('front_cms_about');
                if ($get->num_rows() > 0) {
                    $this->db->where('id', $get->row()->id);
                    $this->db->update('front_cms_about', $arrayData);
                } else {
                    $this->db->insert('front_cms_about', $arrayData);
                }
                $array = array('status' => 'success');
            } else {
                $error = $this->form_validation->error_array();
                $array = array('status' => 'fail', 'error' => $error); 
            }
            echo json_encode($array);
        }
    }

    public function aboutServiceSave()
    {
        if ($_POST) {
            // check access permission
            if (!get_permission('frontend_section', 'is_add')) {
                ajax_access_denied();
            }

            $this->form_validation->set_rules('title', 'Title', 'trim|required');
            $this->form_validation->set_rules('subtitle', 'Subtitle', 'trim|required');
            if ($this->form_validation->run() == true) {
                $branchID = $this->frontend_model->getBranchID();

                // save information in the database
                $arrayData = array(
                    'branch_id' => $branchID,
                    'title' => $this->input->post('title'),
                    'subtitle' => $this->input->post('subtitle'),
                    'parallax_image' => $this->uploadImage('service_parallax' . $branchID, 'about'),
                );

                $this->db->where('branch_id', $branchID);
                $get = $this->db->get('front_cms_services');
                if ($get->num_rows() > 0) {
                    $this->db->where('id', $get->row()->id);
                    $this->db->update('front_cms_services', $arrayData);
                } else {
                    $this->db->insert('front_cms_services', $arrayData);
                }
                $array = array('status' => 'success');
            } else {
                $error = $this->form_validation->error_array();
                $array = array('status' => 'fail', 'error' => $error); 
            }
            echo json_encode($array);
        }
    }

    public function aboutCtaSave()
    {
        if ($_POST) {
            // check access permission
            if (!get_permission('frontend_section', 'is_add')) {
                ajax_access_denied();
            }
            $branchID = $this->frontend_model->getBranchID();
            $this->form_validation->set_rules('cta_title', 'Cta Title', 'trim|required');
            $this->form_validation->set_rules('button_text', 'Button Text', 'trim|required');
            $this->form_validation->set_rules('button_url', 'Button Url', 'trim|required');
            if ($this->form_validation->run() == true) {
                // save information in the database
                $array_cta = array(
                    'cta_title' => $this->input->post('cta_title'),
                    'button_text' => $this->input->post('button_text'),
                    'button_url' => $this->input->post('button_url'),
                );
                $this->db->where('branch_id', $branchID);
                $get = $this->db->get('front_cms_about');
                if ($get->num_rows() > 0) {
                    $this->db->where('id', $get->row()->id);
                    $this->db->update('front_cms_about', array('elements' => json_encode($array_cta)) );
                } else {
                    $this->db->insert('front_cms_about', array('elements' => json_encode($array_cta)) );
                }
                set_alert('success', translate('information_has_been_saved_successfully'));
                $array = array('status' => 'success');
            } else {
                $error = $this->form_validation->error_array();
                $array = array('status' => 'fail', 'error' => $error); 
            }
            echo json_encode($array);
        }
    }

    public function aboutOptionsSave()
    {
        if ($_POST) {
            // check access permission
            if (!get_permission('frontend_section', 'is_add')) {
                ajax_access_denied();
            }
            $branchID = $this->frontend_model->getBranchID();
            $this->form_validation->set_rules('page_title', 'Page Title', 'trim|required');
            if ($this->form_validation->run() == true) {
                // save information in the database
                $arrayData = array(
                    'page_title' => $this->input->post('page_title'),
                    'page_title_en' => $this->input->post('page_title_en'),
                    'meta_description'  => $this->input->post('meta_description'),
                    'meta_keyword' => $this->input->post('meta_keyword'),
                );
                $banner_img = $this->uploadImage('about' . $branchID, 'banners');
                if (!empty($banner_img)) {
                    $arrayData['banner_image'] = $banner_img;
                }
                $this->db->where('branch_id', $branchID);
                $get = $this->db->get('front_cms_about');
                if ($get->num_rows() > 0) {
                    $this->db->where('id', $get->row()->id);
                    $this->db->update('front_cms_about', $arrayData);
                } else {
                    $this->db->insert('front_cms_about', $arrayData);
                }
                set_alert('success', translate('information_has_been_saved_successfully'));
                $array = array('status' => 'success');
            } else {
                $error = $this->form_validation->error_array();
                $array = array('status' => 'fail', 'error' => $error); 
            }
            echo json_encode($array);
        }
    }

    public function faq()
    {
        $branchID = $this->frontend_model->getBranchID();
        $this->data['branch_id'] = $branchID;
        $this->data['faq'] = $this->frontend_model->get('front_cms_faq', array('branch_id' => $branchID), true);
        $this->data['title'] = translate('website_page');
        $this->data['sub_page'] = 'frontend/section_faq';
        $this->data['main_menu'] = 'frontend';
        $this->load->view('layout/index', $this->data);
    }

    public function faqSave()
    {
        if ($_POST) {
            // check access permission
            if (!get_permission('frontend_section', 'is_add')) {
                access_denied();
            }
            $branchID = $this->frontend_model->getBranchID();
            $this->form_validation->set_rules('title', 'Title', 'trim|required');
            $this->form_validation->set_rules('description', 'Description', 'trim|required');
            if ($this->form_validation->run() == true) {
                // save information in the database
                $arrayData = array(
                    'branch_id' => $branchID,
                    'title' => $this->input->post('title'),
                    'description' => $this->input->post('description', false),
                );
                $this->db->where('branch_id', $branchID);
                $get = $this->db->get('front_cms_faq');
                if ($get->num_rows() > 0) {
                    $this->db->where('id', $get->row()->id);
                    $this->db->update('front_cms_faq', $arrayData);
                } else {
                    $this->db->insert('front_cms_faq', $arrayData);
                }
                set_alert('success', translate('information_has_been_saved_successfully'));
                $array = array('status' => 'success');
            } else {
                $error = $this->form_validation->error_array();
                $array = array('status' => 'fail', 'error' => $error); 
            }
            echo json_encode($array);
        }
    }

    public function faqOptionSave()
    {
        if ($_POST) {
            // check access permission
            if (!get_permission('frontend_section', 'is_add')) {
                access_denied();
            }
            $branchID = $this->frontend_model->getBranchID();
            $this->form_validation->set_rules('page_title', 'Page Title', 'trim|required');
            $this->form_validation->set_rules('photo', translate('photo'), 'callback_photoHandleUpload[photo]');
            if (isset($_FILES["photo"]) && empty($_FILES["photo"]['name']) && empty($_POST['old_photo'])) {
                $this->form_validation->set_rules('photo', translate('photo'), 'required');
            }
            if ($this->form_validation->run() == true) {
                // save information in the database
                $arrayData = array(
                    'page_title' => $this->input->post('page_title'),
                    'meta_description' => $this->input->post('meta_description'),
                    'meta_keyword' => $this->input->post('meta_keyword'),
                    'banner_image' => $this->uploadImage('faq' . $branchID, 'banners'),
                );

                $this->db->where('branch_id', $branchID);
                $get = $this->db->get('front_cms_faq');
                if ($get->num_rows() > 0) {
                    $this->db->where('id', $get->row()->id);
                    $this->db->update('front_cms_faq', $arrayData);
                } else {
                    $this->db->insert('front_cms_faq', $arrayData);
                }
                set_alert('success', translate('information_has_been_saved_successfully'));
                $array = array('status' => 'success');
            } else {
                $error = $this->form_validation->error_array();
                $array = array('status' => 'fail', 'error' => $error); 
            }
            echo json_encode($array);
        }
    }

    public function admission()
    {
        $branchID = $this->frontend_model->getBranchID();
        $this->data['branch_id'] = $branchID;
        $this->data['admission'] = $this->frontend_model->get('front_cms_admission', array('branch_id' => $branchID), true);
        $this->data['title'] = translate('website_page');
        $this->data['sub_page'] = 'frontend/section_admission';
        $this->data['main_menu'] = 'frontend';
        $this->load->view('layout/index', $this->data);
    }

    public function saveAdmission()
    {
        $branchID = $this->frontend_model->getBranchID();
        if ($_POST) {
            // check access permission
            if (!get_permission('frontend_section', 'is_add')) {
                ajax_access_denied();
            }
            $this->form_validation->set_rules('title', 'Title', 'trim|required');
            $this->form_validation->set_rules('admission_application_form', translate('admission_application_form'), 'callback_fileHandleUpload[admission_application_form]');
            $items = $this->input->post('addmissionfee');
            if (!empty($items)) {
                foreach ($items as $key => $value) {
                    if ($value['status'] == 1) {
                        $this->form_validation->set_rules('addmissionfee[' . $key . '][amount]', translate('amount'), 'trim|numeric|required');
                    }
                }
            }

            if ($this->form_validation->run() == true) {
                // save information in the database
                $feeElements = array();
                if (!empty($items)) {
                    foreach ($items as $key => $value) {
                        if ($value['status'] == 1) {
                            $classID = $value['class_id'];
                            $feeElements[$classID] = array(
                                'fee_status' => $value['status'],
                                'amount' => $value['amount']
                            );
                        }
                    }
                }
                $arrayData = array(
                    'branch_id' => $branchID,
                    'title' => $this->input->post('title'),
                    'description' => $this->input->post('description', false),
                    'terms_conditions_title' => $this->input->post('terms_conditions_title'),
                    'terms_conditions_description' => $this->input->post('terms_conditions_description', false),
                    'fee_elements' => json_encode($feeElements),
                );

                //upload admission application form
                $application_form_file = $this->input->post('old_admission_application_form');
                if (isset($_FILES["admission_application_form"]) && $_FILES['admission_application_form']['name'] != '' && (!empty($_FILES['admission_application_form']['name']))) {
                    $application_form_file = $this->saas_model->fileupload("admission_application_form", "./uploads/admission_form/", $application_form_file, true);
                    $arrayData['application_form_name'] = $_FILES['admission_application_form']['name'];
                }

                $arrayData['application_form_file'] = $application_form_file;

                $this->db->where('branch_id', $branchID);
                $get = $this->db->get('front_cms_admission');
                if ($get->num_rows() > 0) {
                    $this->db->where('id', $get->row()->id);
                    $this->db->update('front_cms_admission', $arrayData);
                } else {
                    $this->db->insert('front_cms_admission', $arrayData);
                }
                set_alert('success', translate('information_has_been_saved_successfully'));
                $array = array('status' => 'success');
            } else {
                $error = $this->form_validation->error_array();
                $array = array('status' => 'fail', 'error' => $error); 
            }
            echo json_encode($array);
        }
    }

    public function saveAdmissionOption()
    {
        $branchID = $this->frontend_model->getBranchID();
        if ($_POST) {
            if (!get_permission('frontend_section', 'is_add')) {
                ajax_access_denied();
            }
            $this->form_validation->set_rules('page_title', 'Page Title', 'trim|required');
            if ($this->form_validation->run() == true) {
                // save information in the database
                $arrayData = array(
                    'branch_id' => $branchID,
                    'page_title' => $this->input->post('page_title'),
                    'meta_keyword' => $this->input->post('meta_keyword'),
                    'meta_description' => $this->input->post('meta_description'),
                    'banner_image' => $this->uploadImage('admission' . $branchID, 'banners'),
                );

                $this->db->where('branch_id', $branchID);
                $get = $this->db->get('front_cms_admission');
                if ($get->num_rows() > 0) {
                    $this->db->where('id', $get->row()->id);
                    $this->db->update('front_cms_admission', $arrayData);
                } else {
                    $this->db->insert('front_cms_admission', $arrayData);
                }
                set_alert('success', translate('information_has_been_saved_successfully'));
                $array = array('status' => 'success');
            } else {
                $error = $this->form_validation->error_array();
                $array = array('status' => 'fail', 'error' => $error); 
            }
            echo json_encode($array);
        }
    }


    public function saveOnlineAdmissionFields()
    {
        $branchID = $this->frontend_model->getBranchID();
        if ($_POST) {
            if (!get_permission('frontend_section', 'is_add')) {
                ajax_access_denied();
            }

            $systemFields = $this->input->post('system_fields');
            foreach ($systemFields as $key => $value) {
                $is_status= (isset($value['status']) ? 1 : 0);
                $is_required = (isset($value['required']) ? 1 : 0);
                $arrayData = array(
                    'fields_id' => $key,
                    'branch_id' => $branchID,
                    'system' => 1,
                    'status' => $is_status,
                    'required' => $is_required,
                );
                $exist_privileges = $this->db->select('id')->limit(1)->where(array('branch_id' => $branchID, 'fields_id' => $key, 'system' => 1))->get('online_admission_fields')->num_rows();
                if ($exist_privileges > 0) {
                    $this->db->update('online_admission_fields', $arrayData, array('fields_id' => $key, 'branch_id' => $branchID, 'system' => 1));
                } else {
                    $this->db->insert('online_admission_fields', $arrayData);
                }
            }

            $customFields = $this->input->post('custom_fields');
            foreach ($customFields as $key => $value) {
                $is_status= (isset($value['status']) ? 1 : 0);
                $is_required = (isset($value['required']) ? 1 : 0);
                $arrayData = array(
                    'fields_id' => $key,
                    'branch_id' => $branchID,
                    'system' => 0,
                    'status' => $is_status,
                    'required' => $is_required,
                );
                $exist_privileges = $this->db->select('id')->limit(1)->where(array('branch_id' => $branchID, 'fields_id' => $key, 'system' => 0))->get('online_admission_fields')->num_rows();
                if ($exist_privileges > 0) {
                    $this->db->update('online_admission_fields', $arrayData, array('fields_id' => $key, 'branch_id' => $branchID, 'system' => 0));
                } else {
                    $this->db->insert('online_admission_fields', $arrayData);
                }
            }
            $message = translate('information_has_been_saved_successfully');
            $array = array('status' => 'success', 'message' => $message);
            echo json_encode($array);
        }
    }

    public function contact()
    {
        $branchID = $this->frontend_model->getBranchID();
        $this->data['branch_id'] = $branchID;
        $this->data['contact'] = $this->frontend_model->get('front_cms_contact', array('branch_id' => $branchID), true);
        $this->data['title'] = translate('website_page');
        $this->data['sub_page'] = 'frontend/section_contact';
        $this->data['main_menu'] = 'frontend';
        $this->load->view('layout/index', $this->data);
    }

    public function contactSave()
    {
        if ($_POST) {
            if (!get_permission('frontend_section', 'is_add')) {
                access_denied();
            }
            $branchID = $this->frontend_model->getBranchID();
            $this->form_validation->set_rules('box_title', 'Box Title', 'trim|required');
            $this->form_validation->set_rules('box_description', 'Box Description', 'trim|required');
            $this->form_validation->set_rules('form_title', 'Form Title', 'trim|required');
            $this->form_validation->set_rules('address', 'Address', 'trim|required');
            $this->form_validation->set_rules('phone', 'Phone', 'trim|required');
            $this->form_validation->set_rules('email', 'Email', 'trim|required');
            $this->form_validation->set_rules('submit_text', 'Submit Text', 'trim|required');
            $this->form_validation->set_rules('map_iframe', 'Map Iframe', 'trim|required');
            if ($this->form_validation->run() == true) {
                // save information in the database
                $arrayData = array(
                    'branch_id' => $branchID,
                    'box_title' => $this->input->post('box_title'),
                    'box_description' => $this->input->post('box_description'),
                    'form_title' => $this->input->post('form_title'),
                    'address' => $this->input->post('address'),
                    'phone' => $this->input->post('phone'),
                    'email' => $this->input->post('email'),
                    'submit_text' => $this->input->post('submit_text'),
                    'map_iframe' => $this->input->post('map_iframe', false),
                );

                // upload box image
                if (isset($_FILES["photo"]) && !empty($_FILES["photo"]['name'])) {
                    $imageNmae = $_FILES['photo']['name'];
                    $extension = pathinfo($imageNmae, PATHINFO_EXTENSION);
                    $newLogoName = "contact-info-box$branchID." . $extension;
                    $image_path = './uploads/frontend/images/' . $newLogoName;
                    if (move_uploaded_file($_FILES['photo']['tmp_name'], $image_path)) {
                        $arrayData['box_image'] = $newLogoName;
                    }
                }

                $this->db->where('branch_id', $branchID);
                $get = $this->db->get('front_cms_contact');
                if ($get->num_rows() > 0) {
                    $this->db->where('id', $get->row()->id);
                    $this->db->update('front_cms_contact', $arrayData);
                } else {
                    $this->db->insert('front_cms_contact', $arrayData);
                }
                set_alert('success', translate('information_has_been_saved_successfully'));
                $array = array('status' => 'success');
            } else {
                $error = $this->form_validation->error_array();
                $array = array('status' => 'fail', 'error' => $error); 
            }
            echo json_encode($array);
        }
    }

    function contactOptionSave()
    {
        if ($_POST) {
            if (!get_permission('frontend_section', 'is_add')) {
                access_denied();
            }
            $branchID = $this->frontend_model->getBranchID();
            $this->form_validation->set_rules('page_title', 'Page Title', 'trim|required');
            if ($this->form_validation->run() == true) {
                // save information in the database
                $array_about = array(
                    'branch_id' => $branchID,
                    'page_title' => $this->input->post('page_title'),
                    'meta_description' => $this->input->post('meta_description'),
                    'meta_keyword' => $this->input->post('meta_keyword'),
                    'banner_image' => $this->uploadImage('contact' . $branchID, 'banners'),
                );

                $this->db->where('branch_id', $branchID);
                $get = $this->db->get('front_cms_contact');
                if ($get->num_rows() > 0) {
                    $this->db->where('id', $get->row()->id);
                    $this->db->update('front_cms_contact', $array_about);
                } else {
                    $this->db->insert('front_cms_contact', $array_about);
                }
                set_alert('success', translate('information_has_been_saved_successfully'));
                $array = array('status' => 'success');
            } else {
                $error = $this->form_validation->error_array();
                $array = array('status' => 'fail', 'error' => $error); 
            }
            echo json_encode($array);
        }
    }

    // upload image
    public function uploadImage($img_name, $path)
    {
        $prev_image = $this->input->post('old_photo');
        $image = $_FILES['photo']['name'];
        $return_image = '';
        if ($image != '') {
            $destination = './uploads/frontend/' . $path . '/';
            $extension = pathinfo($image, PATHINFO_EXTENSION);
            $image_path = $img_name . '.' . $extension;
            move_uploaded_file($_FILES['photo']['tmp_name'], $destination . $image_path);
            // need to unlink previous slider
            if ($prev_image != $image_path) {
                if (file_exists($destination . $prev_image)) {
                    @unlink($destination . $prev_image);
                }
            }
            $return_image = $image_path;
        } else {
            $return_image = $prev_image;
        }
        return $return_image;
    }

    private function saveHome($item, $branch_id, $data)
    {
        $this->db->where(array('item_type' => $item, 'branch_id' => $branch_id));
        $get = $this->db->get('front_cms_home');
        if ($get->num_rows() > 0) {
            $this->db->where('id', $get->row()->id);
            $this->db->update('front_cms_home', $data);
        } else {
            $data['item_type'] = $item;
            $this->db->insert('front_cms_home', $data);
        }
    }

    public function admit_card()
    {
        $branchID = $this->frontend_model->getBranchID();
        if ($_POST) {
            if (!get_permission('frontend_section', 'is_add')) {
                ajax_access_denied();
            }
            $this->form_validation->set_rules('page_title', 'Page Title', 'trim|required');
            $this->form_validation->set_rules('description', 'Description', 'required');
            $this->form_validation->set_rules('photo', translate('photo'), 'callback_photoHandleUpload[photo]');
            if (isset($_FILES["photo"]) && empty($_FILES["photo"]['name']) && empty($_POST['old_photo'])) {
                $this->form_validation->set_rules('photo', translate('photo'), 'required');
            }
            $this->form_validation->set_rules('templete_id', 'Default Template', 'trim|required');
            if ($this->form_validation->run() == true) {
                // save information in the database
                $arrayData = array(
                    'branch_id' => $branchID,
                    'page_title' => $this->input->post('page_title'),
                    'description' => $this->input->post('description', false),
                    'templete_id' => $this->input->post('templete_id'),
                    'meta_description' => $this->input->post('meta_description'),
                    'meta_keyword' => $this->input->post('meta_keyword'),
                    'banner_image' => $this->uploadImage('admit_card' . $branchID, 'banners'),
                );
                $this->db->where('branch_id', $branchID);
                $get = $this->db->get('front_cms_admitcard');
                if ($get->num_rows() > 0) {
                    $this->db->where('id', $get->row()->id);
                    $this->db->update('front_cms_admitcard', $arrayData);
                } else {
                    $this->db->insert('front_cms_admitcard', $arrayData);
                }
                set_alert('success', translate('information_has_been_saved_successfully'));
                $array = array('status' => 'success');
            } else {
                $error = $this->form_validation->error_array();
                $array = array('status' => 'fail', 'error' => $error); 
            }
            echo json_encode($array);
            exit();
        }
        $this->data['branch_id']    = $branchID;
        $this->data['admitcard']    = $this->frontend_model->get('front_cms_admitcard', array('branch_id' => $branchID), true);
        $this->data['title']        = translate('website_page');
        $this->data['sub_page']     = 'frontend/section_admit_card';
        $this->data['main_menu']    = 'frontend';
        $this->load->view('layout/index', $this->data);
    }

    public function exam_results()
    {
        $branchID = $this->frontend_model->getBranchID();
        if ($_POST) {
            if (!get_permission('frontend_section', 'is_add')) {
                ajax_access_denied();
            }
            $this->form_validation->set_rules('page_title', 'Page Title', 'trim|required');
            $this->form_validation->set_rules('description', 'Description', 'required');
            $this->form_validation->set_rules('photo', translate('photo'), 'callback_photoHandleUpload[photo]');
            if (isset($_FILES["photo"]) && empty($_FILES["photo"]['name']) && empty($_POST['old_photo'])) {
                $this->form_validation->set_rules('photo', translate('photo'), 'required');
            }
            if ($this->form_validation->run() == true) {
                // save information in the database
                $arrayData = array(
                    'branch_id' => $branchID,
                    'page_title' => $this->input->post('page_title'),
                    'description' => $this->input->post('description', false),
                    'grade_scale' => isset($_POST['grade_scale']) ? 1 : 0,
                    'attendance' => isset($_POST['attendance']) ? 1 : 0,
                    'meta_description' => $this->input->post('meta_description'),
                    'meta_keyword' => $this->input->post('meta_keyword'),
                    'banner_image' => $this->uploadImage('exam_results' . $branchID, 'banners'),
                );
                $this->db->where('branch_id', $branchID);
                $get = $this->db->get('front_cms_exam_results');
                if ($get->num_rows() > 0) {
                    $this->db->where('id', $get->row()->id);
                    $this->db->update('front_cms_exam_results', $arrayData);
                } else {
                    $this->db->insert('front_cms_exam_results', $arrayData);
                }
                set_alert('success', translate('information_has_been_saved_successfully'));
                $array = array('status' => 'success');
            } else {
                $error = $this->form_validation->error_array();
                $array = array('status' => 'fail', 'error' => $error); 
            }
            echo json_encode($array);
            exit();
        }
        $this->data['branch_id']    = $branchID;
        $this->data['admitcard']    = $this->frontend_model->get('front_cms_exam_results', array('branch_id' => $branchID), true);
        $this->data['title']        = translate('website_page');
        $this->data['sub_page']     = 'frontend/section_exam_results';
        $this->data['main_menu']    = 'frontend';
        $this->load->view('layout/index', $this->data);
    }

    public function certificates()
    {
        $branchID = $this->frontend_model->getBranchID();
        if ($_POST) {
            if (!get_permission('frontend_section', 'is_add')) {
                ajax_access_denied();
            }
            $this->form_validation->set_rules('page_title', 'Page Title', 'trim|required');
            $this->form_validation->set_rules('description', 'Description', 'required');
            $this->form_validation->set_rules('photo', translate('photo'), 'callback_photoHandleUpload[photo]');
            if (isset($_FILES["photo"]) && empty($_FILES["photo"]['name']) && empty($_POST['old_photo'])) {
                $this->form_validation->set_rules('photo', translate('photo'), 'required');
            }
            if ($this->form_validation->run() == true) {
                // save information in the database
                $arrayData = array(
                    'branch_id' => $branchID,
                    'page_title' => $this->input->post('page_title'),
                    'description' => $this->input->post('description', false),
                    'meta_description' => $this->input->post('meta_description'),
                    'meta_keyword' => $this->input->post('meta_keyword'),
                    'banner_image' => $this->uploadImage('certificates' . $branchID, 'banners'),
                );
                $this->db->where('branch_id', $branchID);
                $get = $this->db->get('front_cms_certificates');
                if ($get->num_rows() > 0) {
                    $this->db->where('id', $get->row()->id);
                    $this->db->update('front_cms_certificates', $arrayData);
                } else {
                    $this->db->insert('front_cms_certificates', $arrayData);
                }
                set_alert('success', translate('information_has_been_saved_successfully'));
                $array = array('status' => 'success');
            } else {
                $error = $this->form_validation->error_array();
                $array = array('status' => 'fail', 'error' => $error); 
            }
            echo json_encode($array);
            exit();
        }
        $this->data['branch_id']    = $branchID;
        $this->data['admitcard']    = $this->frontend_model->get('front_cms_certificates', array('branch_id' => $branchID), true);
        $this->data['title']        = translate('website_page');
        $this->data['sub_page']     = 'frontend/section_certificates';
        $this->data['main_menu']    = 'frontend';
        $this->load->view('layout/index', $this->data);
    }

    public function gallery()
    {
        $branchID = $this->frontend_model->getBranchID();
        if ($_POST) {
            if (!get_permission('frontend_section', 'is_add')) {
                ajax_access_denied();
            }
            $this->form_validation->set_rules('page_title', 'Page Title', 'trim|required');
            $this->form_validation->set_rules('photo', translate('photo'), 'callback_photoHandleUpload[photo]');
            if (isset($_FILES["photo"]) && empty($_FILES["photo"]['name']) && empty($_POST['old_photo'])) {
                $this->form_validation->set_rules('photo', translate('photo'), 'required');
            }
            if ($this->form_validation->run() == true) {
                // save information in the database
                $arrayData = array(
                    'branch_id' => $branchID,
                    'page_title' => $this->input->post('page_title'),
                    'meta_description' => $this->input->post('meta_description'),
                    'meta_keyword' => $this->input->post('meta_keyword'),
                    'banner_image' => $this->uploadImage('gallery' . $branchID, 'banners'),
                );
                $this->db->where('branch_id', $branchID);
                $get = $this->db->get('front_cms_gallery');
                if ($get->num_rows() > 0) {
                    $this->db->where('id', $get->row()->id);
                    $this->db->update('front_cms_gallery', $arrayData);
                } else {
                    $this->db->insert('front_cms_gallery', $arrayData);
                }
                set_alert('success', translate('information_has_been_saved_successfully'));
                $array = array('status' => 'success');
            } else {
                $error = $this->form_validation->error_array();
                $array = array('status' => 'fail', 'error' => $error); 
            }
            echo json_encode($array);
            exit();
        }
        $this->data['branch_id']    = $branchID;
        $this->data['admitcard']    = $this->frontend_model->get('front_cms_gallery', array('branch_id' => $branchID), true);
        $this->data['title']        = translate('website_page');
        $this->data['sub_page']     = 'frontend/section_gallery';
        $this->data['main_menu']    = 'frontend';
        $this->load->view('layout/index', $this->data);
    }

    public function news()
    {
        $branchID = $this->frontend_model->getBranchID();
        if ($_POST) {
            if (!get_permission('frontend_section', 'is_add')) {
                ajax_access_denied();
            }
            $this->form_validation->set_rules('page_title', 'Page Title', 'trim|required');
            $this->form_validation->set_rules('description', 'Description', 'required');
            $this->form_validation->set_rules('photo', translate('photo'), 'callback_photoHandleUpload[photo]');
            if (isset($_FILES["photo"]) && empty($_FILES["photo"]['name']) && empty($_POST['old_photo'])) {
                $this->form_validation->set_rules('photo', translate('photo'), 'required');
            }
            if ($this->form_validation->run() == true) {
                // save information in the database
                $arrayData = array(
                    'branch_id' => $branchID,
                    'page_title' => $this->input->post('page_title'),
                    'description' => $this->input->post('description', false),
                    'meta_description' => $this->input->post('meta_description'),
                    'meta_keyword' => $this->input->post('meta_keyword'),
                    'banner_image' => $this->uploadImage('news' . $branchID, 'banners'),
                );
                $this->db->where('branch_id', $branchID);
                $get = $this->db->get('front_cms_news');
                if ($get->num_rows() > 0) {
                    $this->db->where('id', $get->row()->id);
                    $this->db->update('front_cms_news', $arrayData);
                } else {
                    $this->db->insert('front_cms_news', $arrayData);
                }
                set_alert('success', translate('information_has_been_saved_successfully'));
                $array = array('status' => 'success');
            } else {
                $error = $this->form_validation->error_array();
                $array = array('status' => 'fail', 'error' => $error); 
            }
            echo json_encode($array);
            exit();
        }
        $this->data['branch_id']    = $branchID;
        $this->data['admitcard']    = $this->frontend_model->get('front_cms_news', array('branch_id' => $branchID), true);
        $this->data['title']        = translate('website_page');
        $this->data['sub_page']     = 'frontend/section_news';
        $this->data['main_menu']    = 'frontend';
        $this->load->view('layout/index', $this->data);
    }
}

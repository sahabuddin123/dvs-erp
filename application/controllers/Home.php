<?php

defined('BASEPATH') or exit('No direct script access allowed');



/**

 * @package : Ramom school management system

 * @version : 7.0

 * @developed by : RamomCoder

 * @support : ramomcoder@yahoo.com

 * @author url : http://codecanyon.net/user/RamomCoder

 * @filename : Home.php

 * @copyright : Reserved RamomCoder Team

 */



class Home extends Frontend_Controller

{

    public function __construct()

    {

        parent::__construct();

        $this->load->helpers('custom_fields');

        $this->load->model('student_fields_model');

        $this->load->model('email_model');

        $this->load->model('testimonial_model');

        $this->load->model('gallery_model');

        $this->load->library('mailer');

        if (!$this->session->has_userdata('frontend_lang')) {
            $this->session->set_userdata('frontend_lang', 'bn');
        }
        if (!$this->session->has_userdata('set_lang')) {
            $this->session->set_userdata('set_lang', 'bengali');
        }
    }



    public function index()

    {

        $this->home();

    }



    public function lang($action = 'bn')
    {
        if ($action == 'en' || $action == 'english') {
            $this->session->set_userdata('frontend_lang', 'en');
            $this->session->set_userdata('set_lang', 'english');
        } else {
            $this->session->set_userdata('frontend_lang', 'bn');
            $this->session->set_userdata('set_lang', 'bengali');
        }

        if (!empty($_SERVER['HTTP_REFERER'])) {

            redirect($_SERVER['HTTP_REFERER']);

        } else {

            redirect(base_url(), 'refresh');

        }

    }



    public function home()

    {

        $branchID = $this->home_model->getDefaultBranch();

        $this->data['branchID'] = $branchID;

        $this->data['sliders'] = $this->home_model->getCmsHome('slider', $branchID, 1, false);

        $this->data['features'] = $this->home_model->getCmsHome('features', $branchID, 1, false);

        $this->data['wellcome'] = $this->home_model->getCmsHome('wellcome', $branchID, null);
        $this->data['teachers'] = $this->home_model->getCmsHome('teachers', $branchID, null);

        $teachers_list = [];
        $has_cms_teachers = false;
        if ($this->db->table_exists('teacher')) {
            $cms_teachers_count = $this->db->where('branch_id', $branchID)->count_all_results('teacher');
            if ($cms_teachers_count > 0) {
                $has_cms_teachers = true;
                $teachers_list = $this->db->where('branch_id', $branchID)->where('active', 1)->where('show_on_homepage', 1)->order_by('position', 'asc')->get('teacher')->result_array();
            }
        }
        // If no dedicated website teachers exist for this branch, load institutional teachers (role 3) for this branch
        if (!$has_cms_teachers && empty($teachers_list)) {
            $doc_list = $this->home_model->get_teacher_list('', $branchID);
            if (!empty($doc_list)) {
                foreach ($doc_list as $doc) {
                    $teachers_list[] = [
                        'id' => $doc['id'],
                        'name' => $doc['name'],
                        'name_bn' => $doc['name'],
                        'name_en' => $doc['name'],
                        'title' => !empty($doc['designation_name']) ? $doc['designation_name'] : translate('teacher'),
                        'designation' => !empty($doc['designation_name']) ? $doc['designation_name'] : translate('teacher'),
                        'subject' => !empty($doc['department_name']) ? $doc['department_name'] : '',
                        'image' => !empty($doc['photo']) ? $doc['photo'] : '',
                        'phone' => !empty($doc['mobileno']) ? $doc['mobileno'] : '',
                        'email' => !empty($doc['email']) ? $doc['email'] : '',
                        'message' => '',
                        'is_staff' => true,
                    ];
                }
            }
        }
        $this->data['teachers_list'] = $teachers_list;

        $this->data['testimonial'] = $this->home_model->getCmsHome('testimonial', $branchID);

        $this->data['testimonials'] = $this->db->where('branch_id', $branchID)->order_by('rank', 'asc')->get('front_cms_testimonial')->result_array();

        $this->data['services'] = $this->home_model->getCmsHome('services', $branchID);

        $this->data['cta_box'] = $this->home_model->getCmsHome('cta', $branchID);

        $this->data['statistics'] = $this->home_model->getCmsHome('statistics', $branchID, null);

        $this->data['hero_sec'] = $this->home_model->getCmsHome('hero', $branchID, null);

        $this->data['notice_sec'] = $this->home_model->getCmsHome('notice', $branchID, null);

        $this->data['leadership_sec'] = $this->home_model->getCmsHome('leadership', $branchID, null);

        $this->data['events_sec'] = $this->home_model->getCmsHome('events', $branchID, null);

        $this->data['video_sec'] = $this->home_model->getCmsHome('video_gallery', $branchID, null);

        $this->data['gallery_sec'] = $this->home_model->getCmsHome('gallery', $branchID, null);

        $this->data['notices'] = $this->home_model->getLatestNewsList($branchID, array('limit' => 10));

        $this->data['events'] = $this->home_model->getLatestEventList($branchID, array('limit' => 6));

        $this->data['gallery_list'] = $this->db->where('branch_id', $branchID)->where('file_type', 'image')->where('show_web', 1)->order_by('id', 'desc')->limit(8)->get('front_cms_gallery_content')->result_array();

        $this->data['video_list'] = $this->db->where('branch_id', $branchID)->where('file_type', 'video')->where('show_web', 1)->order_by('id', 'desc')->limit(6)->get('front_cms_gallery_content')->result_array();

        $this->data['page_data'] = $this->home_model->get('front_cms_home_seo', array('branch_id' => $branchID), true);

        $this->data['visitor_stats'] = function_exists('get_frontend_visitor_stats') ? get_frontend_visitor_stats($branchID) : ['total' => 0, 'month' => 0, 'today' => 0];

        $this->data['main_contents'] = $this->load->view('home/index', $this->data, true);

        $this->load->view('home/layout/index', $this->data);

    }



    public function about()

    {

        $branchID = $this->home_model->getDefaultBranch();

        $this->data['branchID'] = $branchID;

        $this->data['page_data'] = $this->home_model->get('front_cms_about', array('branch_id' => $branchID), true);

        $this->data['main_contents'] = $this->load->view('home/about', $this->data, true);

        $this->load->view('home/layout/index', $this->data);

    }



    public function faq()

    {

        $branchID = $this->home_model->getDefaultBranch();

        $this->data['branchID'] = $branchID;

        $this->data['page_data'] = $this->home_model->get('front_cms_faq', array('branch_id' => $branchID), true);

        $this->data['main_contents'] = $this->load->view('home/faq', $this->data, true);

        $this->load->view('home/layout/index', $this->data);

    }



    public function events()

    {

        $branchID = $this->home_model->getDefaultBranch();

        $url_alias = $this->data['cms_setting']['url_alias'];

        $getLatestEventList = $this->home_model->getLatestEventList($branchID);

        $page = empty($this->input->get('page')) ? 0 : html_escape(urldecode($this->input->get('page')));

        if (is_numeric($page)) {

            $page = !empty($page) ? $page : 0;

        } else {

            $page = 0;

        }

        if (!empty($getLatestEventList)) {

            $total_records = count($getLatestEventList);

        } else {

            $total_records = 0;

        }

        $config = array();

        $config['page_query_string'] = TRUE;

        $config['query_string_segment'] = 'page';

        $config["base_url"] = base_url() . $url_alias . '/events';

        $config["total_rows"] = $total_records;

        $config["per_page"] = 12;

        $config['full_tag_open'] = '<ul class="pagination justify-content-center">';

        $config['full_tag_close'] = '</ul>';

        $config['first_link'] = '<i class="fas fa-angle-double-left"></i>';

        $config['first_tag_open'] = '<li class="previous">';

        $config['first_tag_close'] = '</li>';

        $config['last_link'] = '<i class="fas fa-angle-double-right"></i>';

        $config['last_tag_open'] = '<li class="next">';

        $config['last_tag_close'] = '</li>';

        $config['next_link'] = '<i class="fas fa-angle-right"></i>';

        $config['next_tag_open'] = '<li class="next">';

        $config['next_tag_close'] = '</li>';

        $config['prev_link'] = '<i class="fas fa-angle-left"></i>';

        $config['prev_tag_open'] = '<li class="previous">';

        $config['prev_tag_close'] = '</li>';

        $config['cur_tag_open'] = '<li class="active"><span>';

        $config['cur_tag_close'] = '</span></li>';

        $config['num_tag_open'] = '<li>';

        $config['num_tag_close'] = '</il>';

        $this->pagination->initialize($config);

        $conditions['limit'] = $config["per_page"];

        $conditions['start'] = $page;

        $this->data["links"] = $this->pagination->create_links();

        $this->data["results"] = $this->home_model->getLatestEventList($branchID, $conditions);

        $this->data['branchID'] = $branchID;

        $this->data['page_data'] = $this->home_model->get('front_cms_events', array('branch_id' => $branchID), true);

        $this->data['main_contents'] = $this->load->view('home/events', $this->data, true);

        $this->load->view('home/layout/index', $this->data);

    }



    public function event_view($id)

    {

        $branchID = $this->home_model->getDefaultBranch();

        $this->data['branchID'] = $branchID;

        $this->data['event'] = $this->home_model->get('event', array('id' => urldecode($id), 'branch_id' => $branchID, 'status' => 1, 'show_web' => 1), true);

        if (empty($this->data['event']['id'])) {

            redirect($_SERVER['HTTP_REFERER']);

        }

        $this->data['page_data'] = $this->home_model->get('front_cms_events', array('branch_id' => $branchID), true);

        $this->data['main_contents'] = $this->load->view('home/event_view', $this->data, true);

        $this->load->view('home/layout/index', $this->data);

    }



    public function news_view($alias = '')

    {

        $branchID = $this->home_model->getDefaultBranch();

        $this->data['branchID'] = $branchID;

        $event = $this->home_model->get('front_cms_news_list', array('alias' => urldecode($alias), 'branch_id' => $branchID, 'show_web' => 1), true);

        if (empty($event['id']) && is_numeric($alias)) {

            $event = $this->home_model->get('front_cms_news_list', array('id' => (int)$alias, 'branch_id' => $branchID, 'show_web' => 1), true);

        }

        if (empty($event['id']) && is_numeric($alias)) {

            $event = $this->home_model->get('front_cms_news_list', array('id' => (int)$alias), true);

        }

        if (empty($event['id'])) {

            $event = $this->db->order_by('id', 'desc')->get_where('front_cms_news_list', array('branch_id' => $branchID, 'show_web' => 1))->row_array();

        }

        $this->data['event'] = $event;

        $page_data = $this->home_model->get('front_cms_news', array('branch_id' => $branchID), true);

        if (empty($page_data)) {

            $page_data = [];

        }

        $current_lang = function_exists('get_current_lang') ? get_current_lang() : 'bn';

        $page_data['page_title'] = ($current_lang == 'en') ? 'Notice Details' : 'নোটিশ বিস্তারিত';

        $this->data['page_data'] = $page_data;

        $this->data['main_contents'] = $this->load->view('home/news_view', $this->data, true);

        $this->load->view('home/layout/index', $this->data);

    }



    public function teachers()
    {
        $branchID = $this->home_model->getDefaultBranch();
        $this->data['branchID'] = $branchID;
        $this->data['page_data'] = $this->home_model->get('front_cms_teachers', array('branch_id' => $branchID), true);
        $this->data['departments'] = $this->home_model->get_teacher_departments($branchID);

        $doctor_list = [];
        if ($this->db->table_exists('teacher')) {
            $cms_count = $this->db->where('branch_id', $branchID)->count_all_results('teacher');
            if ($cms_count > 0) {
                $doctor_list = $this->db->where('branch_id', $branchID)->where('active', 1)->order_by('position', 'asc')->get('teacher')->result_array();
            }
        }
        if (empty($doctor_list)) {
            $doctor_list = $this->home_model->get_teacher_list("", $branchID);
        }

        $this->data['doctor_list'] = $doctor_list;
        $this->data['active_menu'] = 'teachers';
        $this->data['main_contents'] = $this->load->view('home/teachers', $this->data, true);
        $this->load->view('home/layout/index', $this->data);
    }



        public function students()
    {
        $branchID = $this->home_model->getDefaultBranch();
        $this->data['branchID'] = $branchID;
        $this->data['page_data'] = array(
            'page_title' => 'শিক্ষার্থী',
            'page_title_en' => 'Students',
            'title' => 'শিক্ষার্থীর তথ্য',
            'title_en' => 'Student Information'
        );
        $this->data['active_menu'] = 'students';

        // Fetch dynamic sections for this branch
        $this->db->where('branch_id', $branchID);
        $this->db->order_by('id', 'ASC');
        $sections = $this->db->get('section')->result_array();

        // Count enrolled students per section in active session
        $session_id = get_session_id();
        $student_counts = array();
        $counts = $this->db->select('section_id, count(id) as total')
            ->where('branch_id', $branchID)
            ->where('session_id', $session_id)
            ->group_by('section_id')
            ->get('enroll')
            ->result_array();
        foreach ($counts as $c) {
            $student_counts[$c['section_id']] = $c['total'];
        }

        $this->data['sections'] = $sections;
        $this->data['student_counts'] = $student_counts;
        $this->data['main_contents'] = $this->load->view('home/students', $this->data, true);
        $this->load->view('home/layout/index', $this->data);
    }

    public function student_section($section_id = 0)
    {
        $branchID = $this->home_model->getDefaultBranch();
        $this->data['branchID'] = $branchID;

        // Fetch section details
        $section = $this->db->where('id', $section_id)->where('branch_id', $branchID)->get('section')->row_array();
        if (empty($section)) {
            $section = $this->db->where('id', $section_id)->get('section')->row_array();
        }

        if (empty($section)) {
            redirect(base_url('students'));
            return;
        }

        $this->data['section'] = $section;
        $this->data['section_id'] = $section['id'];

        // Active session & academic year
        $session_id = get_session_id();
        $this->data['session_id'] = $session_id;
        $session_row = $this->db->where('id', $session_id)->get('schoolyear')->row_array();
        $academic_year = !empty($session_row['school_year']) ? $session_row['school_year'] : date('Y');
        $this->data['academic_year'] = $academic_year;

        // Fetch classes allocated to this section
        $this->db->select('c.id, c.name');
        $this->db->from('class as c');
        $this->db->join('sections_allocation as sa', 'sa.class_id = c.id', 'inner');
        $this->db->where('sa.section_id', $section['id']);
        $this->db->where('c.branch_id', $branchID);
        $this->db->order_by('c.id', 'ASC');
        $classes = $this->db->get()->result_array();

        if (empty($classes)) {
            // Fallback: all classes of the branch
            $classes = $this->db->where('branch_id', $branchID)->order_by('id', 'ASC')->get('class')->result_array();
        }
        $this->data['classes'] = $classes;

        // Check if class_id is passed via GET or POST
        $class_id = $this->input->get_post('class_id', true);
        $this->data['selected_class_id'] = $class_id;
        $students = array();

        if (!empty($class_id)) {
            $this->db->select("e.id as enroll_id, e.roll, s.id as student_id, CONCAT_WS(' ', s.first_name, s.last_name) as fullname, s.photo, s.register_no, s.gender, c.name as class_name, se.name as section_name");
            $this->db->from('enroll as e');
            $this->db->join('student as s', 's.id = e.student_id', 'inner');
            $this->db->join('login_credential as l', 'l.user_id = s.id AND l.role = 7', 'left');
            $this->db->join('class as c', 'c.id = e.class_id', 'left');
            $this->db->join('section as se', 'se.id = e.section_id', 'left');
            $this->db->where('e.section_id', $section['id']);
            $this->db->where('e.class_id', $class_id);
            $this->db->where('e.branch_id', $branchID);
            $this->db->where('e.session_id', $session_id);
            $this->db->where('(l.active = 1 OR l.active IS NULL)');
            $this->db->order_by('e.roll', 'ASC');
            $this->db->order_by('s.id', 'ASC');
            $students = $this->db->get()->result_array();
        }

        $this->data['students'] = $students;

        $current_lang = !empty($this->data['current_lang']) ? $this->data['current_lang'] : 'bn';
        $this->data['page_data'] = array(
            'page_title' => $section['name'],
            'page_title_en' => $section['name'],
            'title' => ($current_lang == 'en') ? 'Student List - ' . $section['name'] : 'শিক্ষার্থী তালিকা - ' . $section['name']
        );
        $this->data['active_menu'] = 'students';
        $this->data['main_contents'] = $this->load->view('home/student_section', $this->data, true);
        $this->load->view('home/layout/index', $this->data);
    }

    public function get_section_students_ajax()
    {
        $branchID = $this->home_model->getDefaultBranch();
        $section_id = $this->input->get_post('section_id', true);
        $class_id = $this->input->get_post('class_id', true);
        $session_id = get_session_id();

        if (empty($section_id) || empty($class_id)) {
            echo json_encode(array(
                'status' => 'error',
                'message' => 'শ্রেণি নির্বাচন করুন।'
            ));
            return;
        }

        $this->db->select("e.id as enroll_id, e.roll, s.id as student_id, CONCAT_WS(' ', s.first_name, s.last_name) as fullname, s.photo, s.register_no, s.gender, c.name as class_name, se.name as section_name");
        $this->db->from('enroll as e');
        $this->db->join('student as s', 's.id = e.student_id', 'inner');
        $this->db->join('login_credential as l', 'l.user_id = s.id AND l.role = 7', 'left');
        $this->db->join('class as c', 'c.id = e.class_id', 'left');
        $this->db->join('section as se', 'se.id = e.section_id', 'left');
        $this->db->where('e.section_id', $section_id);
        $this->db->where('e.class_id', $class_id);
        $this->db->where('e.branch_id', $branchID);
        $this->db->where('e.session_id', $session_id);
        $this->db->where('(l.active = 1 OR l.active IS NULL)');
        $this->db->order_by('e.roll', 'ASC');
        $this->db->order_by('s.id', 'ASC');
        $students = $this->db->get()->result_array();

        $current_lang = (!empty($this->session->userdata('set_lang')) && $this->session->userdata('set_lang') == 'english') ? 'en' : 'bn';

        $html = $this->load->view('home/student_list_table', array(
            'students' => $students,
            'current_lang' => $current_lang
        ), true);

        echo json_encode(array(
            'status' => 'success',
            'count' => count($students),
            'html' => $html
        ));
    }

    public function student_department($slug = '')
    {
        redirect(base_url('students'));
    }

    public function admission()

    {

        if (!$this->data['cms_setting']['online_admission']) {

            redirect(site_url('home'));

        }





        $branchID = $this->home_model->getDefaultBranch();

        $captcha = $this->data['cms_setting']['captcha_status'];

        if ($captcha == 'enable') {

            $this->load->library('recaptcha', array('site_key' => $this->data['cms_setting']['recaptcha_site_key'], 'secret_key' => $this->data['cms_setting']['recaptcha_secret_key']));

            $this->data['recaptcha'] = array(

                'widget' => $this->recaptcha->getWidget(),

                'script' => $this->recaptcha->getScriptTag(),

            );

        }

        if ($_POST) {

            $this->form_validation->set_rules("first_name", "First Name", "trim|required");

            $this->form_validation->set_rules("class_id", "Class", "trim|required");

            $this->form_validation->set_rules("father_mobile_no", "Father Mobile No", "trim|numeric");

            $this->form_validation->set_rules("mother_mobile_no", "Mother Mobile No", "trim|numeric");

            $this->form_validation->set_rules("guardian_photo", "Guardian Photo", "callback_handle_upload[guardian_photo]");

            $this->form_validation->set_rules("father_photo", "Father Photo", "callback_handle_upload[father_photo]");

            $this->form_validation->set_rules("mother_photo", "Mother Photo", "callback_handle_upload[mother_photo]");

            $this->form_validation->set_rules("student_photo", "Student Photo", "callback_handle_upload[student_photo]");



            $validationArr = $this->student_fields_model->getOnlineStatusArr($branchID);

            unset($validationArr[0]);

            foreach ($validationArr as $key => $value) {

                if ($value->status && $value->required) {

                    if ($value->prefix == 'student_email' || $value->prefix == 'guardian_email') {

                        $this->form_validation->set_rules("$value->prefix", "Email", 'trim|required|valid_email');

                    } else if($value->prefix == 'student_mobile_no' || $value->prefix == 'guardian_mobile_no') {

                        $this->form_validation->set_rules("$value->prefix", "Mobile No", 'trim|required|numeric');

                    } else if($value->prefix == 'student_photo' || $value->prefix == 'guardian_photo' || $value->prefix == 'upload_documents') {

                        if (isset($_FILES["$value->prefix"]) && empty($_FILES["$value->prefix"]['name'])) {

                            $this->form_validation->set_rules("$value->prefix", ucwords(str_replace('_', ' ', $value->prefix)), "required" );

                        }

                    } else if($value->prefix == 'previous_school_details') {

                        $this->form_validation->set_rules("school_name", "School Name", "trim|required" );

                        $this->form_validation->set_rules("qualification", "Qualification", "trim|required" );

                    } else {

                        $this->form_validation->set_rules($value->prefix, ucwords(str_replace('_', ' ', $value->prefix)), 'trim|required');

                    }

                }  

            }



            if ($captcha == 'enable') {

                $this->form_validation->set_rules('g-recaptcha-response', 'Captcha', 'trim|required');

            }

            // custom fields validation rules

            $customFields = getOnlineCustomFields('student', $branchID);

            foreach ($customFields as $fields_key => $fields_value) {

                if ($fields_value['required']) {

                    $fieldsID = $fields_value['id'];

                    $fieldLabel = $fields_value['field_label'];

                    $this->form_validation->set_rules("custom_fields[student][" . $fieldsID . "]", $fieldLabel, 'trim|required');

                }

            }



            if ($this->form_validation->run() == true) {

                $admissionDate = !empty($_POST['admission_date']) ? date("Y-m-d", strtotime($this->input->post('admission_date'))) : "";

                $birthday = !empty($_POST['birthday']) ? date("Y-m-d", strtotime($this->input->post('birthday'))) : "";

                

                $previous_details = $this->input->post('school_name');

                if (!empty($previous_details)) {

                    $previous_details = array(

                        'school_name' => $this->input->post('school_name'),

                        'qualification' => $this->input->post('qualification'),

                        'remarks' => $this->input->post('previous_remarks'),

                    );

                    $previous_details =  json_encode($previous_details);

                } else {

                    $previous_details = "";

                }



                do {

                    $reference_no = mt_rand(0000001, 99999999);

                    $refence_status = $this->home_model->checkAdmissionReferenceNo($reference_no);

                } while ($refence_status);



                $arrayData = array(

                    'reference_no' => $reference_no,

                    'first_name' => $this->input->post('first_name'),

                    'last_name' => $this->input->post('last_name'),

                    'gender' => $this->input->post('gender'),

                    'birthday' => $birthday,

                    'admission_date' => $admissionDate,

                    'religion' => $this->input->post('religion'),

                    'caste' => $this->input->post('caste'),

                    'blood_group' => $this->input->post('blood_group'),

                    'mobile_no' => $this->input->post('student_mobile_no'),

                    'mother_tongue' => $this->input->post('mother_tongue'),

                    'present_address' => $this->input->post('present_address'),

                    'permanent_address' => $this->input->post('permanent_address'),

                    'city' => $this->input->post('city'),

                    'state' => $this->input->post('state'),

                    'category_id' => $this->input->post('category'),

                    'email' => $this->input->post('student_email'),

                    'student_photo' => $this->uploadImage('images/student', 'student_photo'),

                    'previous_school_details' => $previous_details,

                    'guardian_name' => $this->input->post('guardian_name'),

                    'guardian_relation' => $this->input->post('guardian_relation'),

                    'father_name' => $this->input->post('father_name'),

                    'mother_name' => $this->input->post('mother_name'),

                    'father_mobile_no' => $this->input->post('father_mobile_no'),

                    'mother_mobile_no' => $this->input->post('mother_mobile_no'),

                    'grd_occupation' => $this->input->post('guardian_occupation'),

                    'grd_income' => $this->input->post('guardian_income'),

                    'grd_education' => $this->input->post('guardian_education'),

                    'grd_email' => $this->input->post('guardian_email'),

                    'grd_mobile_no' => $this->input->post('guardian_mobile_no'),

                    'grd_address' => $this->input->post('guardian_address'),

                    'grd_city' => $this->input->post('guardian_city'),

                    'grd_state' => $this->input->post('guardian_state'),

                    'grd_photo' => $this->uploadImage('images/parent', 'guardian_photo'),

                    'father_photo' => $this->uploadImage('images/parent', 'father_photo'),

                    'mother_photo' => $this->uploadImage('images/parent', 'mother_photo'),

                    'status' => 1,

                    'branch_id' => $branchID,

                    'class_id' => $this->input->post('class_id'),

                    'section_id' => $this->input->post('section'),

                    'doc' => $this->uploadImage('online_ad_documents', 'upload_documents'),

                    'apply_date' => date("Y-m-d H:i:s"),

                    'created_date' => date("Y-m-d H:i:s"),

                );

                $this->db->insert('online_admission', $arrayData);

                $studentID = $this->db->insert_id();



                // handle custom fields data

                $class_slug = 'student';

                $customField = $this->input->post("custom_fields[$class_slug]");

                if (empty($customField)) {

                    $customField = array();

                }

                saveCustomFieldsOnline($customField, $studentID);

                // check out admission payment status

                $this->load->model('admissionpayment_model');

                $getStudent = $this->admissionpayment_model->getStudentDetails($reference_no);

                if ($getStudent['fee_elements']['status'] == 0) {

                    $url = base_url("home/admission_confirmation/" . $reference_no);

                    if (empty($arrayData['section_id'])) {

                       $section_name = "N/A";

                    } else {

                       $section_name = get_type_name_by_id('section', $arrayData['section_id']);

                    }

                    // applicant email send 

                    $arrayData['institute_name'] = get_type_name_by_id('branch', $arrayData['branch_id']);

                    $arrayData['reference_no'] = $reference_no;

                    $arrayData['student_name'] = $arrayData['first_name'] . " " . $arrayData['last_name'];

                    $arrayData['class_name'] = get_type_name_by_id('class', $arrayData['class_id']);

                    $arrayData['section_name'] = $section_name;

                    $arrayData['payment_url'] = base_url("admissionpayment/index/" . $reference_no);

                    $arrayData['admission_copy_url'] = $url;

                    $arrayData['paid_amount'] = 0;

                    $this->email_model->onlineAdmission($arrayData);

                    

                    $success = "Thank you for submitting the online registration form. Please you can print this copy.";

                    $this->session->set_flashdata('success', $success);

                } else {

                    $url = base_url("admissionpayment/index/" . $reference_no);

                }

                $array = array('status' => 'success', 'url' => $url);

            } else {

                $error = $this->form_validation->error_array();

                $array = array('status' => 'fail', 'error' => $error);

            }

            echo json_encode($array);

            exit();

        }



        $this->data['branchID'] = $branchID;

        $this->data['page_data'] = $this->home_model->get('front_cms_admission', array('branch_id' => $branchID), true);

        $this->data['main_contents'] = $this->load->view('home/admission', $this->data, true);

        $this->load->view('home/layout/index', $this->data);

    }



    public function checkAdmissionStatus()

    {

        if ($_POST) {

            $this->form_validation->set_rules("refno", "Enter Your Reference Number", "trim|required|callback_admissionstatus");

            if ($this->form_validation->run() == true) {

                $reference_no = $this->input->post("refno");

                $url = base_url("home/admission_confirmation/" . $reference_no);

                $array = array('status' => 'success', 'url' => $url);

            } else {

                $error = $this->form_validation->error_array();

                $array = array('status' => 'fail', 'error' => $error);   

            }

            echo json_encode($array);

            exit(); 

        }

    }



    public function admissionstatus($reference_no)

    {

        if (!empty($reference_no)) {

            $this->db->where('reference_no', urldecode($reference_no));

            $query = $this->db->get('online_admission')->num_rows();

            if ($query < 1) {

                $this->form_validation->set_message('admissionstatus', "Invalid Reference Number.");

                return false;

            }

        }

        return true;

    }



    public function handle_upload($str, $fields)

    {

        if (isset($_FILES["$fields"]) && !empty($_FILES["$fields"]['name'])) {

            $file_size = $_FILES["$fields"]["size"];

            $file_name = $_FILES["$fields"]["name"];

            $allowedExts = array('jpg', 'jpeg', 'png');

            $extension = pathinfo($file_name, PATHINFO_EXTENSION);

            if ($files = filesize($_FILES["$fields"]['tmp_name'])) {

                if (!in_array(strtolower($extension), $allowedExts)) {

                    $this->form_validation->set_message('handle_upload', translate('this_file_type_is_not_allowed'));

                    return false;

                }

                if ($file_size > 2097152) {

                    $this->form_validation->set_message('handle_upload', translate('file_size_shoud_be_less_than') . " 2048KB.");

                    return false;

                }

            } else {

                $this->form_validation->set_message('handle_upload', translate('error_reading_the_file'));

                return false;

            }

            return true;

        }

    }



    public function uploadImage($role, $fields) {

        $return_photo = '';

        if (isset($_FILES["$fields"]) && !empty($_FILES["$fields"]['name'])) {

            $config['upload_path'] = './uploads/' . $role . '/';

            $config['overwrite'] = FALSE;

            $config['encrypt_name'] = TRUE;

            $config['allowed_types'] = '*';

            $this->upload->initialize($config);

            if ($this->upload->do_upload("$fields")) {

                $return_photo = $this->upload->data('file_name');

            }

        }

        return $return_photo;

    }



    public function admission_confirmation($referenceNo = '')

    {

        $this->load->model('admissionpayment_model');

        $getStudent = $this->admissionpayment_model->getStudentDetails($referenceNo);

        if (empty($getStudent['id'])) {

            set_alert('error', "This application was not found.");

            redirect($_SERVER['HTTP_REFERER']);

        }

        $this->data['student'] = $getStudent;

        $this->data['branchID'] = $this->data['student']['branch_id'];

        $this->data['page_data'] = $this->home_model->get('front_cms_admission', array('branch_id' => $this->data['student']['branch_id']), true);

        $this->data['main_contents'] = $this->load->view('home/admission_confirmation', $this->data, true);

        $this->load->view('home/layout/index', $this->data);

    }



    public function refresh_contact_captcha()
    {
        $num1 = rand(1, 9);
        $num2 = rand(1, 9);
        $sum = $num1 + $num2;
        $this->session->set_userdata('contact_math_captcha', $sum);

        $bn_digits = ['০','১','২','৩','৪','৫','৬','৭','৮','৯'];
        $en_digits = ['0','1','2','3','4','5','6','7','8','9'];
        $num1_bn = str_replace($en_digits, $bn_digits, (string)$num1);
        $num2_bn = str_replace($en_digits, $bn_digits, (string)$num2);

        header('Content-Type: application/json');
        echo json_encode(array(
            'status' => 'success',
            'num1' => $num1,
            'num2' => $num2,
            'question_en' => "$num1 + $num2 = ?",
            'question_bn' => "$num1_bn + $num2_bn = ?"
        ));
        exit;
    }

    public function contact()
    {
        $branchID = $this->home_model->getDefaultBranch();
        $current_lang = function_exists('get_current_lang') ? get_current_lang() : ($this->session->userdata('frontend_lang') ? $this->session->userdata('frontend_lang') : 'bn');

        if ($_POST) {
            $ip = $this->input->ip_address();

            // 1. Anti-Spam Rate Limiting: Cooldown check (minimum 30s gap between submissions)
            $last_submit = $this->session->userdata('last_contact_submit_time');
            if (!empty($last_submit) && (time() - $last_submit) < 30) {
                $remaining = 30 - (time() - $last_submit);
                $error_msg = ($current_lang == 'en')
                    ? "Please wait {$remaining} seconds before submitting another message."
                    : "দয়া করে অপেক্ষা করুন! আপনি খুব ঘনঘন বার্তা পাঠাচ্ছেন। আরো {$remaining} সেকেন্ড পর আবার চেষ্টা করুন।";
                $this->session->set_flashdata('msg_error', $error_msg);
                redirect(current_url());
                return;
            }

            // 2. Anti-Spam Rate Limiting: Max 5 submissions per IP per hour
            $one_hour_ago = date('Y-m-d H:i:s', strtotime('-1 hour'));
            $recent_count = $this->db->where('ip_address', $ip)
                ->where('created_at >=', $one_hour_ago)
                ->count_all_results('front_cms_contact_messages');

            if ($recent_count >= 5) {
                $error_msg = ($current_lang == 'en')
                    ? 'You have reached the maximum allowed messages (5 per hour) from your IP. Please try again later.'
                    : 'নিরাপত্তাজনিত কারণে আপনার আইপি থেকে প্রতি ঘণ্টায় সর্বোচ্চ ৫টি বার্তা পাঠানো যাবে। অনুগ্রহ করে কিছুক্ষণ পর চেষ্টা করুন।';
                $this->session->set_flashdata('msg_error', $error_msg);
                redirect(current_url());
                return;
            }

            // 3. Form fields validation
            $this->form_validation->set_rules('name', ($current_lang == 'en' ? 'Full Name' : 'পূর্ণ নাম'), 'trim|required|min_length[2]|max_length[100]');
            $this->form_validation->set_rules('email', ($current_lang == 'en' ? 'Email Address' : 'ইমেইল ঠিকানা'), 'trim|required|valid_email|max_length[100]');
            $this->form_validation->set_rules('phoneno', ($current_lang == 'en' ? 'Phone Number' : 'ফোন নম্বর'), 'trim|required|min_length[6]|max_length[25]');
            $this->form_validation->set_rules('subject', ($current_lang == 'en' ? 'Subject' : 'বিষয়'), 'trim|required|min_length[2]|max_length[200]');
            $this->form_validation->set_rules('message', ($current_lang == 'en' ? 'Message' : 'বার্তা'), 'trim|required|min_length[5]|max_length[3000]');
            $this->form_validation->set_rules('math_captcha', ($current_lang == 'en' ? 'Security Captcha' : 'নিরাপত্তা ক্যাপচা'), 'trim|required');

            if ($this->form_validation->run() !== false) {
                // 4. Validate Math Captcha (support both Bangla and English digits)
                $raw_captcha = trim($this->input->post('math_captcha'));
                $bn_digits = ['০','১','২','৩','৪','৫','৬','৭','৮','৯'];
                $en_digits = ['0','1','2','3','4','5','6','7','8','9'];
                $normalized_captcha = str_replace($bn_digits, $en_digits, $raw_captcha);
                $user_captcha = is_numeric($normalized_captcha) ? (int)$normalized_captcha : -999;
                $session_captcha = $this->session->userdata('contact_math_captcha');

                if ($session_captcha === null || $user_captcha !== (int)$session_captcha) {
                    $error_msg = ($current_lang == 'en') 
                        ? 'Incorrect captcha answer! Please solve the math verification correctly.' 
                        : 'ক্যাপচা গণনা সঠিক হয়নি! দয়া করে সঠিক যোগফলটি লিখুন।';
                    $this->session->set_flashdata('msg_error', $error_msg);
                    redirect(current_url());
                    return;
                }

                // 5. Save message to database
                $messageData = array(
                    'branch_id'   => $branchID,
                    'name'        => $this->input->post('name', true),
                    'email'       => $this->input->post('email', true),
                    'phone'       => $this->input->post('phoneno', true),
                    'subject'     => $this->input->post('subject', true),
                    'message'     => $this->input->post('message', true),
                    'ip_address'  => $ip,
                    'user_agent'  => substr($this->input->user_agent(), 0, 255),
                    'status'      => 0, // unread
                    'created_at'  => date('Y-m-d H:i:s'),
                );
                $this->db->insert('front_cms_contact_messages', $messageData);

                // Update session cooldown and reset captcha to prevent replay
                $this->session->set_userdata('last_contact_submit_time', time());
                $this->session->unset_userdata('contact_math_captcha');

                // 6. Attempt optional email notification (failsafe)
                if (!empty($this->data['cms_setting']['receive_contact_email'])) {
                    try {
                        $emailMsg = "<h3>নতুন যোগাযোগ বার্তা (চর হাসান ইসলামিয়া দাখিল মাদরাসা)</h3>"
                                  . "<p><b>প্রেরকের নাম:</b> " . html_escape($messageData['name']) . "</p>"
                                  . "<p><b>ইমেইল:</b> " . html_escape($messageData['email']) . "</p>"
                                  . "<p><b>ফোন নম্বর:</b> " . html_escape($messageData['phone']) . "</p>"
                                  . "<p><b>বিষয়:</b> " . html_escape($messageData['subject']) . "</p>"
                                  . "<p><b>বার্তা:</b><br>" . nl2br(html_escape($messageData['message'])) . "</p>"
                                  . "<p><small>IP: {$ip} | Date: " . date('d M Y, h:i A') . "</small></p>";
                        $mailData = array(
                            'branch_id' => $branchID,
                            'recipient' => $this->data['cms_setting']['receive_contact_email'],
                            'subject'   => 'Contact Inquiry: ' . $messageData['subject'],
                            'message'   => $emailMsg,
                        );
                        @$this->mailer->send($mailData, true);
                    } catch (\Exception $e) {
                        // Email server may not be configured in local environment; message is safely recorded in DB.
                    }
                }

                $success_msg = ($current_lang == 'en')
                    ? 'Your message has been sent successfully! We will get in touch with you shortly.'
                    : 'আপনার বার্তাটি সফলভাবে পাঠানো হয়েছে! আমাদের প্রতিনিধি শীঘ্রই আপনার সাথে যোগাযোগ করবেন।';
                $this->session->set_flashdata('msg_success', $success_msg);
                redirect(current_url());
                return;
            } else {
                $this->session->set_flashdata('msg_error', validation_errors());
            }
        }

        // Generate Math Captcha numbers
        $num1 = rand(1, 9);
        $num2 = rand(1, 9);
        $this->session->set_userdata('contact_math_captcha', ($num1 + $num2));
        $this->data['captcha_num1'] = $num1;
        $this->data['captcha_num2'] = $num2;

        $this->data['branchID'] = $branchID;
        $this->data['page_data'] = $this->home_model->get('front_cms_contact', array('branch_id' => $branchID), true);
        $this->data['main_contents'] = $this->load->view('home/contact', $this->data, true);
        $this->load->view('home/layout/index', $this->data);
    }



    public function admit_card()

    {

        $branchID = $this->home_model->getDefaultBranch();

        $this->data['branchID'] = $branchID;

        $this->data['page_data'] = $this->home_model->get('front_cms_admitcard', array('branch_id' => $branchID), true);

        $this->data['main_contents'] = $this->load->view('home/admit_card', $this->data, true);

        $this->load->view('home/layout/index', $this->data);

    }



    public function admitCardprintFn()

    {

        if ($_POST) {

            $this->load->model('card_manage_model');

            $this->load->model('timetable_model');

            $this->load->library('ciqrcode', array('cacheable' => false));

            $this->form_validation->set_rules('exam_id', translate('exam'), 'trim|required');

            $this->form_validation->set_rules('register_no', translate('register_no'), 'trim|required');

            if ($this->form_validation->run() == true) {

                //get all QR Code file

                $files = glob('uploads/qr_code/*');

                foreach ($files as $file) {

                    if (is_file($file)) {

                        unlink($file); //delete file

                    }

                }

                $registerNo = $this->input->post('register_no');



                $sessionID = get_session_id();        

                $this->db->select('e.id');

                $this->db->from('enroll as e');

                $this->db->join('student as s', "e.student_id = s.id", 'inner');

                $this->db->where('e.session_id', $sessionID);

                $this->db->where('s.register_no', $registerNo);

                $this->db->where('s.active', 1);

                $userID = $this->db->get()->row_array();

                if (empty($userID)) {

                    $array = array('status' => '0', 'error' => "Register No Not Found.");

                    echo json_encode($array);

                    exit();

                }

                $templateID = $this->input->post('templete_id');

                if (empty($templateID) || $templateID == 0) {

                    $array = array('status' => '0', 'error' => "No Default Template Set.");

                    echo json_encode($array);

                    exit();

                }

                $this->data['exam_id'] = $this->input->post('exam_id');

                $this->data['userID'] = $userID;

                $this->data['template'] = $this->card_manage_model->get('card_templete', array('id' => $templateID), true);

                $this->data['print_date'] = date('Y-m-d');

                $card_data = $this->load->view('home/admitCardprintFn', $this->data, true);

                $array = array('status' => 'success', 'card_data' => $card_data);

            } else {

                $error = $this->form_validation->error_array();

                $array = array('status' => 'fail', 'error' => $error);

            }

            echo json_encode($array);

        }

    }



    public function exam_results()

    {

        $branchID = $this->home_model->getDefaultBranch();

        $this->data['branchID'] = $branchID;

        $this->data['page_data'] = $this->home_model->get('front_cms_exam_results', array('branch_id' => $branchID), true);

        $this->data['main_contents'] = $this->load->view('home/exam_results', $this->data, true);

        $this->load->view('home/layout/index', $this->data);

    }



    public function examResultsPrintFn()

    {

        $this->load->model('exam_model');

        if ($_POST) {

            $this->form_validation->set_rules('exam_id', translate('exam'), 'trim|required');

            $this->form_validation->set_rules('register_no', translate('register_no'), 'trim|required');

            $this->form_validation->set_rules('session_id', translate('academic_year'), 'trim|required');

            if ($this->form_validation->run() == true) {

                $sessionID = $this->input->post('session_id');

                $registerNo = $this->input->post('register_no');

                $examID = $this->input->post('exam_id');



                $this->db->select('student.id,enroll.class_id,enroll.section_id');

                $this->db->from('enroll');

                $this->db->join('student', 'student.id = enroll.student_id', 'inner');

                $this->db->join('login_credential', 'login_credential.user_id = student.id and login_credential.role = 7', 'inner');

                $this->db->where('student.register_no', $registerNo);

                $this->db->where('login_credential.active', 1);

                $this->db->where('enroll.session_id', $sessionID);

                $userID = $this->db->get()->row_array();



                if (empty($userID)) {

                    $array = array('status' => '0', 'error' => "Register No Not Found.");

                    echo json_encode($array);

                    exit();

                }

                $result = $this->exam_model->getStudentReportCard($userID['id'], $examID, $sessionID, $userID['class_id'], $userID['section_id']);

                if (empty($result['exam'])) {

                    $array = array('status' => '0', 'error' => "Exam Results Not Found.");

                    echo json_encode($array);

                    exit();

                }

                $this->data['result'] = $result;

                $this->data['sessionID'] = $sessionID;

                $this->data['userID'] = $userID['id'];

                $this->data['examID'] = $examID;

                $this->data['grade_scale'] = $this->input->post('grade_scale');

                $this->data['attendance'] = $this->input->post('attendance');

                $this->data['print_date'] = date('Y-m-d');

                $card_data = $this->load->view('home/reportCard', $this->data, true);

                $array = array('status' => 'success', 'card_data' => $card_data);

            } else {

                $error = $this->form_validation->error_array();

                $array = array('status' => 'fail', 'error' => $error);

            }

            echo json_encode($array);
        }
    }

    public function exam_routine()
    {
        $branchID = $this->home_model->getDefaultBranch();
        $this->data['branchID'] = $branchID;

        // Fetch CMS page record for exam-routine if available
        $this->db->select('front_cms_menu.title as menu_title,front_cms_menu.alias,front_cms_pages.*');
        $this->db->from('front_cms_menu');
        $this->db->join('front_cms_pages', 'front_cms_pages.menu_id = front_cms_menu.id', 'inner');
        $this->db->where('front_cms_menu.alias', 'exam-routine');
        $this->db->where('front_cms_menu.publish', 1);
        $this->data['page_data'] = $this->db->get()->row_array();

        // Get Exams list for this branch
        $sessionID = get_session_id();
        $this->data['exams'] = $this->db->select('exam.id, exam.name, exam.term_id')
            ->from('exam')
            ->where('branch_id', $branchID)
            ->where('session_id', $sessionID)
            ->where('status', 1)
            ->order_by('id', 'desc')
            ->get()->result_array();

        // Get Classes for this branch
        $this->data['classes'] = $this->db->select('id, name')
            ->where('branch_id', $branchID)
            ->order_by('id', 'asc')
            ->get('class')->result_array();

        // Handle search / filter
        $exam_id = $this->input->get_post('exam_id', true);
        $class_id = $this->input->get_post('class_id', true);
        $section_id = $this->input->get_post('section_id', true);

        $is_searched = (isset($_GET['exam_id']) || isset($_GET['class_id']) || isset($_POST['exam_id']) || isset($_POST['class_id']));

        $this->data['selected_exam'] = $exam_id;
        $this->data['selected_class'] = $class_id;
        $this->data['selected_section'] = $section_id;
        $this->data['is_searched'] = $is_searched;
        $this->data['timetable'] = array();

        if (!empty($exam_id) && !empty($class_id)) {
            $this->db->select('timetable_exam.*, subject.name as subject_name, subject.subject_code, subject.subject_type, class.name as class_name, section.name as section_name');
            $this->db->from('timetable_exam');
            $this->db->join('subject', 'subject.id = timetable_exam.subject_id', 'left');
            $this->db->join('class', 'class.id = timetable_exam.class_id', 'left');
            $this->db->join('section', 'section.id = timetable_exam.section_id', 'left');
            $this->db->where('timetable_exam.exam_id', $exam_id);
            $this->db->where('timetable_exam.class_id', $class_id);
            if (!empty($section_id)) {
                $this->db->where('timetable_exam.section_id', $section_id);
            }
            $this->db->order_by('timetable_exam.exam_date', 'asc');
            $this->db->order_by('timetable_exam.time_start', 'asc');
            $this->data['timetable'] = $this->db->get()->result_array();
        }

        $this->data['active_menu'] = 'exam-routine';
        $this->data['main_contents'] = $this->load->view('home/exam_routine', $this->data, true);
        $this->load->view('home/layout/index', $this->data);
    }

    public function certificates()

    {

        $branchID = $this->home_model->getDefaultBranch();

        $this->data['branchID'] = $branchID;

        $this->data['page_data'] = $this->home_model->get('front_cms_certificates', array('branch_id' => $branchID), true);

        $this->data['main_contents'] = $this->load->view('home/certificates', $this->data, true);

        $this->load->view('home/layout/index', $this->data);

    }



    public function certificatesPrintFn()

    {

        if ($_POST) {

            $this->load->model('certificate_model');

            $this->load->library('ciqrcode', array('cacheable' => false));

            //get all QR Code file

            $files = glob('uploads/qr_code/*');

            foreach ($files as $file) {

                if (is_file($file)) {

                    unlink($file); //delete file

                }

            }



            $this->form_validation->set_rules('templete_id', translate('certificate'), 'trim|required');

            $this->form_validation->set_rules('register_no', translate('register_no'), 'trim|required');

            if ($this->form_validation->run() == true) {



                $registerNo = $this->input->post('register_no');

                $examID = $this->input->post('exam_id');

                $userID = $this->db->select('id')->where(['register_no' => $registerNo, 'active' => 1])->get('student')->row_array();

                if (empty($userID)) {

                    $array = array('status' => '0', 'error' => "Register No Not Found.");

                    echo json_encode($array);

                    exit();

                }



                $this->data['user_type'] = 1;

                $templateID = $this->input->post('templete_id');

                $this->data['template'] = $this->certificate_model->get('certificates_templete', array('id' => $templateID), true);

                $this->data['userID'] = $userID['id'];

                $this->data['print_date'] = date('Y-m-d');

                $card_data = $this->load->view('home/certificatesPrintFn', $this->data, true);

                $array = array('status' => 'success', 'card_data' => $card_data);

            } else {

                $error = $this->form_validation->error_array();

                $array = array('status' => 'fail', 'error' => $error);

            }

            echo json_encode($array);

        }

    }



    public function gallery()
    {
        $branchID = $this->home_model->getDefaultBranch();
        $this->data['branchID'] = $branchID;
        $this->data['page_data'] = $this->home_model->get('front_cms_gallery', array('branch_id' => $branchID), true);

        // Fetch categories
        $this->data['category'] = $this->db->get_where('front_cms_gallery_category', array('branch_id' => $branchID))->result_array();

        // Fetch photo albums (file_type = 'image')
        $this->data['albums'] = $this->home_model->getGalleryList($branchID, null, 'image');

        // Total counts for tabs
        $this->data['total_photos_count'] = count($this->data['albums']);
        $this->data['total_videos_count'] = $this->db->where('branch_id', $branchID)->where('file_type', 'video')->where('show_web', 1)->count_all_results('front_cms_gallery_content');

        $this->data['active_menu'] = 'gallery';
        $this->data['main_contents'] = $this->load->view('home/gallery', $this->data, true);
        $this->load->view('home/layout/index', $this->data);
    }

    public function video_gallery()
    {
        $branchID = $this->home_model->getDefaultBranch();
        $this->data['branchID'] = $branchID;
        
        // Page data from front_cms_home (video_gallery) or front_cms_gallery
        $video_sec = $this->home_model->getCmsHome('video_gallery', $branchID, null);
        $page_data = $this->home_model->get('front_cms_gallery', array('branch_id' => $branchID), true);
        if (!empty($video_sec)) {
            $page_data['page_title'] = !empty($video_sec['title']) ? $video_sec['title'] : 'ভিডিও গ্যালারি';
            $page_data['page_title_en'] = !empty($video_sec['title_en']) ? $video_sec['title_en'] : 'Video Gallery';
            $page_data['subtitle'] = !empty($video_sec['subtitle']) ? $video_sec['subtitle'] : '';
            $page_data['subtitle_en'] = !empty($video_sec['subtitle_en']) ? $video_sec['subtitle_en'] : '';
        } else {
            $page_data['page_title'] = 'ভিডিও গ্যালারি';
            $page_data['page_title_en'] = 'Video Gallery';
        }
        $this->data['page_data'] = $page_data;

        // Categories
        $this->data['category'] = $this->db->get_where('front_cms_gallery_category', array('branch_id' => $branchID))->result_array();

        // Videos
        $this->data['videos'] = $this->home_model->getVideoList($branchID);

        // Total counts for tabs
        $this->data['total_photos_count'] = $this->db->where('branch_id', $branchID)->where('file_type', 'image')->where('show_web', 1)->count_all_results('front_cms_gallery_content');
        $this->data['total_videos_count'] = count($this->data['videos']);

        $this->data['active_menu'] = 'video-gallery';
        $this->data['main_contents'] = $this->load->view('home/video_gallery', $this->data, true);
        $this->load->view('home/layout/index', $this->data);
    }

    public function gallery_view($alias = '')
    {
        $branchID = $this->home_model->getDefaultBranch();
        $this->data['branchID'] = $branchID;
        $this->data['page_data'] = $this->home_model->get('front_cms_gallery', array('branch_id' => $branchID), true);

        $album = null;
        if (is_numeric($alias)) {
            $album = $this->db->select('front_cms_gallery_content.*, front_cms_gallery_category.name as category_name, front_cms_gallery_category.name_en as category_name_en')
                ->from('front_cms_gallery_content')
                ->join('front_cms_gallery_category', 'front_cms_gallery_category.id = front_cms_gallery_content.category_id', 'left')
                ->where('front_cms_gallery_content.id', (int)$alias)
                ->where('front_cms_gallery_content.branch_id', $branchID)
                ->get()->row_array();
        }
        if (empty($album)) {
            $album = $this->db->select('front_cms_gallery_content.*, front_cms_gallery_category.name as category_name, front_cms_gallery_category.name_en as category_name_en')
                ->from('front_cms_gallery_content')
                ->join('front_cms_gallery_category', 'front_cms_gallery_category.id = front_cms_gallery_content.category_id', 'left')
                ->where('front_cms_gallery_content.alias', urldecode($alias))
                ->where('front_cms_gallery_content.branch_id', $branchID)
                ->get()->row_array();
        }

        // If album still not found, check if $alias is a category_id and get first album in category
        if (empty($album) && is_numeric($alias)) {
            $album = $this->db->select('front_cms_gallery_content.*, front_cms_gallery_category.name as category_name, front_cms_gallery_category.name_en as category_name_en')
                ->from('front_cms_gallery_content')
                ->join('front_cms_gallery_category', 'front_cms_gallery_category.id = front_cms_gallery_content.category_id', 'left')
                ->where('front_cms_gallery_content.category_id', (int)$alias)
                ->where('front_cms_gallery_content.branch_id', $branchID)
                ->where('front_cms_gallery_content.show_web', 1)
                ->get()->row_array();
        }

        if (empty($album)) {
            redirect(base_url('gallery'));
            return;
        }

        $this->data['album'] = $album;
        $this->data['active_menu'] = 'gallery';
        $this->data['main_contents'] = $this->load->view('home/gallery_view', $this->data, true);
        $this->load->view('home/layout/index', $this->data);
    }



    public function history()

    {

        $branchID = $this->home_model->getDefaultBranch();

        $this->db->select('front_cms_menu.title as menu_title,front_cms_menu.alias,front_cms_pages.*');

        $this->db->from('front_cms_menu');

        $this->db->join('front_cms_pages', 'front_cms_pages.menu_id = front_cms_menu.id', 'inner');

        $this->db->where('front_cms_menu.alias', 'history');

        $this->db->where('front_cms_menu.publish', 1);

        $getData = $this->db->get()->row_array();



        if (empty($getData)) {

            $getData = $this->db->get_where('front_cms_pages', array('menu_id' => 18))->row_array();

            if (empty($getData)) {

                $getData = $this->db->get_where('front_cms_pages', array('id' => 3))->row_array();

            }

        }



        $this->data['branchID'] = !empty($getData['branch_id']) ? $getData['branch_id'] : $branchID;

        $this->data['page_data'] = $getData;

        $this->data['active_menu'] = 'history';

        $this->data['main_contents'] = $this->load->view('home/history', $this->data, true);

        $this->load->view('home/layout/index', $this->data);

    }



    public function principle_message()

    {

        $branchID = $this->home_model->getDefaultBranch();

        $this->db->select('front_cms_menu.title as menu_title,front_cms_menu.alias,front_cms_pages.*');

        $this->db->from('front_cms_menu');

        $this->db->join('front_cms_pages', 'front_cms_pages.menu_id = front_cms_menu.id', 'left');

        $this->db->where('front_cms_menu.alias', 'principle-message');

        $this->db->where('front_cms_menu.publish', 1);

        $getData = $this->db->get()->row_array();



        if (empty($getData)) {

            $getData = $this->db->get_where('front_cms_pages', array('menu_id' => 19))->row_array();

            if (empty($getData)) {

                $getData = $this->db->get_where('front_cms_pages', array('id' => 4))->row_array();

            }

        }



        $this->data['branchID'] = !empty($getData['branch_id']) ? $getData['branch_id'] : $branchID;

        $this->data['page_data'] = $getData;

        $this->data['active_menu'] = 'principle-message';

        $this->data['main_contents'] = $this->load->view('home/principle_message', $this->data, true);

        $this->load->view('home/layout/index', $this->data);

    }



    public function governing_body()

    {

        $branchID = $this->home_model->getDefaultBranch();

        $this->db->select('front_cms_menu.title as menu_title,front_cms_menu.alias,front_cms_pages.*');

        $this->db->from('front_cms_menu');

        $this->db->join('front_cms_pages', 'front_cms_pages.menu_id = front_cms_menu.id', 'left');

        $this->db->group_start();
        $this->db->where('front_cms_menu.alias', 'committee');
        $this->db->or_where('front_cms_menu.alias', 'governing-body');
        $this->db->or_where('front_cms_menu.alias', 'governing_body');
        $this->db->group_end();

        $this->db->where('front_cms_menu.publish', 1);

        $getData = $this->db->get()->row_array();



        if (empty($getData)) {

            $getData = $this->db->get_where('front_cms_pages', array('menu_id' => 15))->row_array();

            if (empty($getData)) {

                $getData = $this->db->get_where('front_cms_pages', array('id' => 1))->row_array();

            }

        }



        // Fetch founders dynamically from founder table
        $this->db->order_by('position', 'asc');
        $this->db->order_by('id', 'asc');
        $this->db->where('active', 1);
        $members = $this->db->get('founder')->result_array();

        if (empty($members)) {
            $this->db->order_by('rank', 'asc');
            $members = $this->db->get_where('front_cms_testimonial', array('branch_id' => $branchID))->result_array();
        }



        $this->data['branchID'] = !empty($getData['branch_id']) ? $getData['branch_id'] : $branchID;

        $this->data['page_data'] = $getData;

        $this->data['members'] = $members;

        $this->data['active_menu'] = 'governing-body';

        $this->data['main_contents'] = $this->load->view('home/governing_body', $this->data, true);

        $this->load->view('home/layout/index', $this->data);

    }



    public function message_founder($id = '')
    {
        $branchID = $this->home_model->getDefaultBranch();

        $founder = [];
        if ($this->db->table_exists('founder') && !empty($id)) {
            $founder = $this->db->get_where('founder', array('id' => $id, 'branch_id' => $branchID, 'active' => 1))->row_array();
            if (empty($founder)) {
                $founder = $this->db->get_where('founder', array('id' => $id, 'active' => 1))->row_array();
            }
            if (empty($founder)) {
                $founder = $this->db->get_where('founder', array('position' => $id, 'branch_id' => $branchID, 'active' => 1))->row_array();
            }
        }

        if (empty($founder)) {
            redirect(base_url());
            return;
        }

        $this->data['branchID'] = $branchID;
        $this->data['founder'] = $founder;
        $this->data['active_menu'] = 'governing-body';

        $page_name = !empty($founder['name_bn']) ? $founder['name_bn'] : (!empty($founder['name_en']) ? $founder['name_en'] : 'Message');
        $page_title = !empty($founder['designation_bn']) ? $founder['designation_bn'] : (!empty($founder['designation_en']) ? $founder['designation_en'] : (!empty($founder['title_bn']) ? $founder['title_bn'] : 'Leadership'));
        $this->data['page_data'] = [
            'page_title' => $page_name,
            'title' => $page_title,
        ];
        $this->data['main_contents'] = $this->load->view('home/message_founder', $this->data, true);
        $this->load->view('home/layout/index', $this->data);
    }



    public function calendar_school()
    {
        $branchID = $this->home_model->getDefaultBranch();
        $this->data['branchID'] = $branchID;
        $this->data['page_data'] = [
            'page_title' => 'একাডেমিক ক্যালেন্ডার (স্কুল)',
            'page_title_en' => 'Academic Calendar (School)',
            'title' => 'সাবস্ক্রাইব করা শিক্ষাবর্ষের একাডেমিক ক্যালেন্ডার',
            'title_en' => 'Academic Calendar of the subscribed academic year'
        ];
        $this->data['active_menu'] = 'calendar-school';
        $this->data['main_contents'] = $this->load->view('home/calendar_school', $this->data, true);
        $this->load->view('home/layout/index', $this->data);
    }



    public function staff()

    {

        $branchID = $this->home_model->getDefaultBranch();

        $this->db->select('front_cms_menu.title as menu_title,front_cms_menu.alias,front_cms_pages.*');

        $this->db->from('front_cms_menu');

        $this->db->join('front_cms_pages', 'front_cms_pages.menu_id = front_cms_menu.id', 'left');

        $this->db->where('front_cms_menu.alias', 'staff');

        $this->db->where('front_cms_menu.publish', 1);

        $getData = $this->db->get()->row_array();



        if (empty($getData)) {

            $getData = $this->db->get_where('front_cms_pages', array('menu_id' => 26))->row_array();

        }



        // Fetch non-teacher staff members dynamically

        $this->db->select('staff.*, staff_designation.name as designation_name, staff_department.name as department_name, roles.name as role_name');

        $this->db->from('staff');

        $this->db->join('login_credential', 'login_credential.user_id = staff.id and login_credential.role != 7', 'inner');

        $this->db->join('roles', 'roles.id = login_credential.role', 'left');

        $this->db->join('staff_designation', 'staff_designation.id = staff.designation', 'left');

        $this->db->join('staff_department', 'staff_department.id = staff.department', 'left');

        $this->db->where('login_credential.role !=', 3);

        $this->db->where('login_credential.active', 1);

        $this->db->where('staff.branch_id', $branchID);

        $this->db->order_by('staff.id', 'asc');

        $staff_list = $this->db->get()->result_array();



        $this->data['branchID'] = !empty($getData['branch_id']) ? $getData['branch_id'] : $branchID;

        $this->data['page_data'] = $getData;

        $this->data['staff_list'] = $staff_list;

        $this->data['active_menu'] = 'staff';

        $this->data['main_contents'] = $this->load->view('home/staff', $this->data, true);

        $this->load->view('home/layout/index', $this->data);

    }



    public function recognition()

    {

        $branchID = $this->home_model->getDefaultBranch();

        $this->db->select('front_cms_menu.title as menu_title,front_cms_menu.title_en as menu_title_en,front_cms_menu.alias,front_cms_pages.*');

        $this->db->from('front_cms_menu');

        $this->db->join('front_cms_pages', 'front_cms_pages.menu_id = front_cms_menu.id', 'inner');

        $this->db->where('front_cms_menu.alias', 'recognition');

        $this->db->where('front_cms_menu.publish', 1);

        $getData = $this->db->get()->row_array();



        if (empty($getData)) {

            $getData = $this->db->get_where('front_cms_pages', array('menu_id' => 16))->row_array();

            if (empty($getData)) {

                $getData = $this->db->get_where('front_cms_pages', array('id' => 2))->row_array();

            }

        }



        $this->data['branchID'] = !empty($getData['branch_id']) ? $getData['branch_id'] : $branchID;

        $this->data['page_data'] = $getData;

        $this->data['active_menu'] = 'recognition';

        $this->data['main_contents'] = $this->load->view('home/recognition', $this->data, true);

        $this->load->view('home/layout/index', $this->data);

    }



    public function page($url = '')

    {

        $alias = urldecode($url);

        if ($alias == 'history') {

            $this->history();

            return;

        }

        if ($alias == 'recognition' || $alias == 'accreditation') {

            $this->recognition();

            return;

        }

        if ($alias == 'principle-message' || $alias == 'principal-message' || $alias == 'superintendent-message') {

            $this->principle_message();

            return;

        }

        if ($alias == 'governing-body' || $alias == 'governing_body' || $alias == 'committee') {

            $this->governing_body();

            return;

        }

        if ($alias == 'staff') {
            $this->staff();
            return;
        }

        if ($alias == 'exam-routine' || $alias == 'exam_routine') {
            $this->exam_routine();
            return;
        }

        if ($alias == 'gallery' || $alias == 'photo-gallery') {
            $this->gallery();
            return;
        }

        if ($alias == 'video-gallery' || $alias == 'video_gallery') {
            $this->video_gallery();
            return;
        }



        $this->db->select('front_cms_menu.title as menu_title,front_cms_menu.alias,front_cms_pages.*');

        $this->db->from('front_cms_menu');

        $this->db->join('front_cms_pages', 'front_cms_pages.menu_id = front_cms_menu.id', 'inner');

        $this->db->where('front_cms_menu.alias', urldecode($url));

        $this->db->where('front_cms_menu.publish', 1);

        $getData = $this->db->get()->row_array();

        $this->data['branchID'] = $getData['branch_id'];

      

        $this->data['page_data'] = $getData;

        $this->data['active_menu'] = 'page';

        $this->data['main_contents'] = $this->load->view('home/page', $this->data, true);

        $this->load->view('home/layout/index', $this->data);

    }



    public function getSectionByClass()

    {

        $html = "";

        $classID = $this->input->post("class_id");

        if (!empty($classID)) {

            $result = $this->db->select('sections_allocation.section_id,section.name')

                ->from('sections_allocation')

                ->join('section', 'section.id = sections_allocation.section_id', 'left')

                ->where('sections_allocation.class_id', $classID)

                ->get()->result_array();

            if (is_array($result) && count($result)) {

                $html .= '<option value="">' . translate('select') . '</option>';

                foreach ($result as $row) {

                    $sec_title = (function_exists('dvs_clean_bilingual') ? dvs_clean_bilingual($row['name'], (function_exists('get_current_lang') && get_current_lang() == 'en') ? 'english' : 'bengali') : $row['name']);
                    $html .= '<option value="' . $row['section_id'] . '">' . $sec_title . '</option>';

                }

            } else {

                $html .= '<option value="">' . translate('no_selection_available') . '</option>';

            }

        } else {

            $html .= '<option value="">' . translate('select_class_first') . '</option>';

        }

        echo $html;

    }



    public function get_branch_url()

    {

        $branch_id = $this->input->post("branch_id");

        $url = $this->db->select('url_alias')->where('branch_id', $branch_id)->get('front_cms_setting')->row_array();

        $school = "";

        if ($this->uri->segment(4)) {

            $school = $this->uri->segment(4);

        } else {

            $school = $this->uri->segment(3);

        }

        echo json_encode(array('url_alias' => base_url($url['url_alias'])));

    }



    public function news()

    {

        $branchID = $this->home_model->getDefaultBranch();

        $url_alias = $this->data['cms_setting']['url_alias'];

        $getLatestNewsList = $this->home_model->getLatestNewsList($branchID);

        $page = empty($this->input->get('page')) ? 0 : html_escape(urldecode($this->input->get('page')));

        if (is_numeric($page)) {

            $page = !empty($page) ? $page : 0;

        } else {

            $page = 0;

        }

        if (!empty($getLatestNewsList)) {

            $total_records = count($getLatestNewsList);

        } else {

            $total_records = 0;

        }

        $config = array();

        $config['page_query_string'] = TRUE;

        $config['query_string_segment'] = 'page';

        $config["base_url"] = base_url() . $url_alias . '/news';

        $config["total_rows"] = $total_records;

        $config["per_page"] = 12;

        $config['full_tag_open'] = '<ul class="pagination justify-content-center">';

        $config['full_tag_close'] = '</ul>';

        $config['first_link'] = '<i class="fas fa-angle-double-left"></i>';

        $config['first_tag_open'] = '<li class="previous">';

        $config['first_tag_close'] = '</li>';

        $config['last_link'] = '<i class="fas fa-angle-double-right"></i>';

        $config['last_tag_open'] = '<li class="next">';

        $config['last_tag_close'] = '</li>';

        $config['next_link'] = '<i class="fas fa-angle-right"></i>';

        $config['next_tag_open'] = '<li class="next">';

        $config['next_tag_close'] = '</li>';

        $config['prev_link'] = '<i class="fas fa-angle-left"></i>';

        $config['prev_tag_open'] = '<li class="previous">';

        $config['prev_tag_close'] = '</li>';

        $config['cur_tag_open'] = '<li class="active"><span>';

        $config['cur_tag_close'] = '</span></li>';

        $config['num_tag_open'] = '<li>';

        $config['num_tag_close'] = '</il>';

        $this->pagination->initialize($config);

        $conditions['limit'] = $config["per_page"];

        $conditions['start'] = $page;

        $this->data["links"] = $this->pagination->create_links();

        $this->data["results"] = $this->home_model->getLatestNewsList($branchID, $conditions);

        $this->data['branchID'] = $branchID;

        $this->data['page_data'] = $this->home_model->get('front_cms_news', array('branch_id' => $branchID), true);

        $this->data['main_contents'] = $this->load->view('home/news', $this->data, true);

        $this->load->view('home/layout/index', $this->data);

    }



    // download online admission application form

    public function download_application_form($value='')

    {

        $this->load->helpers('download');

        $branchID = $this->security->xss_clean($value);

        $branchID = base64_decode(str_pad(strtr($branchID, '-_', '+/'), strlen($branchID) % 4, '=', STR_PAD_RIGHT));

        if (!empty($branchID)) {

            $row = $this->db->select('application_form_name,application_form_file')->where('branch_id', $branchID)->get('front_cms_admission')->row();

            if (!empty($row->application_form_file)) {

                force_download($row->application_form_name, file_get_contents('uploads/admission_form/' . $row->application_form_file));

            }

        }  

    }

}
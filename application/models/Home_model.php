<?php

defined('BASEPATH') or exit('No direct script access allowed');



class Home_model extends MY_Model

{

    public function __construct()

    {

        parent::__construct();

    }



    public function getDefaultBranch()

    {

        $saasExisting = $this->app_lib->isExistingAddon('saas');

        if ($saasExisting && $this->db->table_exists("custom_domain")) {

            $getDomain = $this->getCurrentDomain();

            if(!empty($getDomain)) {

                return $getDomain->school_id; 

            } else {

                $school = "";

                $school = $this->uri->segment(1);

                $row = $this->db->select('branch_id')->get_where('front_cms_setting', array('url_alias' => $school))->row_array();

                if (empty($row) || $row['branch_id'] == 0) {

                    return $this->getCMSdefault();

                } else {

                    return $row['branch_id'];

                }

            }

        } else {

            $school = "";

            $school = $this->uri->segment(1);

            $row = $this->db->select('branch_id')->get_where('front_cms_setting', array('url_alias' => $school))->row_array();

            if (empty($row) || $row['branch_id'] == 0) {

                return $this->getCMSdefault();

            } else {

                return $row['branch_id'];

            }

        }

    }



    public function getCmsHome($item_type, $branch_id, $active = 1, $single = true)
    {
        $this->db->select('*');
        $this->db->from('front_cms_home');
        if ($active !== null && $active !== false && $active !== '') {
            $this->db->where('active', $active);
        }
        $this->db->where('branch_id', $branch_id);
        $this->db->where('item_type', $item_type);
        $query = $this->db->get();
        if ($single == true) {
            $method = "row_array";
        } else {
            $this->db->order_by("id", "asc");
            $method = "result_array";
        }
        return $query->$method();
    }



    public function whatsappChat()

    {

        $branchID = $this->getDefaultBranch();

        $this->db->select("*");

        $this->db->where('branch_id', $branchID);

        $this->db->limit(1);

        $r = $this->db->get('whatsapp_chat')->row_array();

        return $r;

    }



    public function whatsappAgent()

    {

        $branchID = $this->getDefaultBranch();

        $this->db->select("*");

        $this->db->where("enable", 1);

        $this->db->where('branch_id',$branchID);

        $r = $this->db->get('whatsapp_agent')->result();

        return $r;

    }



    public function get_teacher_list($start = '', $branch_id = '')

    {

        $this->db->select('staff.*,staff_designation.name as designation_name,staff_department.name as department_name');

        $this->db->from('staff');

        $this->db->join('login_credential', 'login_credential.user_id = staff.id and login_credential.role != 7', 'inner');

        $this->db->join('staff_designation', 'staff_designation.id = staff.designation', 'left');

        $this->db->join('staff_department', 'staff_department.id = staff.department', 'left');

        $this->db->where_in('login_credential.role', array(3, 6));

        $this->db->where('login_credential.active', 1);

        $this->db->where('staff.branch_id', $branch_id);

        $this->db->group_by('staff.id');

        $this->db->order_by('staff.id', 'asc');

        if ($start != '') {

            $this->db->limit(4, $start);

        }

        $result = $this->db->get()->result_array();

        return $result;

    }



    public function get_teacher_departments($branch_id)

    {

        $this->db->select('staff_department.id as department_id,staff_department.name as department_name');

        $this->db->from('staff_department');

        $this->db->join('staff', 'staff.department = staff_department.id', 'left');

        $this->db->join('login_credential', 'login_credential.user_id = staff.id and login_credential.role != 7', 'inner');

        $this->db->where('login_credential.role', 3);

        $this->db->where('login_credential.active', 1);

        $this->db->where('staff_department.branch_id', $branch_id);

        $this->db->group_by('staff_department.id');

        $this->db->order_by('staff.id', 'asc');

        $result = $this->db->get()->result_array();

        return $result;

    }



    public function branch_list()

    {

        $this->db->select('b.school_name,b.id');

        $this->db->from('branch as b');

        $this->db->join('front_cms_setting as f', 'f.branch_id = b.id', 'inner');

        $this->db->where('f.cms_active', 1);

        $result = $this->db->get()->result();

        $arrayData = array();

        foreach ($result as $row) {

            $arrayData[$row->id] = $row->school_name;

        }

        return $arrayData;

    }



    public function menuList($school = '', $branchID = '')

    {

        $mainMenu = array();

        $subMenu = array();

        $mergeMenu = array();

        if (empty($branchID)) {

            $branchID = $this->getDefaultBranch();

        }

        if (empty($school)) {

            $cms_setting = $this->db->select('url_alias')->get_where('front_cms_setting', array('branch_id' => $branchID))->row();

            $school = $cms_setting->url_alias;

        }

        $this->db->select('front_cms_menu.*,if(mv.name is null, front_cms_menu.title, mv.name) as title,if(mv.parent_id is null, front_cms_menu.parent_id, mv.parent_id) as parent_id,if(mv.ordering is null, front_cms_menu.ordering, mv.ordering) as ordering,mv.invisible');

        $this->db->from('front_cms_menu');

        $this->db->join('front_cms_menu_visible as mv', 'mv.menu_id = front_cms_menu.id and mv.branch_id = ' . $branchID, 'left');

        $this->db->where('front_cms_menu.publish', 1);

        $this->db->where_in('front_cms_menu.branch_id', array(0, $branchID));

        $result = $this->db->get()->result_array();

        //php array sort

        array_multisort(array_column($result, 'ordering'), SORT_ASC, SORT_NUMERIC, $result);

        foreach ($result as $key => $value) {

            if ($value['invisible'] == 0) {

                if ($value['parent_id'] == 0) {

                    $mainMenu[$key] = $value;

                } else {

                    $subMenu[$key] = $value;

                }

            }

        }



        foreach ($mainMenu as $key => $value) {
            $value['url'] = $this->genURL($value, $school);
            $value['submenu'] = array();
            foreach ($subMenu as $key2 => $value2) {
                if ($value['id'] == $value2['parent_id']) {
                    $value['submenu'][] = array(
                        'id' => $value2['id'],
                        'title' => $value2['title'],
                        'alias' => $value2['alias'],
                        'ordering' => $value2['ordering'],
                        'open_new_tab' => $value2['open_new_tab'],
                        'url' => $this->genURL($value2, $school),
                    );
                }
            }
            $mergeMenu[] = $value;
        }

        return $mergeMenu;
    }

    public function genURL($array = array(), $school = '')
    {
        $url = "#";
        $defaultBranch = $this->getDefaultBranch();

        if ($school == 'cms' || $school == '/cms' || empty($school)) {
            $prefix = '';
        } else {
            $prefix = trim($school, '/') . '/';
        }

        $saasExisting = $this->app_lib->isExistingAddon('saas');
        if ($saasExisting && $this->db->table_exists("custom_domain")) {
            $getDomain = $this->getCurrentDomain();
            if (!empty($getDomain)) {
                $prefix = "";
            }
        }

        if (!empty($array['ext_url'])) {
            $url = $array['ext_url_address'];
        } elseif (!empty($array['system']) && $array['alias'] !== 'pages') {
            $url = empty($array['alias']) ? base_url($prefix) : base_url($prefix . $array['alias']);
        } else {
            if ($array['alias'] == 'exam-routine' || $array['alias'] == 'exam_routine') {
                $url = base_url($prefix . 'exam-routine');
            } else {
                $url = base_url($prefix . 'page/' . $array['alias']);
            }
        }

        return $url;
    }



    public function getExamList($branchID = '', $classID = '', $sectionID = '')

    {

        $sessionID = get_session_id();

        $this->db->select('exam.id,exam.name,exam.term_id');

        $this->db->from('timetable_exam');

        $this->db->join('exam', 'exam.id = timetable_exam.exam_id', 'left');

        if (!empty($classID)) {

            $this->db->where('timetable_exam.class_id', $classID);

        }

        if (!empty($sectionID)) {

            $this->db->where('timetable_exam.section_id', $sectionID);

        }

        $this->db->where('exam.status', 1);

        $this->db->where('exam.publish_result', 1);

        $this->db->where('timetable_exam.branch_id', $branchID);

        $this->db->where('timetable_exam.session_id', $sessionID);

        $this->db->group_by('timetable_exam.exam_id');

        $result = $this->db->get()->result_array();

        return $result;

    }



    public function getGalleryCategory($branch_id)
    {
        $this->db->select('front_cms_gallery_category.id as category_id,front_cms_gallery_category.name as category_name,front_cms_gallery_category.name_en as category_name_en,COUNT(front_cms_gallery_content.id) as photo_count,MAX(front_cms_gallery_content.thumb_image) as cover_image');
        $this->db->from('front_cms_gallery_category');
        $this->db->join('front_cms_gallery_content', 'front_cms_gallery_content.category_id = front_cms_gallery_category.id', 'inner');
        $this->db->where('front_cms_gallery_category.branch_id', $branch_id);
        $this->db->where('front_cms_gallery_content.show_web', 1);
        $this->db->where('front_cms_gallery_content.file_type', 'image');
        $this->db->group_by('front_cms_gallery_category.id');
        $this->db->order_by('front_cms_gallery_category.id', 'asc');
        $result = $this->db->get()->result_array();
        if (empty($result)) {
            $count = $this->db->where('branch_id', $branch_id)->where('show_web', 1)->where('file_type', 'image')->count_all_results('front_cms_gallery_content');
            if ($count > 0) {
                $result[] = [
                    'category_id' => 1,
                    'category_name' => 'Science Fair',
                    'category_name_en' => 'Science Fair',
                    'photo_count' => $count,
                    'cover_image' => 'dOE0oYuYQRh3bDbaMw6J9JQyEMO884GAv5lQ5YGq.jpg'
                ];
            }
        }
        return $result;
    }

    public function getGalleryList($branch_id, $category_id = null, $file_type = null)
    {
        $this->db->select('front_cms_gallery_content.*,staff.name as staff_name,front_cms_gallery_category.name as category_name,front_cms_gallery_category.name_en as category_name_en');
        $this->db->from('front_cms_gallery_content');
        $this->db->join('staff', 'staff.id = front_cms_gallery_content.added_by', 'left');
        $this->db->join('front_cms_gallery_category', 'front_cms_gallery_category.id = front_cms_gallery_content.category_id', 'left');
        $this->db->where('front_cms_gallery_content.branch_id', $branch_id);
        $this->db->where('front_cms_gallery_content.show_web', 1);
        if (!empty($category_id)) {
            $this->db->where('front_cms_gallery_content.category_id', (int)$category_id);
        }
        if (!empty($file_type)) {
            $this->db->where('front_cms_gallery_content.file_type', $file_type);
        }
        $this->db->order_by('front_cms_gallery_content.id', 'asc');
        $result = $this->db->get()->result_array();
        return $result;
    }



    public function getStatisticsCounter($type, $branch_id)

    {

        $result = 0;

        if ($type == 'class' || $type == 'section' || $type == 'live_class' || $type == 'subject' || $type == 'exam' || $type == 'book' || $type == 'branch') {

            $this->db->select('id');

            $this->db->from($type);

            if ($type != 'branch') {

                $this->db->where("branch_id", $branch_id);

            }

            $q = $this->db->get()->num_rows();

            $result = $q;

        }

        if ($type == 'employees' || $type == 'teacher') {

            $this->db->select('count(staff.id) as snumber');

            $this->db->from('staff');

            $this->db->join('login_credential', 'login_credential.user_id = staff.id', 'inner');

            $this->db->where_not_in('login_credential.role', 1);

            if ($type == 'teacher') {

                $this->db->where('login_credential.role', 3);

            } else {

                $this->db->where_not_in('login_credential.role', array(1, 6, 7));

            }

            $this->db->where('staff.branch_id', $branch_id);

            $q = $this->db->get()->row_array();

            $result = $q['snumber'];

        }

        if ($type == 'student') {

            $this->db->select('student.id');

            $this->db->from('enroll');

            $this->db->join('student', 'student.id = enroll.student_id', 'inner');

            $this->db->where('enroll.branch_id', $branch_id);

            $result = $this->db->get()->num_rows();

        }

        if ($type == 'parent') {
            // Count distinct parents who have enrolled active students in this branch
            $this->db->select('COUNT(DISTINCT student.parent_id) as snumber');
            $this->db->from('enroll');
            $this->db->join('student', 'student.id = enroll.student_id', 'inner');
            $this->db->join('parent', 'parent.id = student.parent_id', 'inner');
            $this->db->where('enroll.branch_id', $branch_id);
            $this->db->where('student.active', 1);
            $q = $this->db->get()->row_array();
            $result = (!empty($q['snumber']) && $q['snumber'] > 0) ? (int)$q['snumber'] : 0;
            
            if ($result === 0) {
                // Fallback to direct parent records for this branch
                $this->db->select('count(parent.id) as snumber');
                $this->db->from('parent');
                $this->db->where('parent.branch_id', $branch_id);
                $q2 = $this->db->get()->row_array();
                $result = !empty($q2['snumber']) ? (int)$q2['snumber'] : 0;
            }
        }

        return $result;

    }



    public function getPaymentConfig($branchID)

    {

        $this->db->where('branch_id', $branchID);

        $this->db->select('*')->from('payment_config');

        return $this->db->get()->row_array();

    }



    public function getCurrentDomain()

    {

        $url = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";

        $url = rtrim($url, '/');

        $domain =  parse_url($url, PHP_URL_HOST);

        $getDomain = $this->db->select('school_id')->get_where('custom_domain', array('status' => 1, 'url' => $domain))->row();

        return $getDomain;

    }



    public function getCMSdefault()

    {  

        $this->db->select('cms_default_branch');

        $this->db->where('id', 1);

        $row = $this->db->get('global_settings')->row_array();

        return $row['cms_default_branch'];

    }



    public function checkAdmissionReferenceNo($ref_no)

    {

        $this->db->select("id");

        $this->db->from('online_admission');

        $this->db->where("reference_no", $ref_no);

        $query = $this->db->get();

        $result = $query->row_array();

        if (!empty($result)) {

            return 1;

        } else {

            return 0;

        }

    }



    public function getLatestNews($branchID='')

    {

        $this->db->limit(10);

        $this->db->where('show_web', 1);

        $this->db->where('branch_id', $branchID);

        $this->db->order_by("id", "desc");

        $news_list = $this->db->get('front_cms_news_list')->result();

        return $news_list;

    }



    public function getLatestNewsList($branchID='', $params = [])

    {

        $this->db->where('branch_id', $branchID);

        $this->db->where('show_web', 1);

        if (!empty($params['start']) && !empty($params['limit'])) {

            $this->db->limit($params['limit'], $params['start']);

        } elseif (empty($params['start']) && !empty($params['limit'])) {

            $this->db->limit($params['limit']);

        }

        $this->db->order_by("id", "desc");

        $q = $this->db->get('front_cms_news_list')->result_array();

        return $q;

    }



    public function getLatestEventList($branchID='', $params = [])
    {
        $this->db->where('branch_id', $branchID);
        $this->db->where('status', 1);
        $this->db->where('show_web', 1);
        $this->db->order_by('start_date', 'desc');

        if (!empty($params['start']) && !empty($params['limit'])) {
            $this->db->limit($params['limit'], $params['start']);
        } elseif (empty($params['start']) && !empty($params['limit'])) {
            $this->db->limit($params['limit']);
        }

        $q = $this->db->get('event')->result_array();

        if (empty($q)) {
            $this->ensure_demo_events($branchID);
            $this->db->where('branch_id', $branchID);
            $this->db->where('status', 1);
            $this->db->where('show_web', 1);
            $this->db->order_by('start_date', 'desc');

            if (!empty($params['start']) && !empty($params['limit'])) {
                $this->db->limit($params['limit'], $params['start']);
            } elseif (empty($params['start']) && !empty($params['limit'])) {
                $this->db->limit($params['limit']);
            }
            $q = $this->db->get('event')->result_array();
        }

        return $q;
    }

    public function ensure_demo_events($branchID = 1)
    {
        if (empty($branchID)) {
            $branchID = 1;
        }

        if (!$this->db->table_exists('event')) {
            return;
        }

        // Ensure columns exist in event table
        if (!$this->db->field_exists('title_en', 'event')) {
            $this->load->dbforge();
            $this->dbforge->add_column('event', [
                'title_en' => ['type' => 'VARCHAR', 'constraint' => 255, 'null' => TRUE, 'after' => 'title'],
                'remark_en' => ['type' => 'TEXT', 'null' => TRUE, 'after' => 'remark'],
                'type_en' => ['type' => 'VARCHAR', 'constraint' => 255, 'null' => TRUE, 'after' => 'type']
            ]);
        }

        // Ensure name_en column in event_types
        if ($this->db->table_exists('event_types') && !$this->db->field_exists('name_en', 'event_types')) {
            $this->load->dbforge();
            $this->dbforge->add_column('event_types', [
                'name_en' => ['type' => 'VARCHAR', 'constraint' => 255, 'null' => TRUE, 'after' => 'name']
            ]);
        }

        // Ensure event types exist
        $type_map = [];
        if ($this->db->table_exists('event_types')) {
            $default_types = [
                ['name' => 'বার্ষিক প্রতিযোগিতা', 'name_en' => 'Annual Competition', 'icon' => 'flag-checkered', 'branch_id' => $branchID],
                ['name' => 'ইসলামিক প্রতিযোগিতা', 'name_en' => 'Islamic Competition', 'icon' => 'comments', 'branch_id' => $branchID],
                ['name' => 'একাডেমিক সম্মেলন', 'name_en' => 'Academic Conference', 'icon' => 'users', 'branch_id' => $branchID]
            ];
            foreach ($default_types as $dt) {
                $exist = $this->db->where('name', $dt['name'])->where('branch_id', $branchID)->get('event_types')->row_array();
                if (!empty($exist)) {
                    $type_map[$dt['name']] = $exist['id'];
                } else {
                    $this->db->insert('event_types', $dt);
                    $type_map[$dt['name']] = $this->db->insert_id();
                }
            }
        }

        $count = $this->db->where('branch_id', $branchID)->count_all_results('event');
        if ($count > 0) {
            // If events exist but have text type, update to type ID
            if (!empty($type_map['বার্ষিক প্রতিযোগিতা'])) {
                $this->db->where('branch_id', $branchID)->where('title LIKE', '%বার্ষিক ক্রীড়া প্রতিযোগিতা%')->update('event', ['type' => $type_map['বার্ষিক প্রতিযোগিতা']]);
            }
            if (!empty($type_map['ইসলামিক প্রতিযোগিতা'])) {
                $this->db->where('branch_id', $branchID)->where('title LIKE', '%কেরাত%')->update('event', ['type' => $type_map['ইসলামিক প্রতিযোগিতা']]);
            }
            if (!empty($type_map['একাডেমিক সম্মেলন'])) {
                $this->db->where('branch_id', $branchID)->where('title LIKE', '%ফলাফল প্রকাশ%')->update('event', ['type' => $type_map['একাডেমিক সম্মেলন']]);
            }
            return;
        }

        $session_id = 7;
        if (function_exists('get_session_id')) {
            $s_val = get_session_id();
            if (!empty($s_val)) {
                $session_id = $s_val;
            }
        }

        $t1 = !empty($type_map['বার্ষিক প্রতিযোগিতা']) ? $type_map['বার্ষিক প্রতিযোগিতা'] : '1';
        $t2 = !empty($type_map['ইসলামিক প্রতিযোগিতা']) ? $type_map['ইসলামিক প্রতিযোগিতা'] : '2';
        $t3 = !empty($type_map['একাডেমিক সম্মেলন']) ? $type_map['একাডেমিক সম্মেলন'] : '3';

        $demo_events = [
            [
                'branch_id' => $branchID,
                'title' => 'বার্ষিক ক্রীড়া প্রতিযোগিতা ও সাংস্কৃতিক অনুষ্ঠান',
                'title_en' => 'Annual Sports Competition & Cultural Program',
                'type' => $t1,
                'type_en' => 'Annual Competition',
                'remark' => '<p>মাদ্রাসার সকল শিক্ষার্থীদের আনন্দের সাথে জানানো যাচ্ছে যে, আগামী ১৫ই নভেম্বর মাদ্রাসা মাঠে বার্ষিক ক্রীড়া প্রতিযোগিতা ও সাংস্কৃতিক অনুষ্ঠান অনুষ্ঠিত হবে। দৌড়, হাই জাম্প, লং জাম্প, যেমন খুশি তেমন সাজো সহ বিভিন্ন আকর্ষণীয় ইভেন্টে শিক্ষার্থীরা অংশগ্রহণ করতে পারবে। সকল শিক্ষার্থীকে নির্ধারিত সময়ে উপস্থিত থাকার জন্য আহ্বান জানানো হচ্ছে।</p>',
                'remark_en' => '<p>All students of the madrasah are cordially informed that the Annual Sports Competition and Cultural Program will be held on November 15 at the madrasah grounds. Students can participate in various exciting events including sprint, high jump, long jump, and dress as you like. All students are requested to be present at the scheduled time.</p>',
                'start_date' => '2026-11-15',
                'end_date' => '2026-11-16',
                'image' => '5hMJasdzo9ywqzCMgWDKotpc00a7l8RsTtCWNRFY.jpg',
                'audition' => '1',
                'selected_list' => '',
                'status' => 1,
                'show_web' => 1,
                'session_id' => $session_id,
                'created_by' => 1,
                'created_at' => date('Y-m-d H:i:s')
            ],
            [
                'branch_id' => $branchID,
                'title' => 'জাতীয় কেরাত, হিফজ ও হামদ-নাত প্রতিযোগিতা',
                'title_en' => 'National Qirat, Hifz & Hamd-Naat Competition',
                'type' => $t2,
                'type_en' => 'Islamic Competition',
                'remark' => '<p>চর হাসান ইসলামিয়া দাখিল মাদ্রাসার উদ্যোগে আগামী ২৫শে নভেম্বর হিফজুল কোরআন, সুললিত কণ্ঠে কেরাত ও হামদ-নাত প্রতিযোগিতা অনুষ্ঠিত হবে। প্রতিযোগিতায় বিজয়ী শিক্ষার্থীদের মাঝে আকর্ষণীয় পুরস্কার ও সনদপত্র বিতরণ করা হবে। ইচ্ছুক শিক্ষার্থীদের নিজ নিজ শ্রেণি শিক্ষকের নিকট আগামী ২০শে নভেম্বরের মধ্যে নাম তালিকাভুক্ত করতে হবে।</p>',
                'remark_en' => '<p>Under the initiative of Char Hasan Islamia Dakhil Madrasah, a National Qirat, Hifz, and Hamd-Naat Competition will be held on November 25. Attractive prizes and certificates will be distributed among the winners. Interested students must register their names with their respective class teachers by November 20.</p>',
                'start_date' => '2026-11-25',
                'end_date' => '2026-11-25',
                'image' => 'Bgs8Aeyj6Lmz6KWiJX0G9vfnmijhg26a7CiOTlRe.png',
                'audition' => '1',
                'selected_list' => '',
                'status' => 1,
                'show_web' => 1,
                'session_id' => $session_id,
                'created_by' => 1,
                'created_at' => date('Y-m-d H:i:s')
            ],
            [
                'branch_id' => $branchID,
                'title' => 'বার্ষিক পরীক্ষার ফলাফল প্রকাশ ও কৃতি শিক্ষার্থী সংবর্ধনা',
                'title_en' => 'Annual Examination Result Publication & Meritorious Student Reception',
                'type' => $t3,
                'type_en' => 'Academic Conference',
                'remark' => '<p>মাদ্রাসার বার্ষিক পরীক্ষার ফলাফল প্রকাশ, মেধা তালিকায় স্থানপ্রাপ্ত কৃতি শিক্ষার্থীদের সংবর্ধনা এবং অভিভাবক সমাবেশ আগামী ৫ই ডিসেম্বর সকাল ১০টায় মাদ্রাসা অডিটোরিয়ামে অনুষ্ঠিত হবে। অনুষ্ঠানে প্রধান অতিথি হিসেবে উপস্থিত থাকবেন বিশিষ্ট শিক্ষাবিদ ও স্থানীয় গণ্যমান্য ব্যক্তিবর্গ। সকল অভিভাবক ও শুভানুধ্যায়ীদের যথাসময়ে উপস্থিত হয়ে অনুষ্ঠানটি সফল করার বিনীত অনুরোধ করা হলো।</p>',
                'remark_en' => '<p>The publication of annual examination results, meritorious student reception, and parents meeting will be held on December 5 at 10:00 AM in the madrasah auditorium. Eminent educationists and local dignitaries will be present as chief guests. All parents and well-wishers are cordially requested to attend on time to make the event a success.</p>',
                'start_date' => '2026-12-05',
                'end_date' => '2026-12-05',
                'image' => 'Mno2PfUN00OsT7ROoqUxOimoQHqqNetXAgIDM87W.jpg',
                'audition' => '1',
                'selected_list' => '',
                'status' => 1,
                'show_web' => 1,
                'session_id' => $session_id,
                'created_by' => 1,
                'created_at' => date('Y-m-d H:i:s')
            ]
        ];

        foreach ($demo_events as $event) {
            $this->db->insert('event', $event);
        }
    }

    public function ensure_demo_founders($branchID = 1)
    {
        if (empty($branchID)) {
            $branchID = 1;
        }

        if (!$this->db->table_exists('founder')) {
            return;
        }

        $count = $this->db->where('branch_id', $branchID)->count_all_results('founder');
        if ($count > 0) {
            return;
        }

        $demo_founders = [
            [
                'branch_id' => $branchID,
                'name_bn' => 'আব্দুল মান্নান',
                'name_en' => 'Abdul Mannan',
                'title_bn' => 'সভাপতি',
                'title_en' => 'President',
                'designation_bn' => 'সভাপতি',
                'designation_en' => 'President',
                'position' => 1,
                'message_bn' => '<p>বিসমিল্লাহির রাহমানির রাহিম।</p><p>চর হাসান ইসলামিয়া দাখিল মাদ্রাসা একটি ঐতিহ্যবাহী ও দ্বীনি শিক্ষার অন্যতম বাতিঘর হিসেবে এই অঞ্চলের শিক্ষার্থীদের মাঝে শিক্ষার আলো ছড়িয়ে দিয়ে আসছে। আমাদের মূল লক্ষ্য ও উদ্দেশ্য হলো শিক্ষার্থীদের সহীহ ইসলামিক জ্ঞান ও আধুনিক শিক্ষার সমন্বয়ে এমনভাবে গড়ে তোলা, যাতে তারা সৎ, দেশপ্রেমিক এবং আদর্শ নাগরিক হিসেবে সমাজে অবদান রাখতে পারে।</p><p>মাদ্রাসার শিক্ষার গুণগত মান উন্নয়ন, শৃঙ্খলা বজায় রাখা এবং পরিকাঠামোগত প্রসারে পরিচালনা পরিষদ নিরলসভাবে কাজ করে যাচ্ছে। সকল শিক্ষক, শিক্ষার্থী, অভিভাবক এবং শুভানুধ্যায়ীদের আন্তরিক সহযোগিতা ও দোয়ার জন্য কৃতজ্ঞতা প্রকাশ করছি। আল্লাহ সুবহানাহু ওয়া তাআলা আমাদের এই প্রচেষ্টাকে কবুল করুন। আমিন।</p>',
                'message_en' => '<p>Bismillahir Rahmanir Rahim.</p><p>Char Hasan Islamia Dakhil Madrasah has been spreading the light of knowledge among the students of this region as a revered lighthouse of Islamic and modern education. Our core mission is to nurture students with a harmonious blend of authentic Islamic values and contemporary modern knowledge, empowering them to become honest, patriotic, and responsible citizens.</p><p>The Governing Body is working tirelessly to enhance academic quality, uphold discipline, and expand institutional infrastructure. We express our heartfelt gratitude to all teachers, students, parents, and well-wishers for their sincere support and prayers. May Almighty Allah accept all our noble endeavors. Ameen.</p>',
                'image' => 'cKnOAg3AGny28G9DRD1pf2AdVa3hRpRBsvgPYcX7.jpg',
                'active' => 1,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ],
            [
                'branch_id' => $branchID,
                'name_bn' => 'মোঃ জাহাঙ্গীর',
                'name_en' => 'Md. Jahangir',
                'title_bn' => 'সদস্য সচিব',
                'title_en' => 'Member Secretary',
                'designation_bn' => 'সদস্য সচিব',
                'designation_en' => 'Member Secretary',
                'position' => 2,
                'message_bn' => '<p>বিসমিল্লাহির রাহমানির রাহিম।</p><p>শিক্ষাই জাতির মেরুদণ্ড, আর নৈতিকতা সমৃদ্ধ দ্বীনি শিক্ষা হলো ইহকাল ও পরকালের মুক্তির পথ। চর হাসান ইসলামিয়া দাখিল মাদ্রাসার সার্বিক উন্নয়ন, সুষ্ঠু প্রশাসনিক ব্যবস্থাপনা এবং একাডেমিক কার্যক্রম নিয়মিত তদারকিতে সদস্য সচিব হিসেবে আমি আন্তরিকভাবে প্রতিশ্রুতিবদ্ধ।</p><p>আমাদের সম্মানিত শিক্ষকদের নিষ্ঠা এবং পরিচালনা পরিষদের সুপরিকল্পিত নির্দেশনায় প্রতিষ্ঠানটি প্রতিনিয়ত সাফল্যের পথে এগিয়ে চলেছে। শিক্ষার্থীদের পড়াশোনার উপযুক্ত পরিবেশ নিশ্চিতকরণ ও মাদ্রাসার সার্বিক অগ্রগতিতে আমরা সর্বদা তৎপর। অভিভাবকবৃন্দের কাছে আমাদের বিনীত অনুরোধ, আপনারা আপনাদের সন্তানদের নিয়মিত উপস্থিতি ও পড়াশোনার প্রতি যত্নশীল থাকবেন।</p>',
                'message_en' => '<p>Bismillahir Rahmanir Rahim.</p><p>Education is the backbone of a nation, and value-based Islamic education is the pathway to prosperity in this life and the hereafter. As Member Secretary, I am deeply committed to the overall development, efficient administrative management, and continuous academic excellence of Char Hasan Islamia Dakhil Madrasah.</p><p>With the dedication of our esteemed teachers and the strategic guidance of the Governing Body, the madrasah is consistently marching towards success. We remain devoted to ensuring a peaceful, well-equipped learning atmosphere. We cordially request all parents to guide and monitor their children\'s regular attendance and studies.</p>',
                'image' => 'aRWyGlsIQwnY9sNe8X9mhdyYWIy29evvmD4e1RaD.jpg',
                'active' => 1,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ],
            [
                'branch_id' => $branchID,
                'name_bn' => 'মোঃ আবদুল হক',
                'name_en' => 'Md. Abdul Haque',
                'title_bn' => 'দাতা সদস্য',
                'title_en' => 'Donor Member',
                'designation_bn' => 'দাতা সদস্য',
                'designation_en' => 'Donor Member',
                'position' => 3,
                'message_bn' => '<p>বিসমিল্লাহির রাহমানির রাহিম।</p><p>চর হাসান ইসলামিয়া দাখিল মাদ্রাসার সাথে জড়িত থাকতে পেরে আমি নিজেকে অত্যন্ত সৌভাগ্যবান মনে করি। দ্বীনি শিক্ষার প্রসার এবং এলাকার ভবিষ্যৎ প্রজন্মকে সঠিক পথের দিশা দেওয়ার লক্ষ্যেই আমাদের এই ক্ষুদ্র অবদান।</p><p>একটি প্রতিষ্ঠান তখনই সফলতার স্বর্ণশিখরে পৌঁছায় যখন সমাজের সর্বস্তরের মানুষ একে আপন করে নেয়। এই মাদ্রাসার প্রতিটি শিক্ষার্থী যেন কুরআন-সুন্নাহর আলোকে নিজেকে আলোকিত করে জাতির কল্যাণে নিয়োজিত হতে পারে, এটাই আমার আন্তরিক প্রত্যাশা। আসুন, আমরা সবাই ঐক্যবদ্ধভাবে প্রতিষ্ঠানটির উন্নয়নে অগ্রণী ভূমিকা পালন করি।</p>',
                'message_en' => '<p>Bismillahir Rahmanir Rahim.</p><p>I feel truly honored and blessed to be associated with Char Hasan Islamia Dakhil Madrasah. This revered institution was founded with the noble objective of disseminating Islamic knowledge and empowering future generations with righteous education. Our humble contributions are made solely for the pleasure of Almighty Allah and for guiding our community toward light.</p><p>An institution achieves remarkable heights when people from all walks of life embrace and support it as their own. It is my earnest hope that every student of this madrasah enlightens their life through the teachings of the Quran and Sunnah and dedicates themselves to the service of humanity. Let us all come together to propel this institution to greater glory.</p>',
                'image' => 'yPgOckzU4JGSDMKFvho1hGvXGL3gTlz6Zi9IqrXT.jpg',
                'active' => 1,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ]
        ];

        foreach ($demo_founders as $founder) {
            $this->db->insert('founder', $founder);
        }
    }

}


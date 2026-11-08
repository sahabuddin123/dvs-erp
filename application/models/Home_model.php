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

        $this->db->where('active', $active);

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

        return $q;

    }

}


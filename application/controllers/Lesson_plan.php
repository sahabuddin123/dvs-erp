<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Lesson_plan extends Admin_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('lesson_plan_model');
        $this->load->model('subject_model');
        $this->load->model('classes_model');
    }

    public function index()
    {
        redirect(base_url('lesson_plan/topic'));
    }

    /* -------------------------------------------------------------
     * TOPIC MANAGEMENT
     * -----------------------------------------------------------*/
    public function topic()
    {
        if (!get_permission('lesson_topic', 'is_view')) {
            access_denied();
        }

        $branchID = $this->application_model->get_branch_id();

        if ($_POST) {
            if (get_permission('lesson_topic', 'is_add')) {
                if (is_superadmin_loggedin()) {
                    $this->form_validation->set_rules('branch_id', translate('branch'), 'trim|required');
                }
                $this->form_validation->set_rules('class_id', translate('class'), 'trim|required');
                $this->form_validation->set_rules('section_id', translate('section'), 'trim|required');
                $this->form_validation->set_rules('subject_id', translate('subject'), 'trim|required');
                $this->form_validation->set_rules('name', translate('topic_name'), 'trim|required');

                if ($this->form_validation->run() !== false) {
                    $postData = [
                        'branch_id'  => $branchID,
                        'class_id'   => $this->input->post('class_id'),
                        'section_id' => $this->input->post('section_id'),
                        'subject_id' => $this->input->post('subject_id'),
                        'name'       => $this->input->post('name'),
                    ];
                    $this->lesson_plan_model->saveTopic($postData);
                    set_alert('success', translate('information_has_been_saved_successfully'));
                    $url = base_url('lesson_plan/topic');
                    $array = ['status' => 'success', 'url' => $url, 'error' => ''];
                } else {
                    $error = $this->form_validation->error_array();
                    $array = ['status' => 'fail', 'url' => '', 'error' => $error];
                }
                echo json_encode($array);
                exit();
            }
        }

        $class_id   = $this->input->get('class_id');
        $section_id = $this->input->get('section_id');
        $subject_id = $this->input->get('subject_id');

        $this->data['branch_id']  = $branchID;
        $this->data['class_id']   = $class_id;
        $this->data['section_id'] = $section_id;
        $this->data['subject_id'] = $subject_id;
        $this->data['topic_list'] = $this->lesson_plan_model->getTopicList($branchID, $class_id, $section_id, $subject_id);
        $this->data['title']      = translate('lesson_plan') . ' - ' . translate('topic');
        $this->data['sub_page']   = 'lesson_plan/topic';
        $this->data['main_menu']  = 'lesson_plan';
        $this->load->view('layout/index', $this->data);
    }

    public function topic_edit($id = '')
    {
        if (!get_permission('lesson_topic', 'is_edit')) {
            access_denied();
        }

        $branchID = $this->application_model->get_branch_id();
        $topic = $this->lesson_plan_model->getTopic($id);
        if (!$topic) {
            redirect(base_url('lesson_plan/topic'));
        }

        if ($_POST) {
            if (is_superadmin_loggedin()) {
                $this->form_validation->set_rules('branch_id', translate('branch'), 'trim|required');
            }
            $this->form_validation->set_rules('class_id', translate('class'), 'trim|required');
            $this->form_validation->set_rules('section_id', translate('section'), 'trim|required');
            $this->form_validation->set_rules('subject_id', translate('subject'), 'trim|required');
            $this->form_validation->set_rules('name', translate('topic_name'), 'trim|required');

            if ($this->form_validation->run() !== false) {
                $postData = [
                    'id'         => $id,
                    'branch_id'  => $branchID,
                    'class_id'   => $this->input->post('class_id'),
                    'section_id' => $this->input->post('section_id'),
                    'subject_id' => $this->input->post('subject_id'),
                    'name'       => $this->input->post('name'),
                ];
                $this->lesson_plan_model->saveTopic($postData);
                set_alert('success', translate('information_has_been_updated_successfully'));
                $url = base_url('lesson_plan/topic');
                $array = ['status' => 'success', 'url' => $url, 'error' => ''];
            } else {
                $error = $this->form_validation->error_array();
                $array = ['status' => 'fail', 'url' => '', 'error' => $error];
            }
            echo json_encode($array);
            exit();
        }

        $this->data['branch_id'] = $topic['branch_id'];
        $this->data['topic']     = $topic;
        $this->data['title']     = translate('edit') . ' ' . translate('topic');
        $this->data['sub_page']  = 'lesson_plan/topic_edit';
        $this->data['main_menu'] = 'lesson_plan';
        $this->load->view('layout/index', $this->data);
    }

    public function topic_delete($id = '')
    {
        if (get_permission('lesson_topic', 'is_delete')) {
            $this->lesson_plan_model->deleteTopic($id);
            set_alert('success', translate('information_deleted'));
        }
        redirect(base_url('lesson_plan/topic'));
    }

    /* -------------------------------------------------------------
     * LESSON MANAGEMENT
     * -----------------------------------------------------------*/
    public function lesson()
    {
        if (!get_permission('lesson_manage', 'is_view')) {
            access_denied();
        }

        $branchID = $this->application_model->get_branch_id();

        if ($_POST) {
            if (get_permission('lesson_manage', 'is_add')) {
                if (is_superadmin_loggedin()) {
                    $this->form_validation->set_rules('branch_id', translate('branch'), 'trim|required');
                }
                $this->form_validation->set_rules('class_id', translate('class'), 'trim|required');
                $this->form_validation->set_rules('section_id', translate('section'), 'trim|required');
                $this->form_validation->set_rules('subject_id', translate('subject'), 'trim|required');
                $this->form_validation->set_rules('topic_id', translate('topic'), 'trim|required');
                $this->form_validation->set_rules('name', translate('lesson_name'), 'trim|required');

                if ($this->form_validation->run() !== false) {
                    $postData = [
                        'branch_id'  => $branchID,
                        'class_id'   => $this->input->post('class_id'),
                        'section_id' => $this->input->post('section_id'),
                        'subject_id' => $this->input->post('subject_id'),
                        'topic_id'   => $this->input->post('topic_id'),
                        'name'       => $this->input->post('name'),
                    ];
                    $this->lesson_plan_model->saveLesson($postData);
                    set_alert('success', translate('information_has_been_saved_successfully'));
                    $url = base_url('lesson_plan/lesson');
                    $array = ['status' => 'success', 'url' => $url, 'error' => ''];
                } else {
                    $error = $this->form_validation->error_array();
                    $array = ['status' => 'fail', 'url' => '', 'error' => $error];
                }
                echo json_encode($array);
                exit();
            }
        }

        $class_id   = $this->input->get('class_id');
        $section_id = $this->input->get('section_id');
        $subject_id = $this->input->get('subject_id');
        $topic_id   = $this->input->get('topic_id');

        $this->data['branch_id']   = $branchID;
        $this->data['class_id']    = $class_id;
        $this->data['section_id']  = $section_id;
        $this->data['subject_id']  = $subject_id;
        $this->data['topic_id']    = $topic_id;
        $this->data['lesson_list'] = $this->lesson_plan_model->getLessonList($branchID, $class_id, $section_id, $subject_id, $topic_id);
        $this->data['title']       = translate('lesson_plan') . ' - ' . translate('lesson');
        $this->data['sub_page']    = 'lesson_plan/lesson';
        $this->data['main_menu']   = 'lesson_plan';
        $this->load->view('layout/index', $this->data);
    }

    public function lesson_edit($id = '')
    {
        if (!get_permission('lesson_manage', 'is_edit')) {
            access_denied();
        }

        $branchID = $this->application_model->get_branch_id();
        $lesson = $this->lesson_plan_model->getLesson($id);
        if (!$lesson) {
            redirect(base_url('lesson_plan/lesson'));
        }

        if ($_POST) {
            if (is_superadmin_loggedin()) {
                $this->form_validation->set_rules('branch_id', translate('branch'), 'trim|required');
            }
            $this->form_validation->set_rules('class_id', translate('class'), 'trim|required');
            $this->form_validation->set_rules('section_id', translate('section'), 'trim|required');
            $this->form_validation->set_rules('subject_id', translate('subject'), 'trim|required');
            $this->form_validation->set_rules('topic_id', translate('topic'), 'trim|required');
            $this->form_validation->set_rules('name', translate('lesson_name'), 'trim|required');

            if ($this->form_validation->run() !== false) {
                $postData = [
                    'id'         => $id,
                    'branch_id'  => $branchID,
                    'class_id'   => $this->input->post('class_id'),
                    'section_id' => $this->input->post('section_id'),
                    'subject_id' => $this->input->post('subject_id'),
                    'topic_id'   => $this->input->post('topic_id'),
                    'name'       => $this->input->post('name'),
                ];
                $this->lesson_plan_model->saveLesson($postData);
                set_alert('success', translate('information_has_been_updated_successfully'));
                $url = base_url('lesson_plan/lesson');
                $array = ['status' => 'success', 'url' => $url, 'error' => ''];
            } else {
                $error = $this->form_validation->error_array();
                $array = ['status' => 'fail', 'url' => '', 'error' => $error];
            }
            echo json_encode($array);
            exit();
        }

        $this->data['branch_id'] = $lesson['branch_id'];
        $this->data['lesson']    = $lesson;
        $this->data['title']     = translate('edit') . ' ' . translate('lesson');
        $this->data['sub_page']  = 'lesson_plan/lesson_edit';
        $this->data['main_menu'] = 'lesson_plan';
        $this->load->view('layout/index', $this->data);
    }

    public function lesson_delete($id = '')
    {
        if (get_permission('lesson_manage', 'is_delete')) {
            $this->lesson_plan_model->deleteLesson($id);
            set_alert('success', translate('information_deleted'));
        }
        redirect(base_url('lesson_plan/lesson'));
    }

    /* -------------------------------------------------------------
     * SYLLABUS STATUS MANAGEMENT
     * -----------------------------------------------------------*/
    public function manage()
    {
        if (!get_permission('lesson_syllabus', 'is_view')) {
            access_denied();
        }

        $branchID = $this->application_model->get_branch_id();

        $class_id   = $this->input->get('class_id');
        $section_id = $this->input->get('section_id');
        $subject_id = $this->input->get('subject_id');
        $status     = $this->input->get('status');

        $this->data['branch_id']     = $branchID;
        $this->data['class_id']      = $class_id;
        $this->data['section_id']    = $section_id;
        $this->data['subject_id']    = $subject_id;
        $this->data['status']        = $status;
        $this->data['syllabus_list'] = $this->lesson_plan_model->getSyllabusList($branchID, $class_id, $section_id, $subject_id, $status);
        $this->data['title']         = translate('manage_syllabus_status');
        $this->data['sub_page']      = 'lesson_plan/manage';
        $this->data['main_menu']     = 'lesson_plan';
        $this->load->view('layout/index', $this->data);
    }

    public function save_syllabus_status()
    {
        if (!get_permission('lesson_syllabus', 'is_add') && !get_permission('lesson_syllabus', 'is_edit')) {
            access_denied();
        }

        $this->form_validation->set_rules('class_id', translate('class'), 'trim|required');
        $this->form_validation->set_rules('section_id', translate('section'), 'trim|required');
        $this->form_validation->set_rules('subject_id', translate('subject'), 'trim|required');
        $this->form_validation->set_rules('topic_id', translate('topic'), 'trim|required');
        $this->form_validation->set_rules('lesson_id', translate('lesson'), 'trim|required');
        $this->form_validation->set_rules('date', translate('date'), 'trim|required');
        $this->form_validation->set_rules('status', translate('status'), 'trim|required');

        if ($this->form_validation->run() !== false) {
            $postData = [
                'id'         => $this->input->post('syllabus_id'),
                'branch_id'  => $this->application_model->get_branch_id(),
                'class_id'   => $this->input->post('class_id'),
                'section_id' => $this->input->post('section_id'),
                'subject_id' => $this->input->post('subject_id'),
                'topic_id'   => $this->input->post('topic_id'),
                'lesson_id'  => $this->input->post('lesson_id'),
                'date'       => $this->input->post('date'),
                'time_from'  => $this->input->post('time_from'),
                'time_to'    => $this->input->post('time_to'),
                'status'     => $this->input->post('status'),
                'note'       => $this->input->post('note'),
            ];

            // Handle file attachment if provided
            if (isset($_FILES['attachment']) && !empty($_FILES['attachment']['name'])) {
                $config['upload_path']   = './uploads/attachments/lesson_plan/';
                $config['allowed_types'] = 'pdf|doc|docx|txt|jpg|jpeg|png|ppt|pptx|xls|xlsx|zip';
                $config['max_size']      = 10240; // 10MB
                $config['encrypt_name']  = true;
                $this->load->library('upload', $config);
                if ($this->upload->do_upload('attachment')) {
                    $uploadData = $this->upload->data();
                    $postData['attachment'] = $uploadData['file_name'];
                }
            }

            $this->lesson_plan_model->saveSyllabus($postData);
            set_alert('success', translate('information_has_been_saved_successfully'));
            $url = base_url('lesson_plan/manage');
            $array = ['status' => 'success', 'url' => $url, 'error' => ''];
        } else {
            $error = $this->form_validation->error_array();
            $array = ['status' => 'fail', 'url' => '', 'error' => $error];
        }
        echo json_encode($array);
        exit();
    }

    public function syllabus_delete($id = '')
    {
        if (get_permission('lesson_syllabus', 'is_delete')) {
            $this->lesson_plan_model->deleteSyllabus($id);
            set_alert('success', translate('information_deleted'));
        }
        redirect(base_url('lesson_plan/manage'));
    }

    /* -------------------------------------------------------------
     * OVERVIEW & REPORT
     * -----------------------------------------------------------*/
    public function overview()
    {
        if (!get_permission('lesson_overview', 'is_view')) {
            access_denied();
        }

        $branchID = $this->application_model->get_branch_id();

        $class_id   = $this->input->get('class_id');
        $section_id = $this->input->get('section_id');
        $subject_id = $this->input->get('subject_id');

        $summary = null;
        if (!empty($class_id) && !empty($section_id) && !empty($subject_id)) {
            $summary = $this->lesson_plan_model->getOverviewSummary($branchID, $class_id, $section_id, $subject_id);
        }

        $this->data['branch_id']  = $branchID;
        $this->data['class_id']   = $class_id;
        $this->data['section_id'] = $section_id;
        $this->data['subject_id'] = $subject_id;
        $this->data['summary']    = $summary;
        $this->data['title']      = translate('lesson_plan') . ' - ' . translate('overview_report');
        $this->data['sub_page']   = 'lesson_plan/overview';
        $this->data['main_menu']  = 'lesson_plan';
        $this->load->view('layout/index', $this->data);
    }

    /* -------------------------------------------------------------
     * AJAX HELPERS
     * -----------------------------------------------------------*/
    public function getTopicsBySubject()
    {
        $class_id   = $this->input->post('class_id');
        $section_id = $this->input->post('section_id');
        $subject_id = $this->input->post('subject_id');

        $html = '<option value="">' . translate('select') . '</option>';
        if (!empty($class_id) && !empty($section_id) && !empty($subject_id)) {
            $topics = $this->lesson_plan_model->getTopicsBySubject($class_id, $section_id, $subject_id);
            foreach ($topics as $t) {
                $html .= '<option value="' . $t['id'] . '">' . html_escape($t['name']) . '</option>';
            }
        }
        echo $html;
    }

    public function getLessonsByTopic()
    {
        $topic_id = $this->input->post('topic_id');
        $html = '<option value="">' . translate('select') . '</option>';
        if (!empty($topic_id)) {
            $lessons = $this->lesson_plan_model->getLessonsByTopic($topic_id);
            foreach ($lessons as $l) {
                $html .= '<option value="' . $l['id'] . '">' . html_escape($l['name']) . '</option>';
            }
        }
        echo $html;
    }
}

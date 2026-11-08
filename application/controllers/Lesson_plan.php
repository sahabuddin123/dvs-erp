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
     * MANAGE LESSON PLAN
     * -----------------------------------------------------------*/
    public function manage_lesson_plan()
    {
        if (!get_permission('lesson_plan_manage', 'is_view') && !is_superadmin_loggedin()) {
            access_denied();
        }

        $branchID = $this->application_model->get_branch_id();

        if ($_POST) {
            if (!get_permission('lesson_plan_manage', 'is_add') && !is_superadmin_loggedin()) {
                access_denied();
            }

            if (is_superadmin_loggedin()) {
                $this->form_validation->set_rules('branch_id', translate('branch'), 'trim|required');
            }
            $this->form_validation->set_rules('class_id', translate('class'), 'trim|required');
            $this->form_validation->set_rules('section_id', translate('section'), 'trim|required');
            $this->form_validation->set_rules('subject_id', translate('subject'), 'trim|required');
            $this->form_validation->set_rules('topic_id', translate('topic'), 'trim|required');
            $this->form_validation->set_rules('lesson_id', translate('lesson'), 'trim|required');
            $this->form_validation->set_rules('date', translate('date'), 'trim|required');

            if ($this->form_validation->run() !== false) {
                $postData = [
                    'branch_id'          => $branchID,
                    'class_id'           => $this->input->post('class_id'),
                    'section_id'         => $this->input->post('section_id'),
                    'subject_id'         => $this->input->post('subject_id'),
                    'topic_id'           => $this->input->post('topic_id'),
                    'lesson_id'          => $this->input->post('lesson_id'),
                    'date'               => $this->input->post('date'),
                    'time_from'          => $this->input->post('time_from'),
                    'time_to'            => $this->input->post('time_to'),
                    'objectives'         => $this->input->post('objectives'),
                    'teaching_method'    => $this->input->post('teaching_method'),
                    'general_objectives' => $this->input->post('general_objectives'),
                    'homework'           => $this->input->post('homework'),
                    'status'             => $this->input->post('status') ? $this->input->post('status') : 'Pending',
                ];

                if (isset($_FILES['attachment']) && !empty($_FILES['attachment']['name'])) {
                    @mkdir('./uploads/attachments/lesson_plan/', 0777, true);
                    $config['upload_path']   = './uploads/attachments/lesson_plan/';
                    $config['allowed_types'] = 'pdf|doc|docx|txt|jpg|jpeg|png|ppt|pptx|xls|xlsx|zip';
                    $config['max_size']      = 10240;
                    $config['encrypt_name']  = true;
                    $this->load->library('upload', $config);
                    if ($this->upload->do_upload('attachment')) {
                        $uploadData = $this->upload->data();
                        $postData['attachment'] = $uploadData['file_name'];
                    }
                }

                $this->lesson_plan_model->saveLessonPlan($postData);
                set_alert('success', translate('information_has_been_saved_successfully'));
                $url = base_url('lesson_plan/manage_lesson_plan');
                $array = ['status' => 'success', 'url' => $url, 'error' => ''];
            } else {
                $error = $this->form_validation->error_array();
                $array = ['status' => 'fail', 'url' => '', 'error' => $error];
            }
            echo json_encode($array);
            exit();
        }

        $class_id   = $this->input->get('class_id');
        $section_id = $this->input->get('section_id');
        $subject_id = $this->input->get('subject_id');
        $date_from  = $this->input->get('date_from');
        $date_to    = $this->input->get('date_to');

        $this->data['branch_id']   = $branchID;
        $this->data['class_id']    = $class_id;
        $this->data['section_id']  = $section_id;
        $this->data['subject_id']  = $subject_id;
        $this->data['date_from']   = $date_from;
        $this->data['date_to']     = $date_to;
        $this->data['plan_list']   = $this->lesson_plan_model->getLessonPlanList($branchID, $class_id, $section_id, $subject_id, $date_from, $date_to);
        $this->data['title']       = translate('lesson_plan') . ' - ' . translate('manage_lesson_plan');
        $this->data['sub_page']    = 'lesson_plan/manage_lesson_plan';
        $this->data['main_menu']   = 'lesson_plan';
        $this->data['headerelements'] = [
            'css' => ['vendor/dropify/css/dropify.min.css'],
            'js'  => ['vendor/dropify/js/dropify.min.js'],
        ];
        $this->load->view('layout/index', $this->data);
    }

    public function manage_lesson_plan_edit($id = '')
    {
        if (!get_permission('lesson_plan_manage', 'is_edit') && !is_superadmin_loggedin()) {
            access_denied();
        }

        $branchID = $this->application_model->get_branch_id();
        $plan = $this->lesson_plan_model->getLessonPlan($id);
        if (!$plan) {
            redirect(base_url('lesson_plan/manage_lesson_plan'));
        }

        if ($_POST) {
            if (is_superadmin_loggedin()) {
                $this->form_validation->set_rules('branch_id', translate('branch'), 'trim|required');
            }
            $this->form_validation->set_rules('class_id', translate('class'), 'trim|required');
            $this->form_validation->set_rules('section_id', translate('section'), 'trim|required');
            $this->form_validation->set_rules('subject_id', translate('subject'), 'trim|required');
            $this->form_validation->set_rules('topic_id', translate('topic'), 'trim|required');
            $this->form_validation->set_rules('lesson_id', translate('lesson'), 'trim|required');
            $this->form_validation->set_rules('date', translate('date'), 'trim|required');

            if ($this->form_validation->run() !== false) {
                $postData = [
                    'id'                 => $id,
                    'branch_id'          => $branchID,
                    'class_id'           => $this->input->post('class_id'),
                    'section_id'         => $this->input->post('section_id'),
                    'subject_id'         => $this->input->post('subject_id'),
                    'topic_id'           => $this->input->post('topic_id'),
                    'lesson_id'          => $this->input->post('lesson_id'),
                    'date'               => $this->input->post('date'),
                    'time_from'          => $this->input->post('time_from'),
                    'time_to'            => $this->input->post('time_to'),
                    'objectives'         => $this->input->post('objectives'),
                    'teaching_method'    => $this->input->post('teaching_method'),
                    'general_objectives' => $this->input->post('general_objectives'),
                    'homework'           => $this->input->post('homework'),
                    'status'             => $this->input->post('status') ? $this->input->post('status') : 'Pending',
                ];

                if (isset($_FILES['attachment']) && !empty($_FILES['attachment']['name'])) {
                    @mkdir('./uploads/attachments/lesson_plan/', 0777, true);
                    $config['upload_path']   = './uploads/attachments/lesson_plan/';
                    $config['allowed_types'] = 'pdf|doc|docx|txt|jpg|jpeg|png|ppt|pptx|xls|xlsx|zip';
                    $config['max_size']      = 10240;
                    $config['encrypt_name']  = true;
                    $this->load->library('upload', $config);
                    if ($this->upload->do_upload('attachment')) {
                        $uploadData = $this->upload->data();
                        $postData['attachment'] = $uploadData['file_name'];
                    }
                }

                $this->lesson_plan_model->saveLessonPlan($postData);
                set_alert('success', translate('information_has_been_updated_successfully'));
                $url = base_url('lesson_plan/manage_lesson_plan');
                $array = ['status' => 'success', 'url' => $url, 'error' => ''];
            } else {
                $error = $this->form_validation->error_array();
                $array = ['status' => 'fail', 'url' => '', 'error' => $error];
            }
            echo json_encode($array);
            exit();
        }

        $this->data['plan']       = $plan;
        $this->data['branch_id']  = $plan['branch_id'];
        $this->data['title']      = translate('edit') . ' ' . translate('manage_lesson_plan');
        $this->data['sub_page']   = 'lesson_plan/manage_lesson_plan_edit';
        $this->data['main_menu']  = 'lesson_plan';
        $this->data['headerelements'] = [
            'css' => ['vendor/dropify/css/dropify.min.css'],
            'js'  => ['vendor/dropify/js/dropify.min.js'],
        ];
        $this->load->view('layout/index', $this->data);
    }

    public function manage_lesson_plan_delete($id = '')
    {
        if (get_permission('lesson_plan_manage', 'is_delete') || is_superadmin_loggedin()) {
            $this->lesson_plan_model->deleteLessonPlan($id);
            set_alert('success', translate('information_deleted'));
        }
        redirect(base_url('lesson_plan/manage_lesson_plan'));
    }

    public function view_lesson_plan($id = '')
    {
        $plan = $this->lesson_plan_model->getLessonPlan($id);
        if (!$plan) {
            echo '<div class="alert alert-danger">' . translate('no_information_available') . '</div>';
            return;
        }

        $is_bn = ($this->session->userdata('set_lang') == 'bengali');
        ?>
        <div class="table-responsive">
            <table class="table table-bordered table-striped mb-none">
                <tbody>
                    <tr>
                        <th width="30%"><?=translate('class')?> &amp; <?=translate('section')?></th>
                        <td><?=html_escape($plan['class_name'])?> (<?=html_escape($plan['section_name'])?>)</td>
                    </tr>
                    <tr>
                        <th><?=translate('subject')?></th>
                        <td><?=html_escape($plan['subject_name'])?> (<?=html_escape($plan['subject_code'])?>)</td>
                    </tr>
                    <tr>
                        <th><?=translate('topic')?></th>
                        <td><strong><?=html_escape($plan['topic_name'])?></strong></td>
                    </tr>
                    <tr>
                        <th><?=translate('lesson')?></th>
                        <td><strong><?=html_escape($plan['lesson_name'])?></strong></td>
                    </tr>
                    <tr>
                        <th><?=translate('date')?> &amp; <?=translate('time')?></th>
                        <td>
                            <i class="far fa-calendar-alt text-primary"></i> <?=_d($plan['date'])?>
                            <?php if (!empty($plan['time_from']) || !empty($plan['time_to'])): ?>
                                &nbsp;|&nbsp; <i class="far fa-clock text-info"></i> <?=html_escape($plan['time_from'])?> - <?=html_escape($plan['time_to'])?>
                            <?php endif; ?>
                        </td>
                    </tr>
                    <tr>
                        <th><?=translate('status')?></th>
                        <td>
                            <?php
                            $st = strtolower($plan['status']);
                            if ($st === 'completed') {
                                echo '<span class="badge badge-success"><i class="fas fa-check"></i> ' . ($is_bn ? 'সম্পন্ন' : 'Completed') . '</span>';
                            } elseif ($st === 'in_progress') {
                                echo '<span class="badge badge-info"><i class="fas fa-spinner fa-spin"></i> ' . ($is_bn ? 'চলমান' : 'In Progress') . '</span>';
                            } else {
                                echo '<span class="badge badge-warning"><i class="far fa-clock"></i> ' . ($is_bn ? 'অপেক্ষমান' : 'Pending') . '</span>';
                            }
                            ?>
                        </td>
                    </tr>
                    <?php if (!empty($plan['objectives'])): ?>
                    <tr>
                        <th><?=translate('objectives') ?? ($is_bn ? 'শিখনফল / উদ্দেশ্য' : 'Objectives')?></th>
                        <td style="white-space: pre-line;"><?=html_escape($plan['objectives'])?></td>
                    </tr>
                    <?php endif; ?>
                    <?php if (!empty($plan['teaching_method'])): ?>
                    <tr>
                        <th><?=translate('teaching_method') ?? ($is_bn ? 'শিক্ষণ পদ্ধতি' : 'Teaching Method')?></th>
                        <td style="white-space: pre-line;"><?=html_escape($plan['teaching_method'])?></td>
                    </tr>
                    <?php endif; ?>
                    <?php if (!empty($plan['homework'])): ?>
                    <tr>
                        <th><?=translate('homework') ?? ($is_bn ? 'বাড়ির কাজ' : 'Homework')?></th>
                        <td style="white-space: pre-line;"><?=html_escape($plan['homework'])?></td>
                    </tr>
                    <?php endif; ?>
                    <?php if (!empty($plan['attachment'])): ?>
                    <tr>
                        <th><?=translate('attachment')?></th>
                        <td>
                            <a href="<?=base_url('uploads/attachments/lesson_plan/' . $plan['attachment'])?>" target="_blank" class="btn btn-default btn-sm">
                                <i class="fas fa-download text-primary"></i> <?=translate('download')?>
                            </a>
                        </td>
                    </tr>
                    <?php endif; ?>
                    <?php if (!empty($plan['teacher_name'])): ?>
                    <tr>
                        <th><?=translate('teacher')?></th>
                        <td><?=html_escape($plan['teacher_name'])?></td>
                    </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
        <?php
    }

    /* -------------------------------------------------------------
     * COPY OLD LESSONS
     * -----------------------------------------------------------*/
    public function copy_old_lessons()
    {
        if (!get_permission('lesson_copy', 'is_view') && !is_superadmin_loggedin()) {
            access_denied();
        }

        $branchID = $this->application_model->get_branch_id();

        if ($_POST) {
            if (!get_permission('lesson_copy', 'is_add') && !is_superadmin_loggedin()) {
                access_denied();
            }

            $this->form_validation->set_rules('from_class_id', translate('class') . ' (From)', 'trim|required');
            $this->form_validation->set_rules('from_section_id', translate('section') . ' (From)', 'trim|required');
            $this->form_validation->set_rules('from_subject_id', translate('subject') . ' (From)', 'trim|required');

            $this->form_validation->set_rules('to_class_id', translate('class') . ' (To)', 'trim|required');
            $this->form_validation->set_rules('to_section_id', translate('section') . ' (To)', 'trim|required');
            $this->form_validation->set_rules('to_subject_id', translate('subject') . ' (To)', 'trim|required');

            $selectedTopics  = $this->input->post('topics');
            $selectedLessons = $this->input->post('lessons');

            if (empty($selectedTopics)) {
                $array = ['status' => 'fail', 'url' => '', 'error' => ['topics' => translate('please_select_at_least_one_topic') ?? 'অনুগ্রহ করে অন্তত একটি টপিক নির্বাচন করুন']];
                echo json_encode($array);
                exit();
            }

            if ($this->form_validation->run() !== false) {
                $targetBranch  = $branchID;
                $targetClass   = (int)$this->input->post('to_class_id');
                $targetSection = (int)$this->input->post('to_section_id');
                $targetSubject = (int)$this->input->post('to_subject_id');

                $res = $this->lesson_plan_model->executeCopyLessons($targetBranch, $targetClass, $targetSection, $targetSubject, $selectedTopics, $selectedLessons);

                $msg = "{$res['topics']} " . translate('topics') . " & " . "{$res['lessons']} " . translate('lessons') . " " . (translate('copied_successfully') ?? 'সফলভাবে কপি করা হয়েছে');
                set_alert('success', $msg);
                $url = base_url('lesson_plan/lesson');
                $array = ['status' => 'success', 'url' => $url, 'error' => ''];
            } else {
                $error = $this->form_validation->error_array();
                $array = ['status' => 'fail', 'url' => '', 'error' => $error];
            }
            echo json_encode($array);
            exit();
        }

        $this->data['branch_id'] = $branchID;
        $this->data['title']     = translate('lesson_plan') . ' - ' . translate('copy_old_lessons');
        $this->data['sub_page']  = 'lesson_plan/copy_old_lessons';
        $this->data['main_menu'] = 'lesson_plan';
        $this->load->view('layout/index', $this->data);
    }

    public function getTopicsForCopy()
    {
        $class_id   = (int)$this->input->post('class_id');
        $section_id = (int)$this->input->post('section_id');
        $subject_id = (int)$this->input->post('subject_id');

        $topics = $this->lesson_plan_model->getTopicsAndLessonsForCopy($class_id, $section_id, $subject_id);

        if (empty($topics)) {
            echo '<div class="alert alert-info text-center" style="margin-top: 15px;"><i class="fas fa-info-circle"></i> ' . (translate('no_topics_or_lessons_found_for_selected_subject') ?? 'নির্বাচিত বিষয়ের অধীনে কোনো টপিক বা পাঠ পাওয়া যায়নি।') . '</div>';
            return;
        }

        $is_bn = ($this->session->userdata('set_lang') == 'bengali');
        ?>
        <div style="background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 8px; padding: 15px; margin-top: 15px;">
            <div class="d-flex" style="display: flex; justify-content: space-between; align-items: center; border-bottom: 2px solid #cbd5e1; padding-bottom: 10px; margin-bottom: 15px;">
                <h5 style="margin: 0; font-weight: 700; color: #1e293b;">
                    <i class="fas fa-list-check text-primary"></i> <?=translate('select_topics_and_lessons_to_copy') ?? ($is_bn ? 'কপি করার জন্য টপিক ও পাঠ নির্বাচন করুন' : 'Select Topics and Lessons to Copy')?>
                </h5>
                <div>
                    <label style="font-weight: 600; cursor: pointer; margin: 0; font-size: 13px;">
                        <input type="checkbox" id="selectAllCopy" checked> <?=translate('select_all') ?? ($is_bn ? 'সবগুলো নির্বাচন করুন' : 'Select All')?>
                    </label>
                </div>
            </div>

            <?php foreach ($topics as $t): ?>
            <div class="panel panel-bordered mb-sm" style="border: 1px solid #cbd5e1; border-radius: 6px; overflow: hidden; margin-bottom: 12px;">
                <div class="panel-heading" style="background: #f1f5f9; padding: 10px 14px;">
                    <label style="font-weight: 700; color: #0f172a; margin: 0; cursor: pointer; display: flex; align-items: center; gap: 8px;">
                        <input type="checkbox" class="topic-checkbox" name="topics[]" value="<?=$t['id']?>" checked>
                        <span><i class="fas fa-folder text-warning"></i> <?=html_escape($t['name'])?></span>
                        <span class="badge badge-primary" style="margin-left: 6px;"><?=count($t['lessons'])?> <?=translate('lesson')?></span>
                    </label>
                </div>
                <?php if (!empty($t['lessons'])): ?>
                <div class="panel-body" style="padding: 10px 15px 10px 35px; background: #fff;">
                    <ul style="list-style: none; padding: 0; margin: 0;">
                        <?php foreach ($t['lessons'] as $l): ?>
                        <li style="padding: 4px 0;">
                            <label style="font-weight: 500; color: #334155; margin: 0; cursor: pointer; font-size: 13px; display: flex; align-items: center; gap: 8px;">
                                <input type="checkbox" class="lesson-checkbox" data-topic-id="<?=$t['id']?>" name="lessons[]" value="<?=$l['id']?>" checked>
                                <span><i class="fas fa-file-alt text-muted"></i> <?=html_escape($l['name'])?></span>
                            </label>
                        </li>
                        <?php endforeach; ?>
                    </ul>
                </div>
                <?php else: ?>
                <div class="panel-body text-muted" style="padding: 8px 15px 8px 35px; font-size: 12px; font-style: italic;">
                    <?=translate('no_lessons_under_this_topic') ?? ($is_bn ? 'এই টপিকের অধীনে কোনো পাঠ তৈরি করা নেই।' : 'No lessons under this topic.')?>
                </div>
                <?php endif; ?>
            </div>
            <?php endforeach; ?>
        </div>

        <script>
            $('#selectAllCopy').on('change', function() {
                var checked = $(this).prop('checked');
                $('.topic-checkbox, .lesson-checkbox').prop('checked', checked);
            });
            $('.topic-checkbox').on('change', function() {
                var tid = $(this).val();
                var checked = $(this).prop('checked');
                $('.lesson-checkbox[data-topic-id="' + tid + '"]').prop('checked', checked);
            });
        </script>
        <?php
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

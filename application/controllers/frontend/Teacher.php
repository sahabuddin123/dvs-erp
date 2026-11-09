<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Teacher extends Admin_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('frontend_model');
        $this->ensure_teacher_table();
    }

    private function ensure_teacher_table()
    {
        if (!$this->db->table_exists('teacher')) {
            $this->db->query("CREATE TABLE IF NOT EXISTS `teacher` (
              `id` int(11) NOT NULL AUTO_INCREMENT,
              `branch_id` int(11) NOT NULL DEFAULT 1,
              `staff_id` int(11) DEFAULT NULL,
              `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
              `name_bn` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
              `name_en` varchar(255) DEFAULT NULL,
              `title` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
              `title_bn` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
              `title_en` varchar(255) DEFAULT NULL,
              `designation` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
              `designation_bn` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
              `designation_en` varchar(255) DEFAULT NULL,
              `subject` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
              `subject_bn` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
              `subject_en` varchar(255) DEFAULT NULL,
              `position` int(11) NOT NULL DEFAULT 1,
              `phone` varchar(50) DEFAULT NULL,
              `email` varchar(255) DEFAULT NULL,
              `message` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
              `message_bn` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
              `message_en` longtext DEFAULT NULL,
              `image` varchar(255) DEFAULT NULL,
              `show_on_homepage` tinyint(1) NOT NULL DEFAULT 1,
              `active` tinyint(1) NOT NULL DEFAULT 1,
              `created_at` datetime DEFAULT NULL,
              `updated_at` datetime DEFAULT NULL,
              PRIMARY KEY (`id`),
              KEY `branch_id` (`branch_id`),
              KEY `staff_id` (`staff_id`),
              KEY `active` (`active`),
              KEY `show_on_homepage` (`show_on_homepage`),
              KEY `position` (`position`)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;");
        } else {
            if (!$this->db->field_exists('staff_id', 'teacher')) {
                $this->db->query("ALTER TABLE `teacher` ADD `staff_id` INT(11) DEFAULT NULL AFTER `branch_id`, ADD KEY `staff_id` (`staff_id`)");
            }
        }
    }

    public function sync_teachers_from_staff($branchID)
    {
        $this->load->model('home_model');
        $staff_teachers = $this->home_model->get_teacher_list('', $branchID);
        if (empty($staff_teachers)) {
            return 0;
        }

        $max_pos_row = $this->db->select_max('position')->where('branch_id', $branchID)->get('teacher')->row();
        $next_pos = ($max_pos_row && $max_pos_row->position > 0) ? ($max_pos_row->position + 1) : 1;

        $synced_count = 0;
        foreach ($staff_teachers as $st) {
            $exists = $this->db->where('branch_id', $branchID)
                ->group_start()
                    ->where('staff_id', $st['id'])
                    ->or_where('name', $st['name'])
                ->group_end()
                ->get('teacher')->row_array();

            if (!$exists) {
                $title = !empty($st['designation_name']) ? $st['designation_name'] : 'সহকারী মৌলভী';
                $subject = !empty($st['department_name']) ? $st['department_name'] : (!empty($st['designation_name']) ? $st['designation_name'] : 'সাধারণ');
                $insert_data = [
                    'branch_id'        => $branchID,
                    'staff_id'         => $st['id'],
                    'name'             => $st['name'],
                    'name_bn'          => $st['name'],
                    'name_en'          => $st['name'],
                    'title'            => $title,
                    'title_bn'         => $title,
                    'title_en'         => 'Teacher',
                    'designation'      => $title,
                    'designation_bn'   => $title,
                    'designation_en'   => 'Teacher',
                    'subject'          => $subject,
                    'subject_bn'       => $subject,
                    'subject_en'       => 'General',
                    'position'         => $next_pos++,
                    'phone'            => !empty($st['mobileno']) ? $st['mobileno'] : '',
                    'email'            => !empty($st['email']) ? $st['email'] : '',
                    'message'          => '',
                    'message_bn'       => '',
                    'message_en'       => '',
                    'image'            => !empty($st['photo']) ? $st['photo'] : '',
                    'show_on_homepage' => 1,
                    'active'           => 1,
                    'created_at'       => date('Y-m-d H:i:s'),
                    'updated_at'       => date('Y-m-d H:i:s'),
                ];
                $this->db->insert('teacher', $insert_data);
                $synced_count++;
            } else {
                if (empty($exists['staff_id'])) {
                    $this->db->where('id', $exists['id'])->update('teacher', ['staff_id' => $st['id']]);
                }
            }
        }
        return $synced_count;
    }

    public function sync()
    {
        if (!get_permission('frontend_teacher', 'is_edit') && !is_superadmin_loggedin() && !get_permission('frontend_setting', 'is_view')) {
            access_denied();
        }
        $branchID = $this->frontend_model->getBranchID();
        if (empty($branchID)) {
            $branchID = $this->input->get('branch_id');
        }
        if (empty($branchID)) {
            $defaultBranch = $this->db->select('id')->limit(1)->get('branch')->row();
            $branchID = $defaultBranch ? $defaultBranch->id : 1;
        }
        $count = $this->sync_teachers_from_staff($branchID);
        set_alert('success', 'কর্মকর্তা ও কর্মচারী থেকে শিক্ষকবৃন্দ সফলভাবে সিঙ্ক করা হয়েছে (' . $count . ' জন নতুন যোগ হয়েছে)।');
        redirect(base_url('frontend/teacher' . (is_superadmin_loggedin() ? '?branch_id=' . $branchID : '')));
    }

    private function validation_rules()
    {
        if (is_superadmin_loggedin()) {
            $this->form_validation->set_rules('branch_id', translate('branch'), 'required');
        }
        if (empty($_POST['name']) && !empty($_POST['name_bn'])) {
            $_POST['name'] = $_POST['name_bn'];
        }
        if (empty($_POST['name']) && !empty($_POST['name_en'])) {
            $_POST['name'] = $_POST['name_en'];
        }
        if (empty($_POST['title']) && !empty($_POST['title_bn'])) {
            $_POST['title'] = $_POST['title_bn'];
        }
        if (empty($_POST['designation']) && !empty($_POST['designation_bn'])) {
            $_POST['designation'] = $_POST['designation_bn'];
        }
        if (empty($_POST['subject']) && !empty($_POST['subject_bn'])) {
            $_POST['subject'] = $_POST['subject_bn'];
        }

        $this->form_validation->set_rules('name', 'Name', 'trim|required');
        $this->form_validation->set_rules('title', 'Title', 'trim');
        $this->form_validation->set_rules('designation', 'Designation', 'trim');
        $this->form_validation->set_rules('subject', 'Subject', 'trim');
        $this->form_validation->set_rules('position', 'Position', 'trim|numeric|required');
        $this->form_validation->set_rules('phone', 'Phone', 'trim');
        $this->form_validation->set_rules('email', 'Email', 'trim|valid_email');
        $this->form_validation->set_rules('message', 'Message/Bio', 'trim');
    }

    public function index()
    {
        if (!get_permission('frontend_teacher', 'is_view') && !is_superadmin_loggedin() && !get_permission('frontend_setting', 'is_view')) {
            access_denied();
        }

        $branchID = $this->frontend_model->getBranchID();

        // Handle POST search filter fallback if JS was disabled
        if (isset($_POST['search'])) {
            $branchID = $this->input->post('branch_id');
            redirect(base_url('frontend/teacher' . (!empty($branchID) ? '?branch_id=' . $branchID : '')));
        }

        // Default to branch 1 if superadmin has not selected any branch
        if (empty($branchID)) {
            $defaultBranch = $this->db->select('id')->limit(1)->get('branch')->row();
            $branchID = $defaultBranch ? $defaultBranch->id : 1;
        }

        if ($_POST && !isset($_POST['search'])) {
            $this->validation_rules();
            if ($this->form_validation->run() !== false) {
                $post = $this->input->post();
                $post['branch_id'] = is_superadmin_loggedin() && !empty($post['branch_id']) ? $post['branch_id'] : $branchID;

                $image_name = $this->upload_teacher_image();

                $name = $post['name'];
                $name_bn = !empty($post['name_bn']) ? $post['name_bn'] : $name;
                $name_en = !empty($post['name_en']) ? $post['name_en'] : $name;

                $title = !empty($post['title']) ? $post['title'] : '';
                $title_bn = !empty($post['title_bn']) ? $post['title_bn'] : $title;
                $title_en = !empty($post['title_en']) ? $post['title_en'] : $title;

                $designation = !empty($post['designation']) ? $post['designation'] : '';
                $designation_bn = !empty($post['designation_bn']) ? $post['designation_bn'] : $designation;
                $designation_en = !empty($post['designation_en']) ? $post['designation_en'] : $designation;

                $subject = !empty($post['subject']) ? $post['subject'] : '';
                $subject_bn = !empty($post['subject_bn']) ? $post['subject_bn'] : $subject;
                $subject_en = !empty($post['subject_en']) ? $post['subject_en'] : $subject;

                $message = !empty($post['message']) ? $post['message'] : '';
                $message_bn = !empty($post['message_bn']) ? $post['message_bn'] : $message;
                $message_en = !empty($post['message_en']) ? $post['message_en'] : $message;

                $insert_data = [
                    'branch_id'        => $post['branch_id'],
                    'name'             => $name,
                    'name_bn'          => $name_bn,
                    'name_en'          => $name_en,
                    'title'            => $title,
                    'title_bn'         => $title_bn,
                    'title_en'         => $title_en,
                    'designation'      => $designation,
                    'designation_bn'   => $designation_bn,
                    'designation_en'   => $designation_en,
                    'subject'          => $subject,
                    'subject_bn'       => $subject_bn,
                    'subject_en'       => $subject_en,
                    'position'         => !empty($post['position']) ? (int)$post['position'] : 1,
                    'phone'            => !empty($post['phone']) ? $post['phone'] : '',
                    'email'            => !empty($post['email']) ? $post['email'] : '',
                    'message'          => $message,
                    'message_bn'       => $message_bn,
                    'message_en'       => $message_en,
                    'image'            => $image_name,
                    'show_on_homepage' => isset($post['show_on_homepage']) ? 1 : 0,
                    'active'           => isset($post['active']) ? 1 : 0,
                    'created_at'       => date('Y-m-d H:i:s'),
                    'updated_at'       => date('Y-m-d H:i:s'),
                ];

                $this->db->insert('teacher', $insert_data);
                set_alert('success', translate('information_has_been_saved_successfully'));
                $url = base_url('frontend/teacher' . (is_superadmin_loggedin() ? '?branch_id=' . $post['branch_id'] : ''));
                echo json_encode(['status' => 'success', 'url' => $url]);
                exit();
            } else {
                $error = $this->form_validation->error_array();
                echo json_encode(['status' => 'fail', 'error' => $error]);
                exit();
            }
        }

        $this->data['branch_id'] = $branchID;
        $this->data['headerelements'] = [
            'css' => [
                'vendor/dropify/css/dropify.min.css',
            ],
            'js' => [
                'vendor/dropify/js/dropify.min.js',
                'vendor/ckeditor/ckeditor.js',
            ],
        ];
        if (!empty($branchID)) {
            $count_existing = $this->db->where('branch_id', $branchID)->count_all_results('teacher');
            if ($count_existing == 0) {
                $this->sync_teachers_from_staff($branchID);
            }
            $this->db->where('branch_id', $branchID);
        }
        $this->data['teacherlist'] = $this->db->order_by('position', 'asc')->order_by('id', 'asc')->get('teacher')->result_array();
        $this->data['title'] = translate('teachers');
        $this->data['sub_page'] = 'frontend/teacher';
        $this->data['main_menu'] = 'frontend';
        $this->load->view('layout/index', $this->data);
    }

    public function edit($id = '')
    {
        if (!get_permission('frontend_teacher', 'is_edit') && !is_superadmin_loggedin() && !get_permission('frontend_setting', 'is_view')) {
            access_denied();
        }

        $teacher = $this->db->get_where('teacher', ['id' => $id])->row_array();
        if (empty($teacher)) {
            set_alert('error', translate('no_information_found'));
            redirect(base_url('frontend/teacher'));
        }

        if ($_POST) {
            $this->validation_rules();
            if ($this->form_validation->run() !== false) {
                $post = $this->input->post();
                $branch_id = is_superadmin_loggedin() && !empty($post['branch_id']) ? $post['branch_id'] : $teacher['branch_id'];

                $remove_image = ($this->input->post('remove_image') == '1' || $this->input->post('remove_image_chk') == '1');

                if (!empty($_FILES['image']['name'])) {
                    $image_name = $this->upload_teacher_image($teacher['image']);
                } elseif ($remove_image) {
                    $destination = FCPATH . 'uploads/frontend/teacher/';
                    if (!empty($teacher['image']) && file_exists($destination . $teacher['image'])) {
                        @unlink($destination . $teacher['image']);
                    }
                    $image_name = '';
                } else {
                    $image_name = !empty($teacher['image']) ? $teacher['image'] : '';
                }

                $name = $post['name'];
                $name_bn = !empty($post['name_bn']) ? $post['name_bn'] : (!empty($teacher['name_bn']) ? $teacher['name_bn'] : $name);
                $name_en = !empty($post['name_en']) ? $post['name_en'] : (!empty($teacher['name_en']) ? $teacher['name_en'] : $name);

                $title = !empty($post['title']) ? $post['title'] : '';
                $title_bn = !empty($post['title_bn']) ? $post['title_bn'] : $title;
                $title_en = !empty($post['title_en']) ? $post['title_en'] : $title;

                $designation = !empty($post['designation']) ? $post['designation'] : '';
                $designation_bn = !empty($post['designation_bn']) ? $post['designation_bn'] : $designation;
                $designation_en = !empty($post['designation_en']) ? $post['designation_en'] : $designation;

                $subject = !empty($post['subject']) ? $post['subject'] : '';
                $subject_bn = !empty($post['subject_bn']) ? $post['subject_bn'] : $subject;
                $subject_en = !empty($post['subject_en']) ? $post['subject_en'] : $subject;

                $message = !empty($post['message']) ? $post['message'] : '';
                $message_bn = !empty($post['message_bn']) ? $post['message_bn'] : $message;
                $message_en = !empty($post['message_en']) ? $post['message_en'] : $message;

                $update_data = [
                    'branch_id'        => $branch_id,
                    'name'             => $name,
                    'name_bn'          => $name_bn,
                    'name_en'          => $name_en,
                    'title'            => $title,
                    'title_bn'         => $title_bn,
                    'title_en'         => $title_en,
                    'designation'      => $designation,
                    'designation_bn'   => $designation_bn,
                    'designation_en'   => $designation_en,
                    'subject'          => $subject,
                    'subject_bn'       => $subject_bn,
                    'subject_en'       => $subject_en,
                    'position'         => !empty($post['position']) ? (int)$post['position'] : 1,
                    'phone'            => !empty($post['phone']) ? $post['phone'] : '',
                    'email'            => !empty($post['email']) ? $post['email'] : '',
                    'message'          => $message,
                    'message_bn'       => $message_bn,
                    'message_en'       => $message_en,
                    'image'            => $image_name,
                    'show_on_homepage' => isset($post['show_on_homepage']) ? 1 : 0,
                    'active'           => isset($post['active']) ? 1 : 0,
                    'updated_at'       => date('Y-m-d H:i:s'),
                ];

                $this->db->where('id', $id)->update('teacher', $update_data);
                set_alert('success', translate('information_has_been_updated_successfully'));
                $url = base_url('frontend/teacher' . (is_superadmin_loggedin() ? '?branch_id=' . $branch_id : ''));
                echo json_encode(['status' => 'success', 'url' => $url]);
                exit();
            } else {
                $error = $this->form_validation->error_array();
                echo json_encode(['status' => 'fail', 'error' => $error]);
                exit();
            }
        }

        $this->data['teacher'] = $teacher;
        $this->data['headerelements'] = [
            'css' => [
                'vendor/dropify/css/dropify.min.css',
            ],
            'js' => [
                'vendor/dropify/js/dropify.min.js',
                'vendor/ckeditor/ckeditor.js',
            ],
        ];
        $this->data['title'] = translate('teachers');
        $this->data['sub_page'] = 'frontend/teacher_edit';
        $this->data['main_menu'] = 'frontend';
        $this->load->view('layout/index', $this->data);
    }

    public function delete($id = '')
    {
        if (!get_permission('frontend_teacher', 'is_delete') && !is_superadmin_loggedin() && !get_permission('frontend_setting', 'is_view')) {
            access_denied();
        }

        $teacher = $this->db->get_where('teacher', ['id' => $id])->row_array();
        if (!empty($teacher)) {
            if (!empty($teacher['image']) && file_exists(FCPATH . 'uploads/frontend/teacher/' . $teacher['image'])) {
                @unlink(FCPATH . 'uploads/frontend/teacher/' . $teacher['image']);
            }
            $this->db->where('id', $id)->delete('teacher');
            set_alert('success', translate('information_deleted'));
        }
        redirect(base_url('frontend/teacher'));
    }

    private function upload_teacher_image($prev_image = '')
    {
        if (!empty($_FILES['image']['name'])) {
            $upload_path = FCPATH . 'uploads/frontend/teacher/';
            if (!is_dir($upload_path)) {
                mkdir($upload_path, 0755, true);
            }

            $config = [
                'upload_path'   => $upload_path,
                'allowed_types' => 'jpg|jpeg|png|gif|webp',
                'max_size'      => 2048,
                'encrypt_name'  => true,
            ];

            $this->load->library('upload', $config);
            $this->upload->initialize($config);

            if ($this->upload->do_upload('image')) {
                $data = $this->upload->data();
                if (!empty($prev_image) && file_exists($upload_path . $prev_image)) {
                    @unlink($upload_path . $prev_image);
                }
                return $data['file_name'];
            }
        }
        return $prev_image;
    }
}


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
              KEY `active` (`active`),
              KEY `show_on_homepage` (`show_on_homepage`),
              KEY `position` (`position`)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;");
        }
    }

    private function validation_rules()
    {
        if (is_superadmin_loggedin()) {
            $this->form_validation->set_rules('branch_id', translate('branch'), 'required');
        }
        $this->form_validation->set_rules('name', 'Name', 'trim|required');
        $this->form_validation->set_rules('title', 'Title', 'trim|required');
        $this->form_validation->set_rules('designation', 'Designation', 'trim|required');
        $this->form_validation->set_rules('subject', 'Subject', 'trim|required');
        $this->form_validation->set_rules('position', 'Position', 'trim|numeric');
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

        if ($_POST) {
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
        $this->data['teacherlist'] = $this->db->where('branch_id', $branchID)->order_by('position', 'asc')->order_by('id', 'asc')->get('teacher')->result_array();
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

                $image_name = $this->upload_teacher_image($teacher['image']);

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


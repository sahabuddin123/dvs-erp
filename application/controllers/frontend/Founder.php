<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Founder extends Admin_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('frontend_model');
        $this->ensure_founder_table();
    }

    private function ensure_founder_table()
    {
        if (!$this->db->table_exists('founder')) {
            $this->db->query("CREATE TABLE IF NOT EXISTS `founder` (
              `id` int(11) NOT NULL AUTO_INCREMENT,
              `branch_id` int(11) NOT NULL DEFAULT 1,
              `name_bn` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
              `name_en` varchar(255) DEFAULT NULL,
              `title_bn` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
              `title_en` varchar(255) DEFAULT NULL,
              `designation_bn` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
              `designation_en` varchar(255) DEFAULT NULL,
              `position` int(11) NOT NULL DEFAULT 1,
              `message_bn` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
              `message_en` longtext DEFAULT NULL,
              `image` varchar(255) DEFAULT NULL,
              `active` tinyint(1) NOT NULL DEFAULT 1,
              `created_at` datetime DEFAULT NULL,
              `updated_at` datetime DEFAULT NULL,
              PRIMARY KEY (`id`),
              KEY `branch_id` (`branch_id`),
              KEY `active` (`active`),
              KEY `position` (`position`)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;");
        }
    }

    private function validation_rules()
    {
        if (is_superadmin_loggedin()) {
            $this->form_validation->set_rules('branch_id', translate('branch'), 'required');
        }
        $this->form_validation->set_rules('name_bn', 'Name (Bangla)', 'trim|required');
        $this->form_validation->set_rules('name_en', 'Name (English)', 'trim|required');
        $this->form_validation->set_rules('title_bn', 'Title (Bangla)', 'trim');
        $this->form_validation->set_rules('title_en', 'Title (English)', 'trim');
        $this->form_validation->set_rules('designation_bn', 'Designation (Bangla)', 'trim');
        $this->form_validation->set_rules('designation_en', 'Designation (English)', 'trim');
        $this->form_validation->set_rules('position', 'Position', 'trim|numeric');
        $this->form_validation->set_rules('message_bn', 'Message (Bangla)', 'trim');
        $this->form_validation->set_rules('message_en', 'Message (English)', 'trim');
    }

    public function index()
    {
        if (!get_permission('frontend_founder', 'is_view') && !is_superadmin_loggedin() && !get_permission('frontend_setting', 'is_view')) {
            access_denied();
        }

        $branchID = $this->frontend_model->getBranchID();

        // Handle POST search filter fallback if JS was disabled
        if (isset($_POST['search'])) {
            $branchID = $this->input->post('branch_id');
            redirect(base_url('frontend/founder' . (!empty($branchID) ? '?branch_id=' . $branchID : '')));
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
                
                // Handle image upload
                $image_name = $this->upload_founder_image();

                $insert_data = [
                    'branch_id'      => $post['branch_id'],
                    'name_bn'        => $post['name_bn'],
                    'name_en'        => $post['name_en'],
                    'title_bn'       => !empty($post['title_bn']) ? $post['title_bn'] : '',
                    'title_en'       => !empty($post['title_en']) ? $post['title_en'] : '',
                    'designation_bn' => !empty($post['designation_bn']) ? $post['designation_bn'] : '',
                    'designation_en' => !empty($post['designation_en']) ? $post['designation_en'] : '',
                    'position'       => !empty($post['position']) ? (int)$post['position'] : 1,
                    'message_bn'     => !empty($post['message_bn']) ? $post['message_bn'] : '',
                    'message_en'     => !empty($post['message_en']) ? $post['message_en'] : '',
                    'image'          => $image_name,
                    'active'         => isset($post['active']) ? 1 : 0,
                    'created_at'     => date('Y-m-d H:i:s'),
                    'updated_at'     => date('Y-m-d H:i:s'),
                ];

                $this->db->insert('founder', $insert_data);
                set_alert('success', translate('information_has_been_saved_successfully'));
                $url = base_url('frontend/founder' . (is_superadmin_loggedin() ? '?branch_id=' . $post['branch_id'] : ''));
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
            $this->db->where('branch_id', $branchID);
        }
        $this->data['founderlist'] = $this->db->order_by('position', 'asc')->order_by('id', 'asc')->get('founder')->result_array();
        $this->data['title'] = translate('founders');
        $this->data['sub_page'] = 'frontend/founder';
        $this->data['main_menu'] = 'frontend';
        $this->load->view('layout/index', $this->data);
    }

    public function edit($id = '')
    {
        if (!get_permission('frontend_founder', 'is_edit') && !is_superadmin_loggedin() && !get_permission('frontend_setting', 'is_view')) {
            access_denied();
        }

        $founder = $this->db->get_where('founder', ['id' => $id])->row_array();
        if (empty($founder)) {
            set_alert('error', translate('no_information_found'));
            redirect(base_url('frontend/founder'));
        }

        if ($_POST) {
            $this->validation_rules();
            if ($this->form_validation->run() !== false) {
                $post = $this->input->post();
                $branch_id = is_superadmin_loggedin() && !empty($post['branch_id']) ? $post['branch_id'] : $founder['branch_id'];

                $image_name = $this->upload_founder_image($founder['image']);

                $update_data = [
                    'branch_id'      => $branch_id,
                    'name_bn'        => $post['name_bn'],
                    'name_en'        => $post['name_en'],
                    'title_bn'       => !empty($post['title_bn']) ? $post['title_bn'] : '',
                    'title_en'       => !empty($post['title_en']) ? $post['title_en'] : '',
                    'designation_bn' => !empty($post['designation_bn']) ? $post['designation_bn'] : '',
                    'designation_en' => !empty($post['designation_en']) ? $post['designation_en'] : '',
                    'position'       => !empty($post['position']) ? (int)$post['position'] : 1,
                    'message_bn'     => !empty($post['message_bn']) ? $post['message_bn'] : '',
                    'message_en'     => !empty($post['message_en']) ? $post['message_en'] : '',
                    'image'          => !empty($image_name) ? $image_name : $founder['image'],
                    'active'         => isset($post['active']) ? 1 : 0,
                    'updated_at'     => date('Y-m-d H:i:s'),
                ];

                $this->db->where('id', $id)->update('founder', $update_data);
                set_alert('success', translate('information_has_been_updated_successfully'));
                $url = base_url('frontend/founder' . (is_superadmin_loggedin() ? '?branch_id=' . $branch_id : ''));
                echo json_encode(['status' => 'success', 'url' => $url]);
                exit();
            } else {
                $error = $this->form_validation->error_array();
                echo json_encode(['status' => 'fail', 'error' => $error]);
                exit();
            }
        }

        $this->data['founder'] = $founder;
        $this->data['headerelements'] = [
            'css' => [
                'vendor/dropify/css/dropify.min.css',
            ],
            'js' => [
                'vendor/dropify/js/dropify.min.js',
                'vendor/ckeditor/ckeditor.js',
            ],
        ];
        $this->data['title'] = translate('founders');
        $this->data['sub_page'] = 'frontend/founder_edit';
        $this->data['main_menu'] = 'frontend';
        $this->load->view('layout/index', $this->data);
    }

    public function delete($id = '')
    {
        if (!get_permission('frontend_founder', 'is_delete') && !is_superadmin_loggedin() && !get_permission('frontend_setting', 'is_view')) {
            access_denied();
        }

        $founder = $this->db->get_where('founder', ['id' => $id])->row_array();
        if (!empty($founder)) {
            $image = $founder['image'];
            if ($this->db->where('id', $id)->delete('founder')) {
                $destination = FCPATH . 'uploads/frontend/founder/';
                if (!empty($image) && file_exists($destination . $image)) {
                    @unlink($destination . $image);
                }
            }
            set_alert('success', translate('information_deleted'));
        }
        redirect(base_url('frontend/founder'));
    }

    private function upload_founder_image($prev_image = '')
    {
        if (!empty($_FILES['image']['name'])) {
            $destination = FCPATH . 'uploads/frontend/founder/';
            if (!file_exists($destination)) {
                mkdir($destination, 0777, true);
            }
            $extension = pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION);
            $filename = 'founder-' . time() . '-' . rand(100, 999) . '.' . $extension;
            if (move_uploaded_file($_FILES['image']['tmp_name'], $destination . $filename)) {
                if (!empty($prev_image) && file_exists($destination . $prev_image)) {
                    @unlink($destination . $prev_image);
                }
                return $filename;
            }
        }
        return $prev_image;
    }
}


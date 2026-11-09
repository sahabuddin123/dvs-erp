<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Board_results extends Admin_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('frontend_model');

        // Permission check
        if (!get_permission('frontend_section', 'is_view') && 
            !get_permission('frontend_setting', 'is_view') && 
            !is_superadmin_loggedin()) {
            access_denied();
        }

        $this->data['headerelements'] = array(
            'css' => array(
                'vendor/datatables/media/css/dataTables.bootstrap.css',
            ),
            'js' => array(
                'vendor/datatables/media/js/jquery.dataTables.min.js',
                'vendor/datatables/media/js/dataTables.bootstrap.min.js',
            ),
        );
    }

    public function index()
    {
        $branchID = $this->frontend_model->getBranchID();
        $this->data['branch_id'] = $branchID;

        // Process Add Form
        if ($this->input->post('save')) {
            $this->form_validation->set_rules('exam_name', translate('exam_name') . ' (বাংলা)', 'trim|required');
            $this->form_validation->set_rules('year', translate('year'), 'trim|required|numeric');
            $this->form_validation->set_rules('total_students', translate('total_students'), 'trim|required|numeric');
            $this->form_validation->set_rules('passed_students', translate('passed_students'), 'trim|required|numeric');

            if ($this->form_validation->run() == true) {
                $post = $this->input->post();
                
                $total_students = intval($post['total_students']);
                $passed_students = intval($post['passed_students']);
                $failed_students = isset($post['failed_students']) && $post['failed_students'] !== '' ? intval($post['failed_students']) : max(0, $total_students - $passed_students);

                $pass_percentage = !empty($post['pass_percentage']) 
                    ? floatval($post['pass_percentage']) 
                    : ($total_students > 0 ? round(($passed_students / $total_students) * 100, 2) : 0);

                $insert_data = array(
                    'branch_id' => !empty($post['branch_id']) ? $post['branch_id'] : $branchID,
                    'exam_name' => trim($post['exam_name']),
                    'exam_name_en' => trim($post['exam_name_en'] ?? ''),
                    'year' => intval($post['year']),
                    'total_students' => $total_students,
                    'passed_students' => $passed_students,
                    'failed_students' => $failed_students,
                    'pass_percentage' => $pass_percentage,
                    'gpa_5' => intval($post['gpa_5'] ?? 0),
                    'grade_a' => intval($post['grade_a'] ?? 0),
                    'grade_a_minus' => intval($post['grade_a_minus'] ?? 0),
                    'grade_b' => intval($post['grade_b'] ?? 0),
                    'grade_c' => intval($post['grade_c'] ?? 0),
                    'description' => trim($post['description'] ?? ''),
                    'status' => isset($post['status']) ? intval($post['status']) : 1,
                    'created_at' => date('Y-m-d H:i:s'),
                    'updated_at' => date('Y-m-d H:i:s'),
                );

                // Handle file upload
                if (!empty($_FILES['result_file']['name'])) {
                    $upload = $this->upload_result_file();
                    if ($upload['status']) {
                        $insert_data['result_file'] = $upload['file_name'];
                    } else {
                        set_alert('error', $upload['error']);
                        redirect(base_url('frontend/board_results'));
                    }
                }

                $this->db->insert('front_cms_board_results', $insert_data);
                set_alert('success', translate('information_has_been_saved_successfully'));
                redirect(base_url('frontend/board_results'));
            }
        }

        // Fetch all board results
        $this->db->select('front_cms_board_results.*, branch.name as branch_name');
        $this->db->from('front_cms_board_results');
        $this->db->join('branch', 'branch.id = front_cms_board_results.branch_id', 'left');
        if (!is_superadmin_loggedin() && !empty($branchID)) {
            $this->db->where('front_cms_board_results.branch_id', $branchID);
        }
        $this->db->order_by('front_cms_board_results.year', 'DESC');
        $this->db->order_by('front_cms_board_results.id', 'DESC');
        $this->data['results_list'] = $this->db->get()->result_array();

        $this->data['title'] = translate('frontend');
        $this->data['sub_page'] = 'frontend/board_results';
        $this->data['main_menu'] = 'frontend';
        $this->load->view('layout/index', $this->data);
    }

    public function edit($id = '')
    {
        if (empty($id)) {
            redirect(base_url('frontend/board_results'));
        }

        $result_item = $this->db->where('id', $id)->get('front_cms_board_results')->row_array();
        if (empty($result_item)) {
            set_alert('error', 'Board result record not found.');
            redirect(base_url('frontend/board_results'));
        }

        if ($this->input->post('save')) {
            $this->form_validation->set_rules('exam_name', translate('exam_name') . ' (বাংলা)', 'trim|required');
            $this->form_validation->set_rules('year', translate('year'), 'trim|required|numeric');
            $this->form_validation->set_rules('total_students', translate('total_students'), 'trim|required|numeric');
            $this->form_validation->set_rules('passed_students', translate('passed_students'), 'trim|required|numeric');

            if ($this->form_validation->run() == true) {
                $post = $this->input->post();
                
                $total_students = intval($post['total_students']);
                $passed_students = intval($post['passed_students']);
                $failed_students = isset($post['failed_students']) && $post['failed_students'] !== '' ? intval($post['failed_students']) : max(0, $total_students - $passed_students);

                $pass_percentage = !empty($post['pass_percentage']) 
                    ? floatval($post['pass_percentage']) 
                    : ($total_students > 0 ? round(($passed_students / $total_students) * 100, 2) : 0);

                $update_data = array(
                    'branch_id' => !empty($post['branch_id']) ? $post['branch_id'] : $result_item['branch_id'],
                    'exam_name' => trim($post['exam_name']),
                    'exam_name_en' => trim($post['exam_name_en'] ?? ''),
                    'year' => intval($post['year']),
                    'total_students' => $total_students,
                    'passed_students' => $passed_students,
                    'failed_students' => $failed_students,
                    'pass_percentage' => $pass_percentage,
                    'gpa_5' => intval($post['gpa_5'] ?? 0),
                    'grade_a' => intval($post['grade_a'] ?? 0),
                    'grade_a_minus' => intval($post['grade_a_minus'] ?? 0),
                    'grade_b' => intval($post['grade_b'] ?? 0),
                    'grade_c' => intval($post['grade_c'] ?? 0),
                    'description' => trim($post['description'] ?? ''),
                    'status' => isset($post['status']) ? intval($post['status']) : 1,
                    'updated_at' => date('Y-m-d H:i:s'),
                );

                // Handle file upload
                if (!empty($_FILES['result_file']['name'])) {
                    $upload = $this->upload_result_file();
                    if ($upload['status']) {
                        // Delete old file
                        if (!empty($result_item['result_file'])) {
                            $old_file = FCPATH . 'uploads/frontend/board_results/' . $result_item['result_file'];
                            if (file_exists($old_file)) {
                                @unlink($old_file);
                            }
                        }
                        $update_data['result_file'] = $upload['file_name'];
                    } else {
                        set_alert('error', $upload['error']);
                        redirect(base_url('frontend/board_results/edit/' . $id));
                    }
                }

                $this->db->where('id', $id)->update('front_cms_board_results', $update_data);
                set_alert('success', translate('information_has_been_updated_successfully'));
                redirect(base_url('frontend/board_results'));
            }
        }

        $this->data['board_result'] = $result_item;
        $this->data['title'] = translate('frontend');
        $this->data['sub_page'] = 'frontend/board_results_edit';
        $this->data['main_menu'] = 'frontend';
        $this->load->view('layout/index', $this->data);
    }

    public function delete($id = '')
    {
        if (!empty($id)) {
            $item = $this->db->where('id', $id)->get('front_cms_board_results')->row_array();
            if (!empty($item)) {
                if (!empty($item['result_file'])) {
                    $file_path = FCPATH . 'uploads/frontend/board_results/' . $item['result_file'];
                    if (file_exists($file_path)) {
                        @unlink($file_path);
                    }
                }
                $this->db->where('id', $id)->delete('front_cms_board_results');
                set_alert('success', translate('information_deleted'));
            }
        }
        redirect(base_url('frontend/board_results'));
    }

    public function change_status($id = '', $status = '')
    {
        if (!empty($id)) {
            $new_status = ($status == 1) ? 1 : 0;
            $this->db->where('id', $id)->update('front_cms_board_results', array(
                'status' => $new_status,
                'updated_at' => date('Y-m-d H:i:s'),
            ));
            set_alert('success', translate('status_has_been_updated_successfully'));
        }
        redirect(base_url('frontend/board_results'));
    }

    private function upload_result_file()
    {
        $config['upload_path']   = './uploads/frontend/board_results/';
        $config['allowed_types'] = 'pdf|jpg|jpeg|png';
        $config['max_size']      = 10240; // 10MB
        $config['encrypt_name']  = TRUE;

        $this->load->library('upload', $config);
        $this->upload->initialize($config);

        if ($this->upload->do_upload('result_file')) {
            $fileData = $this->upload->data();
            return array('status' => true, 'file_name' => $fileData['file_name']);
        } else {
            return array('status' => false, 'error' => $this->upload->display_errors('', ''));
        }
    }
}

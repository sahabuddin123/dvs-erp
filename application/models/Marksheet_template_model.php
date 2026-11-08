<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Marksheet_template_model extends MY_Model
{
    public function __construct()
    {
        parent::__construct();
    }

    public function getList()
    {
        $this->db->select('marksheet_template.*,branch.name as branchname');
        $this->db->from('marksheet_template');
        $this->db->join('branch', 'branch.id = marksheet_template.branch_id', 'left');
        if (!is_superadmin_loggedin()) {
            $this->db->where('marksheet_template.branch_id', get_loggedin_branch_id());
        }
        $this->db->order_by('marksheet_template.id', 'ASC');
        $result = $this->db->get()->result_array();
        return $result;
    }

    public function save($data)
    {
        $arrayLive = array(
            'branch_id' => $this->application_model->get_branch_id(),
            'name' => $data['marksheet_template_name'],
            'page_layout' => $data['page_layout'],
            'photo_style' => $data['photo_style'],
            'photo_size' => empty($data['photo_size']) ? 100 : $data['photo_size'],
            'top_space' => empty($data['top_space']) ? 0 : $data['top_space'],
            'bottom_space' => empty($data['bottom_space']) ? 0 : $data['bottom_space'],
            'right_space' => empty($data['right_space']) ? 0 : $data['right_space'],
            'left_space' => empty($data['left_space']) ? 0 : $data['left_space'],
            'background' => $this->fileupload('background_file', './uploads/marksheet/', $this->input->post('old_background_file')),
            'logo' => $this->fileupload('logo_file', './uploads/marksheet/', $this->input->post('old_logo_file')),
            'left_signature' => $this->fileupload('left_signature_file', './uploads/marksheet/', $this->input->post('old_left_signature_file')),
            'middle_signature' => $this->fileupload('middle_signature_file', './uploads/marksheet/', $this->input->post('old_middle_signature_file')),
            'right_signature' => $this->fileupload('right_signature_file', './uploads/marksheet/', $this->input->post('old_right_signature_file')),
            'header_content' => $this->input->post('header_content', false),
            'footer_content' => $this->input->post('footer_content', false),
            'attendance_percentage' => isset($_POST['attendance_percentage']) ? 1 : 0,
            'grading_scale' => isset($_POST['grading_scale']) ? 1 : 0,
            'position' => isset($_POST['position']) ? 1 : 0,
            'cumulative_average' => isset($_POST['cumulative_average']) ? 1 : 0,
            'class_average' => isset($_POST['class_average']) ? 1 : 0,
            'subject_position' => isset($_POST['subject_position']) ? 1 : 0,
            'remark' => isset($_POST['remark']) ? 1 : 0,
            'result' => isset($_POST['result']) ? 1 : 0,
        );
        if (!isset($data['marksheet_template_id'])) {
            $this->db->insert('marksheet_template', $arrayLive);
        } else {
            $this->db->where('id', $data['marksheet_template_id']);
            $this->db->update('marksheet_template', $arrayLive);
        }
    }

    public function tagsList()
    {
        $arrayTags = array();
        $arrayTags[] = '{name}';
        $arrayTags[] = '{gender}';
        $arrayTags[] = '{father_name}';
        $arrayTags[] = '{mother_name}';
        $arrayTags[] = '{student_photo}';
        $arrayTags[] = '{register_no}';
        $arrayTags[] = '{academic_session}';
        $arrayTags[] = '{roll}';
        $arrayTags[] = '{admission_date}';
        $arrayTags[] = '{class}';
        $arrayTags[] = '{section}';
        $arrayTags[] = '{category}';
        $arrayTags[] = '{caste}';
        $arrayTags[] = '{religion}';
        $arrayTags[] = '{blood_group}';
        $arrayTags[] = '{birthday}';
        $arrayTags[] = '{email}';
        $arrayTags[] = '{mobileno}';
        $arrayTags[] = '{present_address}';
        $arrayTags[] = '{permanent_address}';
        $arrayTags[] = '{exam_name}';
        $arrayTags[] = '{left_signature}';
        $arrayTags[] = '{middle_signature}';
        $arrayTags[] = '{right_signature}';
        $arrayTags[] = '{print_date}';
        $arrayTags[] = '{principal_comments}';
        $arrayTags[] = '{teacher_comments}';
        $arrayTags[] = '{logo}';
        $arrayTags[] = '{institute_name}';
        $arrayTags[] = '{institute_email}';
        $arrayTags[] = '{institute_address}';
        $arrayTags[] = '{institute_mobile_no}';
        
        return $arrayTags;
    }

    public function tagsReplace($studentData = '', $template = '', $extendsData = [], $header_content = '')
    {
        if (empty($template) || !isset($template[$header_content])) {
            return '';
        }
        $body = $template[$header_content];
        $photo_size = empty($template['photo_size']) ? 100 : $template['photo_size'];
        $photo_style = empty($template['photo_style']) ? 1 : $template['photo_style'];
        $tags = $this->tagsList();
        $userDetails = is_array($studentData) ? $studentData : [];
        $arr = array('{', '}');
        foreach ($tags as $tag) {
            $field = str_replace($arr, '', $tag);
            if ($field == 'student_photo') {
                $photoUrl = (!empty($userDetails['photo'])) ? get_image_url('student', $userDetails['photo']) : base_url('uploads/app_image/defualt.png');
                $photo = '<img class="' . ($photo_style == 1 ? '' : 'rounded') . '" src="' . $photoUrl . '" height="' . $photo_size . '">';
                $body = str_replace($tag, $photo, $body);
            } else if ($field == 'logo') {
                if (!empty($template['logo'])) {
                    $logo_ph = '<img src="' . base_url('uploads/marksheet/' . $template['logo']) . '">';
                    $body = str_replace($tag, $logo_ph, $body);
                } else {
                    $body = str_replace($tag, '', $body);
                }
            } else if ($field == 'left_signature') {
                if (!empty($template['left_signature'])) {
                    $signature_ph = '<img src="' . base_url('uploads/marksheet/' . $template['left_signature']) . '">';
                    $body = str_replace($tag, $signature_ph, $body);
                } else {
                    $body = str_replace($tag, '', $body);
                }
            } else if ($field == 'middle_signature') {
                if (!empty($template['middle_signature'])) {
                    $signature_ph = '<img src="' . base_url('uploads/marksheet/' . $template['middle_signature']) . '">';
                    $body = str_replace($tag, $signature_ph, $body);
                } else {
                    $body = str_replace($tag, '', $body);
                }
            } else if ($field == 'right_signature') {
                if (!empty($template['right_signature'])) {
                    $signature_ph = '<img src="' . base_url('uploads/marksheet/' . $template['right_signature']) . '">';
                    $body = str_replace($tag, $signature_ph, $body);
                } else {
                    $body = str_replace($tag, '', $body);
                }
            } else if ($field == 'present_address') {
                $body = str_replace($tag, (string)($userDetails['current_address'] ?? ''), $body);
            } else if ($field == 'academic_session') {
                $body = str_replace($tag, (string)($extendsData['schoolYear'] ?? ''), $body);
            } else if ($field == 'print_date') {
                $pDate = !empty($extendsData['print_date']) ? _d($extendsData['print_date']) : _d(date('Y-m-d'));
                $body = str_replace($tag, (string)$pDate, $body);
            } else if ($field == 'exam_name') {
                $body = str_replace($tag, (string)($extendsData['exam_name'] ?? ''), $body);
            } else if ($field == 'principal_comments') {
                $body = str_replace($tag, (string)($extendsData['principal_comments'] ?? ''), $body);
            } else if ($field == 'teacher_comments') {
                $body = str_replace($tag, (string)($extendsData['teacher_comments'] ?? ''), $body);
            } else {
                $val = (isset($userDetails[$field]) && $userDetails[$field] !== null) ? (string)$userDetails[$field] : '';
                $body = str_replace($tag, $val, $body);
            }
        }
        return $body;
    }

    public function getTemplate($templateID = '', $branchID = '')
    {
        return $this->db->where(array('id' => $templateID, 'branch_id' => $branchID))->get('marksheet_template')->row_array();
    }
}

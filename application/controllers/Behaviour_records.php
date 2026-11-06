<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Behaviour_records extends Admin_Controller
{
    public function __construct()
    {
        parent::__construct();
        if (!moduleIsEnabled('behaviour_records')) {
            access_denied();
        }
        $this->load->model('behaviour_records_model');
    }

    public function index()
    {
        $branchID = $this->application_model->get_branch_id();
        $classID = $this->input->get('class_id');
        $sectionID = $this->input->get('section_id');

        $this->data['branch_id'] = $branchID;
        $this->data['class_id'] = $classID;
        $this->data['section_id'] = $sectionID;

        $this->data['records'] = $this->behaviour_records_model->getRecords($branchID, $classID, $sectionID);
        $this->data['incidents'] = $this->behaviour_records_model->getIncidents($branchID);

        $this->data['title'] = 'Behaviour Records (শিক্ষার্থীর আচরণ মূল্যায়ন)';
        $this->data['sub_page'] = 'behaviour_records/index';
        $this->data['main_menu'] = 'behaviour_records';
        $this->load->view('layout/index', $this->data);
    }

    public function save()
    {
        if ($_POST) {
            $this->form_validation->set_rules('student_id', translate('student'), 'trim|required');
            $this->form_validation->set_rules('title', translate('title'), 'trim|required');
            $this->form_validation->set_rules('point', 'Point / পয়েন্ট', 'trim|required|numeric');
            $this->form_validation->set_rules('date', translate('date'), 'trim|required');

            if ($this->form_validation->run() !== false) {
                $post = $this->input->post();
                $this->behaviour_records_model->saveRecord($post);
                set_alert('success', translate('information_has_been_saved_successfully'));
                $array = array('status' => 'success');
            } else {
                $error = $this->form_validation->error_array();
                $array = array('status' => 'fail', 'error' => $error);
            }
            echo json_encode($array);
            exit();
        }
    }

    public function delete($id = '')
    {
        if (!empty($id)) {
            $this->behaviour_records_model->deleteRecord($id);
            set_alert('success', translate('information_deleted'));
        }
        redirect(base_url('behaviour_records'));
    }

    public function incidents()
    {
        $branchID = $this->application_model->get_branch_id();
        $this->data['branch_id'] = $branchID;
        $this->data['incidents'] = $this->behaviour_records_model->getIncidents($branchID);
        $this->data['title'] = 'Behaviour Incidents / আচরন রেকর্ড তালিকা';
        $this->data['sub_page'] = 'behaviour_records/incidents';
        $this->data['main_menu'] = 'behaviour_records';
        $this->load->view('layout/index', $this->data);
    }

    public function incident_save()
    {
        if ($_POST) {
            $this->form_validation->set_rules('title', translate('title'), 'trim|required');
            $this->form_validation->set_rules('point', 'Point / পয়েন্ট', 'trim|required|numeric');
            $this->form_validation->set_rules('type', translate('type'), 'trim|required');

            if ($this->form_validation->run() !== false) {
                $post = $this->input->post();
                $this->behaviour_records_model->saveIncident($post);
                set_alert('success', translate('information_has_been_saved_successfully'));
                $array = array('status' => 'success');
            } else {
                $error = $this->form_validation->error_array();
                $array = array('status' => 'fail', 'error' => $error);
            }
            echo json_encode($array);
            exit();
        }
    }

    public function incident_delete($id = '')
    {
        if (!empty($id)) {
            $this->behaviour_records_model->deleteIncident($id);
            set_alert('success', translate('information_deleted'));
        }
        redirect(base_url('behaviour_records/incidents'));
    }

    public function reports()
    {
        $branchID = $this->application_model->get_branch_id();
        $classID = $this->input->get('class_id');
        $sectionID = $this->input->get('section_id');

        $this->data['branch_id'] = $branchID;
        $this->data['class_id'] = $classID;
        $this->data['section_id'] = $sectionID;
        $this->data['report'] = array();

        if (!empty($classID)) {
            $this->data['report'] = $this->behaviour_records_model->getReport($branchID, $classID, $sectionID);
        }

        $this->data['title'] = 'Behaviour Report / আচরণ রিপোর্ট';
        $this->data['sub_page'] = 'behaviour_records/reports';
        $this->data['main_menu'] = 'behaviour_records';
        $this->load->view('layout/index', $this->data);
    }

    public function get_students_by_section()
    {
        $classID = $this->input->post('class_id');
        $sectionID = $this->input->post('section_id');
        $session_id = get_session_id();

        $this->db->select('s.id, s.first_name, s.last_name, s.register_no, e.roll');
        $this->db->from('enroll as e');
        $this->db->join('student as s', 's.id = e.student_id', 'inner');
        $this->db->where('e.class_id', $classID);
        if (!empty($sectionID)) {
            $this->db->where('e.section_id', $sectionID);
        }
        $this->db->where('e.session_id', $session_id);
        $this->db->order_by('e.roll', 'ASC');
        $students = $this->db->get()->result_array();

        $html = '<option value="">' . translate('select') . '</option>';
        foreach ($students as $stu) {
            $html .= '<option value="' . $stu['id'] . '">' . html_escape($stu['first_name'] . ' ' . $stu['last_name']) . ' (Roll: ' . ($stu['roll'] ?: 'N/A') . ', Reg: ' . $stu['register_no'] . ')</option>';
        }
        echo $html;
    }
}


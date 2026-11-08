<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Seat_plan extends Admin_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('seat_plan_model');
        $this->load->model('exam_model');
    }

    /**
     * Main Seat Plan Allocation Page
     */
    public function index()
    {
        if (!get_permission('seat_plan', 'is_view')) {
            access_denied();
        }

        $branchID = $this->application_model->get_branch_id();
        $sessionID = get_session_id();

        $exam_id = $this->input->get('exam_id');
        if (empty($exam_id) && $this->input->post('exam_id')) {
            $exam_id = $this->input->post('exam_id');
        }

        $hall_id = $this->input->get('hall_id');
        if (empty($hall_id) && $this->input->post('hall_id')) {
            $hall_id = $this->input->post('hall_id');
        }

        $class_id = $this->input->get('class_id');
        if (empty($class_id) && $this->input->post('class_id')) {
            $class_id = $this->input->post('class_id');
        }

        $section_id = $this->input->get('section_id');
        if (empty($section_id) && $this->input->post('section_id')) {
            $section_id = $this->input->post('section_id');
        }

        $this->data['branch_id'] = $branchID;
        $this->data['session_id'] = $sessionID;
        $this->data['exam_id'] = $exam_id;
        $this->data['hall_id'] = $hall_id;
        $this->data['class_id'] = $class_id;
        $this->data['section_id'] = $section_id;

        $this->data['exams'] = $this->seat_plan_model->getExams($branchID, $sessionID);
        $this->data['halls'] = $this->seat_plan_model->getHalls($branchID);
        $this->data['classes'] = $this->app_lib->getClass($branchID);

        $this->data['hall_summary'] = array();
        $this->data['seat_plans'] = array();

        if (!empty($exam_id)) {
            $this->data['hall_summary'] = $this->seat_plan_model->getHallCapacitySummary($exam_id, $branchID, $sessionID);
            $this->data['seat_plans'] = $this->seat_plan_model->getSeatPlanList($exam_id, $hall_id, $class_id, $section_id, $branchID, $sessionID);
        }

        $this->data['title'] = translate('seat_plan');
        $this->data['sub_page'] = 'seat_plan/index';
        $this->data['main_menu'] = 'seat_plan';
        $this->load->view('layout/index', $this->data);
    }

    /**
     * AJAX Generate Seat Plan
     */
    public function generate()
    {
        if (!get_permission('seat_plan', 'is_add')) {
            ajax_access_denied();
        }

        if ($_POST) {
            $post = $this->input->post();
            $result = $this->seat_plan_model->generateSeatPlan($post);

            if ($result['status']) {
                set_alert('success', $result['message']);
                $url = base_url('seat_plan?exam_id=' . $post['exam_id']);
                echo json_encode(array('status' => 'success', 'message' => $result['message'], 'url' => $url));
            } else {
                echo json_encode(array('status' => 'fail', 'error' => array('error' => $result['message'])));
            }
            exit();
        }
    }

    /**
     * AJAX Update Individual Seat
     */
    public function update_seat()
    {
        if (!get_permission('seat_plan', 'is_edit')) {
            ajax_access_denied();
        }

        $id = $this->input->post('id');
        $hall_id = $this->input->post('hall_id');
        $seat_no = $this->input->post('seat_no');
        $bench_no = $this->input->post('bench_no');

        if (empty($id) || empty($hall_id) || empty($seat_no)) {
            echo json_encode(array('status' => 'fail', 'message' => translate('required_fields_missing')));
            exit();
        }

        $update = array(
            'hall_id' => $hall_id,
            'seat_no' => $seat_no,
            'bench_no' => $bench_no,
        );

        $this->seat_plan_model->updateSeat($id, $update);
        echo json_encode(array('status' => 'success', 'message' => translate('information_has_been_updated_successfully')));
        exit();
    }

    /**
     * Delete Single Seat Allocation
     */
    public function delete_seat($id = '')
    {
        if (!get_permission('seat_plan', 'is_delete')) {
            access_denied();
        }

        if (!empty($id)) {
            $this->seat_plan_model->deleteSeat($id);
            set_alert('success', translate('information_deleted'));
        }

        $referrer = $this->input->server('HTTP_REFERER');
        if (!empty($referrer)) {
            redirect($referrer);
        } else {
            redirect(base_url('seat_plan'));
        }
    }

    /**
     * Clear Plan for an Exam / Hall
     */
    public function clear_plan()
    {
        if (!get_permission('seat_plan', 'is_delete')) {
            access_denied();
        }

        $exam_id = $this->input->post('exam_id');
        $hall_id = $this->input->post('hall_id');
        $branchID = $this->application_model->get_branch_id();
        $sessionID = get_session_id();

        if (!empty($exam_id)) {
            $this->seat_plan_model->clearPlan($exam_id, $hall_id, $branchID, $sessionID);
            set_alert('success', translate('seat_plan_cleared_successfully'));
        }

        $referrer = $this->input->server('HTTP_REFERER');
        if (!empty($referrer)) {
            redirect($referrer);
        } else {
            redirect(base_url('seat_plan'));
        }
    }

    /**
     * Print Seat Cards / Slips for Student Desks
     */
    public function print_card()
    {
        if (!get_permission('seat_plan', 'is_view')) {
            access_denied();
        }

        $branchID = $this->application_model->get_branch_id();
        $sessionID = get_session_id();

        $exam_id = $this->input->get('exam_id');
        if (empty($exam_id) && $this->input->post('exam_id')) {
            $exam_id = $this->input->post('exam_id');
        }

        $hall_id = $this->input->get('hall_id');
        if (empty($hall_id) && $this->input->post('hall_id')) {
            $hall_id = $this->input->post('hall_id');
        }

        $class_id = $this->input->get('class_id');
        if (empty($class_id) && $this->input->post('class_id')) {
            $class_id = $this->input->post('class_id');
        }

        $section_id = $this->input->get('section_id');
        if (empty($section_id) && $this->input->post('section_id')) {
            $section_id = $this->input->post('section_id');
        }

        $this->data['branch_id'] = $branchID;
        $this->data['session_id'] = $sessionID;
        $this->data['exam_id'] = $exam_id;
        $this->data['hall_id'] = $hall_id;
        $this->data['class_id'] = $class_id;
        $this->data['section_id'] = $section_id;

        $this->data['exams'] = $this->seat_plan_model->getExams($branchID, $sessionID);
        $this->data['halls'] = $this->seat_plan_model->getHalls($branchID);
        $this->data['classes'] = $this->app_lib->getClass($branchID);

        $this->data['cards'] = array();
        if (!empty($exam_id)) {
            $this->data['cards'] = $this->seat_plan_model->getSeatCards($exam_id, $hall_id, $class_id, $section_id, $branchID, $sessionID);
        }

        $this->data['title'] = translate('print_seat_cards');
        $this->data['sub_page'] = 'seat_plan/print_card';
        $this->data['main_menu'] = 'seat_plan';
        $this->load->view('layout/index', $this->data);
    }

    /**
     * Room-Wise Attendance & Invigilator Master Sheet
     */
    public function room_report()
    {
        if (!get_permission('seat_plan', 'is_view')) {
            access_denied();
        }

        $branchID = $this->application_model->get_branch_id();
        $sessionID = get_session_id();

        $exam_id = $this->input->get('exam_id');
        if (empty($exam_id) && $this->input->post('exam_id')) {
            $exam_id = $this->input->post('exam_id');
        }

        $hall_id = $this->input->get('hall_id');
        if (empty($hall_id) && $this->input->post('hall_id')) {
            $hall_id = $this->input->post('hall_id');
        }

        $this->data['branch_id'] = $branchID;
        $this->data['session_id'] = $sessionID;
        $this->data['exam_id'] = $exam_id;
        $this->data['hall_id'] = $hall_id;

        $this->data['exams'] = $this->seat_plan_model->getExams($branchID, $sessionID);
        $this->data['halls'] = $this->seat_plan_model->getHalls($branchID);

        $this->data['report_data'] = null;
        if (!empty($exam_id) && !empty($hall_id)) {
            $this->data['report_data'] = $this->seat_plan_model->getRoomAttendanceSheet($exam_id, $hall_id, $branchID, $sessionID);
        }

        $this->data['title'] = translate('room_attendance_sheet');
        $this->data['sub_page'] = 'seat_plan/room_report';
        $this->data['main_menu'] = 'seat_plan';
        $this->load->view('layout/index', $this->data);
    }
}

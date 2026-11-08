<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Seat_plan_model extends MY_Model
{
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Get active exams for session & branch
     */
    public function getExams($branch_id = null, $session_id = null)
    {
        $branch_id = !empty($branch_id) ? $branch_id : $this->application_model->get_branch_id();
        $session_id = !empty($session_id) ? $session_id : get_session_id();

        $this->db->select('e.*, t.name as term_name');
        $this->db->from('exam as e');
        $this->db->join('exam_term as t', 't.id = e.term_id', 'left');
        $this->db->where('e.session_id', $session_id);
        if (!empty($branch_id)) {
            $this->db->where('e.branch_id', $branch_id);
        }
        $this->db->order_by('e.id', 'DESC');
        return $this->db->get()->result_array();
    }

    /**
     * Get exam halls with seats capacity
     */
    public function getHalls($branch_id = null)
    {
        $branch_id = !empty($branch_id) ? $branch_id : $this->application_model->get_branch_id();

        $this->db->select('h.*, b.name as branch_name');
        $this->db->from('exam_hall as h');
        $this->db->join('branch as b', 'b.id = h.branch_id', 'left');
        if (!empty($branch_id)) {
            $this->db->where('h.branch_id', $branch_id);
        }
        $this->db->order_by('h.hall_no', 'ASC');
        return $this->db->get()->result_array();
    }

    /**
     * Get hall capacity & allocated summary for an exam
     */
    public function getHallCapacitySummary($exam_id, $branch_id = null, $session_id = null)
    {
        $halls = $this->getHalls($branch_id);
        $session_id = !empty($session_id) ? $session_id : get_session_id();

        $summary = array();
        foreach ($halls as $hall) {
            $this->db->where('exam_id', $exam_id);
            $this->db->where('hall_id', $hall['id']);
            $this->db->where('session_id', $session_id);
            $allocated = $this->db->count_all_results('exam_seat_plan');

            $capacity = (int)$hall['seats'];
            $remaining = max(0, $capacity - $allocated);
            $percentage = $capacity > 0 ? round(($allocated / $capacity) * 100) : 0;

            $summary[] = array(
                'hall_id' => $hall['id'],
                'hall_no' => $hall['hall_no'],
                'capacity' => $capacity,
                'allocated' => $allocated,
                'remaining' => $remaining,
                'percentage' => min(100, $percentage)
            );
        }
        return $summary;
    }

    /**
     * Get existing seat plan list with filters
     */
    public function getSeatPlanList($exam_id, $hall_id = null, $class_id = null, $section_id = null, $branch_id = null, $session_id = null)
    {
        $session_id = !empty($session_id) ? $session_id : get_session_id();
        $branch_id = !empty($branch_id) ? $branch_id : $this->application_model->get_branch_id();

        $this->db->select('sp.*, s.register_no, s.first_name, s.last_name, s.photo, s.gender, e.roll, c.name as class_name, sec.name as section_name, h.hall_no, h.seats as hall_capacity, ex.name as exam_name');
        $this->db->from('exam_seat_plan as sp');
        $this->db->join('enroll as e', 'e.id = sp.enroll_id', 'inner');
        $this->db->join('student as s', 's.id = sp.student_id', 'inner');
        $this->db->join('class as c', 'c.id = sp.class_id', 'left');
        $this->db->join('section as sec', 'sec.id = sp.section_id', 'left');
        $this->db->join('exam_hall as h', 'h.id = sp.hall_id', 'left');
        $this->db->join('exam as ex', 'ex.id = sp.exam_id', 'left');

        $this->db->where('sp.exam_id', $exam_id);
        $this->db->where('sp.session_id', $session_id);
        if (!empty($branch_id)) {
            $this->db->where('sp.branch_id', $branch_id);
        }
        if (!empty($hall_id)) {
            $this->db->where('sp.hall_id', $hall_id);
        }
        if (!empty($class_id)) {
            $this->db->where('sp.class_id', $class_id);
        }
        if (!empty($section_id)) {
            $this->db->where('sp.section_id', $section_id);
        }

        $this->db->order_by('CAST(sp.seat_no AS UNSIGNED)', 'ASC');
        $this->db->order_by('sp.seat_no', 'ASC');
        $this->db->order_by('e.roll', 'ASC');
        return $this->db->get()->result_array();
    }

    /**
     * Get students available for allocation
     */
    public function getStudentsForAllocation($branch_id, $session_id, $class_ids = array(), $section_ids = array())
    {
        $this->db->select('e.id as enroll_id, e.student_id, e.class_id, e.section_id, e.roll, s.register_no, s.first_name, s.last_name, s.photo, c.name as class_name, sec.name as section_name');
        $this->db->from('enroll as e');
        $this->db->join('student as s', 's.id = e.student_id', 'inner');
        $this->db->join('class as c', 'c.id = e.class_id', 'left');
        $this->db->join('section as sec', 'sec.id = e.section_id', 'left');

        $this->db->where('e.branch_id', $branch_id);
        $this->db->where('e.session_id', $session_id);
        $this->db->where('s.active', 1);

        if (!empty($class_ids)) {
            if (is_array($class_ids)) {
                $this->db->where_in('e.class_id', $class_ids);
            } else {
                $this->db->where('e.class_id', $class_ids);
            }
        }

        if (!empty($section_ids)) {
            if (is_array($section_ids)) {
                $this->db->where_in('e.section_id', $section_ids);
            } else {
                $this->db->where('e.section_id', $section_ids);
            }
        }

        $this->db->order_by('e.class_id', 'ASC');
        $this->db->order_by('e.section_id', 'ASC');
        $this->db->order_by('e.roll', 'ASC');
        return $this->db->get()->result_array();
    }

    /**
     * Generate Seat Plan Allocation
     */
    public function generateSeatPlan($post)
    {
        $branch_id = !empty($post['branch_id']) ? $post['branch_id'] : $this->application_model->get_branch_id();
        $session_id = !empty($post['session_id']) ? $post['session_id'] : get_session_id();
        $exam_id = (int)$post['exam_id'];

        $hall_ids = isset($post['hall_ids']) ? (array)$post['hall_ids'] : array();
        $class_ids = isset($post['class_ids']) ? (array)$post['class_ids'] : array();
        $section_ids = isset($post['section_ids']) ? (array)$post['section_ids'] : array();

        $strategy = isset($post['strategy']) ? $post['strategy'] : 'sequential';
        $seats_per_bench = !empty($post['seats_per_bench']) ? max(1, (int)$post['seats_per_bench']) : 2;
        $bench_prefix = isset($post['bench_prefix']) ? trim($post['bench_prefix']) : 'B-';
        $starting_seat = !empty($post['starting_seat']) ? max(1, (int)$post['starting_seat']) : 1;
        $overwrite = !empty($post['overwrite']) ? true : false;

        if (empty($exam_id)) {
            return array('status' => false, 'message' => 'Please select an exam.');
        }

        if (empty($hall_ids)) {
            return array('status' => false, 'message' => 'Please select at least one exam hall.');
        }

        if (empty($class_ids)) {
            return array('status' => false, 'message' => 'Please select at least one class.');
        }

        // Fetch halls and capacities
        $this->db->where_in('id', $hall_ids);
        if (!empty($branch_id)) {
            $this->db->where('branch_id', $branch_id);
        }
        $halls = $this->db->get('exam_hall')->result_array();
        if (empty($halls)) {
            return array('status' => false, 'message' => 'Selected exam halls not found.');
        }

        // Handle overwrite
        if ($overwrite) {
            $this->db->where('exam_id', $exam_id);
            $this->db->where('session_id', $session_id);
            if (!empty($branch_id)) {
                $this->db->where('branch_id', $branch_id);
            }
            $this->db->where_in('hall_id', $hall_ids);
            if (!empty($class_ids)) {
                $this->db->where_in('class_id', $class_ids);
            }
            $this->db->delete('exam_seat_plan');
        }

        // Fetch students to allocate
        $students = $this->getStudentsForAllocation($branch_id, $session_id, $class_ids, $section_ids);
        if (empty($students)) {
            return array('status' => false, 'message' => 'No enrolled students found for the selected criteria.');
        }

        // Filter out already allocated students if overwrite was not selected
        if (!$overwrite) {
            $this->db->select('enroll_id');
            $this->db->where('exam_id', $exam_id);
            $this->db->where('session_id', $session_id);
            $already_allocated = $this->db->get('exam_seat_plan')->result_array();
            $allocated_enroll_ids = array_column($already_allocated, 'enroll_id');

            if (!empty($allocated_enroll_ids)) {
                $students = array_filter($students, function($s) use ($allocated_enroll_ids) {
                    return !in_array($s['enroll_id'], $allocated_enroll_ids);
                });
                $students = array_values($students);
            }
        }

        if (empty($students)) {
            return array('status' => false, 'message' => 'All students in the selected classes are already allocated to seats.');
        }

        // Rearrange students based on selected strategy
        $arranged_students = $this->applyArrangementStrategy($students, $strategy);

        // Allocate students across selected halls according to capacities
        $allocations = array();
        $student_index = 0;
        $total_students = count($arranged_students);
        $total_allocated = 0;

        foreach ($halls as $hall) {
            $hall_id = $hall['id'];
            $capacity = (int)$hall['seats'];

            // Find current count already allocated in this hall
            $this->db->where('exam_id', $exam_id);
            $this->db->where('hall_id', $hall_id);
            $this->db->where('session_id', $session_id);
            $current_in_hall = $this->db->count_all_results('exam_seat_plan');
            $available_slots = max(0, $capacity - $current_in_hall);

            if ($available_slots <= 0) {
                continue;
            }

            $seat_num = $current_in_hall + $starting_seat;

            for ($slot = 0; $slot < $available_slots && $student_index < $total_students; $slot++) {
                $stu = $arranged_students[$student_index];

                // Calculate bench number
                $bench_number = (int)ceil($seat_num / $seats_per_bench);
                $bench_label = !empty($bench_prefix) ? $bench_prefix . $bench_number : (string)$bench_number;

                $allocations[] = array(
                    'branch_id' => $branch_id,
                    'session_id' => $session_id,
                    'exam_id' => $exam_id,
                    'hall_id' => $hall_id,
                    'class_id' => $stu['class_id'],
                    'section_id' => $stu['section_id'],
                    'student_id' => $stu['student_id'],
                    'enroll_id' => $stu['enroll_id'],
                    'seat_no' => (string)$seat_num,
                    'bench_no' => $bench_label,
                    'created_at' => date('Y-m-d H:i:s'),
                );

                $seat_num++;
                $student_index++;
                $total_allocated++;
            }

            if ($student_index >= $total_students) {
                break;
            }
        }

        if (!empty($allocations)) {
            $this->db->insert_batch('exam_seat_plan', $allocations);
        }

        $unallocated = $total_students - $total_allocated;
        $msg = sprintf(
            '%d students allocated successfully across %d hall(s).',
            $total_allocated,
            count($halls)
        );
        if ($unallocated > 0) {
            $msg .= sprintf(' %d student(s) could not be seated due to hall capacity limits.', $unallocated);
        }

        return array(
            'status' => true,
            'allocated' => $total_allocated,
            'unallocated' => $unallocated,
            'message' => $msg
        );
    }

    /**
     * Apply Anti-Cheating or Sequential arrangement strategies
     */
    protected function applyArrangementStrategy($students, $strategy)
    {
        if ($strategy === 'random') {
            shuffle($students);
            return $students;
        }

        if ($strategy === 'alternate_class') {
            // Group by class
            $groups = array();
            foreach ($students as $s) {
                $groups[$s['class_id']][] = $s;
            }
            // Round robin interleave
            $interleaved = array();
            $has_more = true;
            $class_keys = array_keys($groups);

            while ($has_more) {
                $has_more = false;
                foreach ($class_keys as $cid) {
                    if (!empty($groups[$cid])) {
                        $interleaved[] = array_shift($groups[$cid]);
                        if (!empty($groups[$cid])) {
                            $has_more = true;
                        }
                    }
                }
            }
            return $interleaved;
        }

        if ($strategy === 'alternate_section') {
            // Group by class and section
            $groups = array();
            foreach ($students as $s) {
                $groups[$s['section_id']][] = $s;
            }
            $interleaved = array();
            $has_more = true;
            $sec_keys = array_keys($groups);

            while ($has_more) {
                $has_more = false;
                foreach ($sec_keys as $sid) {
                    if (!empty($groups[$sid])) {
                        $interleaved[] = array_shift($groups[$sid]);
                        if (!empty($groups[$sid])) {
                            $has_more = true;
                        }
                    }
                }
            }
            return $interleaved;
        }

        // Default: sequential by Class, Section, Roll
        usort($students, function ($a, $b) {
            if ($a['class_id'] != $b['class_id']) {
                return $a['class_id'] <=> $b['class_id'];
            }
            if ($a['section_id'] != $b['section_id']) {
                return $a['section_id'] <=> $b['section_id'];
            }
            return (int)$a['roll'] <=> (int)$b['roll'];
        });

        return $students;
    }

    /**
     * Update individual seat allocation
     */
    public function updateSeat($id, $data)
    {
        $update = array(
            'hall_id' => (int)$data['hall_id'],
            'seat_no' => trim($data['seat_no']),
            'bench_no' => trim($data['bench_no']),
            'updated_at' => date('Y-m-d H:i:s'),
        );
        $this->db->where('id', $id);
        return $this->db->update('exam_seat_plan', $update);
    }

    /**
     * Delete individual seat allocation
     */
    public function deleteSeat($id)
    {
        $this->db->where('id', $id);
        return $this->db->delete('exam_seat_plan');
    }

    /**
     * Clear seat plan for an entire exam or hall
     */
    public function clearPlan($exam_id, $hall_id = null, $branch_id = null, $session_id = null)
    {
        $this->db->where('exam_id', (int)$exam_id);
        if (!empty($hall_id)) {
            $this->db->where('hall_id', (int)$hall_id);
        }
        if (!empty($branch_id)) {
            $this->db->where('branch_id', $branch_id);
        }
        if (!empty($session_id)) {
            $this->db->where('session_id', $session_id);
        }
        return $this->db->delete('exam_seat_plan');
    }

    /**
     * Get seat cards data for printing
     */
    public function getSeatCards($exam_id, $hall_id = null, $class_id = null, $section_id = null, $branch_id = null, $session_id = null)
    {
        return $this->getSeatPlanList($exam_id, $hall_id, $class_id, $section_id, $branch_id, $session_id);
    }

    /**
     * Get room attendance sheet for invigilators
     */
    public function getRoomAttendanceSheet($exam_id, $hall_id, $branch_id = null, $session_id = null)
    {
        $session_id = !empty($session_id) ? $session_id : get_session_id();
        $branch_id = !empty($branch_id) ? $branch_id : $this->application_model->get_branch_id();

        // Get hall info
        $this->db->where('id', $hall_id);
        $hall = $this->db->get('exam_hall')->row_array();

        // Get exam info
        $this->db->select('e.*, t.name as term_name');
        $this->db->from('exam as e');
        $this->db->join('exam_term as t', 't.id = e.term_id', 'left');
        $this->db->where('e.id', $exam_id);
        $exam = $this->db->get()->row_array();

        // Get seated students
        $students = $this->getSeatPlanList($exam_id, $hall_id, null, null, $branch_id, $session_id);

        return array(
            'hall' => $hall,
            'exam' => $exam,
            'students' => $students,
            'total_seated' => count($students)
        );
    }
}

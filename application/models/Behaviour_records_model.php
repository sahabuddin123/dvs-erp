<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Behaviour_records_model extends MY_Model
{
    public function __construct()
    {
        parent::__construct();
    }

    public function getIncidents($branch_id = 0)
    {
        if (!empty($branch_id)) {
            $this->db->group_start();
            $this->db->where('branch_id', $branch_id);
            $this->db->or_where('branch_id', 0);
            $this->db->group_end();
        }
        $this->db->order_by('id', 'DESC');
        return $this->db->get('behaviour_incidents')->result_array();
    }

    public function getIncidentById($id)
    {
        return $this->db->where('id', $id)->get('behaviour_incidents')->row_array();
    }

    public function saveIncident($data)
    {
        $arrayData = array(
            'branch_id'   => !empty($data['branch_id']) ? $data['branch_id'] : get_loggedin_branch_id(),
            'title'       => trim($data['title']),
            'point'       => (int)$data['point'],
            'type'        => in_array($data['type'], array('positive', 'negative')) ? $data['type'] : 'positive',
            'description' => isset($data['description']) ? trim($data['description']) : '',
        );

        if (!empty($data['id'])) {
            $this->db->where('id', $data['id']);
            $this->db->update('behaviour_incidents', $arrayData);
            return $data['id'];
        } else {
            $this->db->insert('behaviour_incidents', $arrayData);
            return $this->db->insert_id();
        }
    }

    public function deleteIncident($id)
    {
        $this->db->where('id', $id)->delete('behaviour_incidents');
    }

    public function getRecords($branch_id = '', $class_id = '', $section_id = '', $student_id = '')
    {
        $this->db->select('sbr.*, s.first_name, s.last_name, s.register_no, c.name as class_name, se.name as section_name, e.roll, staff.name as staff_name');
        $this->db->from('student_behaviour_records as sbr');
        $this->db->join('enroll as e', 'e.id = sbr.enroll_id', 'left');
        $this->db->join('student as s', 's.id = sbr.student_id', 'left');
        $this->db->join('class as c', 'c.id = e.class_id', 'left');
        $this->db->join('section as se', 'se.id = e.section_id', 'left');
        $this->db->join('staff', 'staff.id = sbr.assigned_by', 'left');

        if (!empty($branch_id)) {
            $this->db->where('sbr.branch_id', $branch_id);
        }
        if (!empty($class_id)) {
            $this->db->where('e.class_id', $class_id);
        }
        if (!empty($section_id)) {
            $this->db->where('e.section_id', $section_id);
        }
        if (!empty($student_id)) {
            $this->db->where('sbr.student_id', $student_id);
        }

        $this->db->order_by('sbr.date', 'DESC');
        $this->db->order_by('sbr.id', 'DESC');
        return $this->db->get()->result_array();
    }

    public function saveRecord($data)
    {
        $session_id = get_session_id();
        $student_id = (int)$data['student_id'];
        
        // Find enroll id
        $enroll = $this->db->where(array('student_id' => $student_id, 'session_id' => $session_id))->get('enroll')->row_array();
        if (empty($enroll)) {
            $enroll = $this->db->where('student_id', $student_id)->order_by('id', 'DESC')->limit(1)->get('enroll')->row_array();
        }
        $enroll_id = !empty($enroll['id']) ? $enroll['id'] : 0;
        $branch_id = !empty($data['branch_id']) ? $data['branch_id'] : (!empty($enroll['branch_id']) ? $enroll['branch_id'] : get_loggedin_branch_id());

        $incident_id = !empty($data['incident_id']) ? (int)$data['incident_id'] : null;
        $title = trim($data['title']);
        $type = in_array($data['type'], array('positive', 'negative')) ? $data['type'] : 'positive';
        $point = (int)$data['point'];

        if ($incident_id) {
            $inc = $this->getIncidentById($incident_id);
            if ($inc) {
                if (empty($title)) $title = $inc['title'];
                if (empty($point)) $point = $inc['point'];
                $type = $inc['type'];
            }
        }

        $arrayData = array(
            'branch_id'   => $branch_id,
            'session_id'  => $session_id,
            'student_id'  => $student_id,
            'enroll_id'   => $enroll_id,
            'incident_id' => $incident_id,
            'title'       => $title,
            'point'       => $point,
            'type'        => $type,
            'date'        => date('Y-m-d', strtotime($data['date'])),
            'comment'     => isset($data['comment']) ? trim($data['comment']) : '',
            'assigned_by' => get_loggedin_user_id(),
        );

        if (!empty($data['id'])) {
            $this->db->where('id', $data['id']);
            $this->db->update('student_behaviour_records', $arrayData);
            return $data['id'];
        } else {
            $this->db->insert('student_behaviour_records', $arrayData);
            return $this->db->insert_id();
        }
    }

    public function deleteRecord($id)
    {
        $this->db->where('id', $id)->delete('student_behaviour_records');
    }

    public function getStudentScore($student_id)
    {
        $pos = $this->db->select_sum('point')->where(array('student_id' => $student_id, 'type' => 'positive'))->get('student_behaviour_records')->row()->point;
        $neg = $this->db->select_sum('point')->where(array('student_id' => $student_id, 'type' => 'negative'))->get('student_behaviour_records')->row()->point;
        $pos = (int)$pos;
        $neg = (int)$neg;
        return array(
            'positive' => $pos,
            'negative' => $neg,
            'net'      => $pos - $neg,
        );
    }

    public function getReport($branch_id = '', $class_id = '', $section_id = '')
    {
        $session_id = get_session_id();
        $this->db->select('e.id as enroll_id, e.student_id, e.roll, s.first_name, s.last_name, s.register_no, c.name as class_name, se.name as section_name,
            COALESCE(SUM(CASE WHEN sbr.type = "positive" THEN sbr.point ELSE 0 END), 0) as pos_points,
            COALESCE(SUM(CASE WHEN sbr.type = "negative" THEN sbr.point ELSE 0 END), 0) as neg_points,
            COUNT(sbr.id) as total_incidents');
        $this->db->from('enroll as e');
        $this->db->join('student as s', 's.id = e.student_id', 'inner');
        $this->db->join('class as c', 'c.id = e.class_id', 'left');
        $this->db->join('section as se', 'se.id = e.section_id', 'left');
        $this->db->join('student_behaviour_records as sbr', 'sbr.student_id = e.student_id AND sbr.session_id = ' . $session_id, 'left');

        if (!empty($branch_id)) {
            $this->db->where('e.branch_id', $branch_id);
        }
        if (!empty($class_id)) {
            $this->db->where('e.class_id', $class_id);
        }
        if (!empty($section_id)) {
            $this->db->where('e.section_id', $section_id);
        }
        $this->db->where('e.session_id', $session_id);
        $this->db->group_by('e.student_id');
        $this->db->order_by('e.roll', 'ASC');

        $result = $this->db->get()->result_array();
        foreach ($result as &$row) {
            $row['net_points'] = (int)$row['pos_points'] - (int)$row['neg_points'];
        }
        return $result;
    }
}


<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Lesson_plan_model extends MY_Model
{
    public function __construct()
    {
        parent::__construct();
    }

    // --- TOPIC METHODS ---

    public function getTopicList($branch_id = '', $class_id = '', $section_id = '', $subject_id = '')
    {
        $this->db->select('t.*, b.name as branch_name, c.name as class_name, s.name as section_name, sub.name as subject_name, sub.subject_code, (SELECT COUNT(l.id) FROM lesson_plan_lesson as l WHERE l.topic_id = t.id) as total_lessons');
        $this->db->from('lesson_plan_topic as t');
        $this->db->join('branch as b', 'b.id = t.branch_id', 'left');
        $this->db->join('class as c', 'c.id = t.class_id', 'left');
        $this->db->join('section as s', 's.id = t.section_id', 'left');
        $this->db->join('subject as sub', 'sub.id = t.subject_id', 'left');

        if (!empty($branch_id)) {
            $this->db->where('t.branch_id', $branch_id);
        }
        if (!empty($class_id)) {
            $this->db->where('t.class_id', $class_id);
        }
        if (!empty($section_id)) {
            $this->db->where('t.section_id', $section_id);
        }
        if (!empty($subject_id)) {
            $this->db->where('t.subject_id', $subject_id);
        }

        $this->db->order_by('t.id', 'DESC');
        return $this->db->get()->result_array();
    }

    public function getTopic($id)
    {
        return $this->db->where('id', $id)->get('lesson_plan_topic')->row_array();
    }

    public function saveTopic($data)
    {
        $topicData = [
            'branch_id'  => !empty($data['branch_id']) ? (int)$data['branch_id'] : get_loggedin_branch_id(),
            'class_id'   => (int)$data['class_id'],
            'section_id' => (int)$data['section_id'],
            'subject_id' => (int)$data['subject_id'],
            'name'       => trim($data['name']),
        ];

        if (!empty($data['id'])) {
            $this->db->where('id', $data['id'])->update('lesson_plan_topic', $topicData);
            return $data['id'];
        } else {
            $this->db->insert('lesson_plan_topic', $topicData);
            return $this->db->insert_id();
        }
    }

    public function deleteTopic($id)
    {
        // Delete associated lessons and syllabus entries
        $lessons = $this->db->where('topic_id', $id)->get('lesson_plan_lesson')->result_array();
        foreach ($lessons as $lesson) {
            $this->deleteLesson($lesson['id']);
        }
        $this->db->where('topic_id', $id)->delete('lesson_plan_syllabus');
        $this->db->where('id', $id)->delete('lesson_plan_topic');
    }

    public function getTopicsBySubject($class_id, $section_id, $subject_id)
    {
        $this->db->where('class_id', $class_id);
        $this->db->where('section_id', $section_id);
        $this->db->where('subject_id', $subject_id);
        $this->db->order_by('name', 'ASC');
        return $this->db->get('lesson_plan_topic')->result_array();
    }

    // --- LESSON METHODS ---

    public function getLessonList($branch_id = '', $class_id = '', $section_id = '', $subject_id = '', $topic_id = '')
    {
        $this->db->select('l.*, t.name as topic_name, b.name as branch_name, c.name as class_name, s.name as section_name, sub.name as subject_name, sub.subject_code, syl.status as syllabus_status, syl.date as syllabus_date');
        $this->db->from('lesson_plan_lesson as l');
        $this->db->join('lesson_plan_topic as t', 't.id = l.topic_id', 'left');
        $this->db->join('branch as b', 'b.id = l.branch_id', 'left');
        $this->db->join('class as c', 'c.id = l.class_id', 'left');
        $this->db->join('section as s', 's.id = l.section_id', 'left');
        $this->db->join('subject as sub', 'sub.id = l.subject_id', 'left');
        $this->db->join('lesson_plan_syllabus as syl', 'syl.lesson_id = l.id', 'left');

        if (!empty($branch_id)) {
            $this->db->where('l.branch_id', $branch_id);
        }
        if (!empty($class_id)) {
            $this->db->where('l.class_id', $class_id);
        }
        if (!empty($section_id)) {
            $this->db->where('l.section_id', $section_id);
        }
        if (!empty($subject_id)) {
            $this->db->where('l.subject_id', $subject_id);
        }
        if (!empty($topic_id)) {
            $this->db->where('l.topic_id', $topic_id);
        }

        $this->db->order_by('l.id', 'DESC');
        return $this->db->get()->result_array();
    }

    public function getLesson($id)
    {
        return $this->db->where('id', $id)->get('lesson_plan_lesson')->row_array();
    }

    public function saveLesson($data)
    {
        $lessonData = [
            'branch_id'  => !empty($data['branch_id']) ? (int)$data['branch_id'] : get_loggedin_branch_id(),
            'class_id'   => (int)$data['class_id'],
            'section_id' => (int)$data['section_id'],
            'subject_id' => (int)$data['subject_id'],
            'topic_id'   => (int)$data['topic_id'],
            'name'       => trim($data['name']),
        ];

        if (!empty($data['id'])) {
            $this->db->where('id', $data['id'])->update('lesson_plan_lesson', $lessonData);
            return $data['id'];
        } else {
            $this->db->insert('lesson_plan_lesson', $lessonData);
            return $this->db->insert_id();
        }
    }

    public function deleteLesson($id)
    {
        $syllabus = $this->db->where('lesson_id', $id)->get('lesson_plan_syllabus')->result_array();
        foreach ($syllabus as $s) {
            if (!empty($s['attachment']) && file_exists('./uploads/attachments/lesson_plan/' . $s['attachment'])) {
                unlink('./uploads/attachments/lesson_plan/' . $s['attachment']);
            }
        }
        $this->db->where('lesson_id', $id)->delete('lesson_plan_syllabus');
        $this->db->where('id', $id)->delete('lesson_plan_lesson');
    }

    public function getLessonsByTopic($topic_id)
    {
        $this->db->where('topic_id', $topic_id);
        $this->db->order_by('name', 'ASC');
        return $this->db->get('lesson_plan_lesson')->result_array();
    }

    // --- SYLLABUS / MANAGE METHODS ---

    public function getSyllabusList($branch_id = '', $class_id = '', $section_id = '', $subject_id = '', $status = '')
    {
        $this->db->select('syl.*, l.name as lesson_name, t.name as topic_name, b.name as branch_name, c.name as class_name, sec.name as section_name, sub.name as subject_name, sub.subject_code, st.name as staff_name');
        $this->db->from('lesson_plan_syllabus as syl');
        $this->db->join('lesson_plan_lesson as l', 'l.id = syl.lesson_id', 'left');
        $this->db->join('lesson_plan_topic as t', 't.id = syl.topic_id', 'left');
        $this->db->join('branch as b', 'b.id = syl.branch_id', 'left');
        $this->db->join('class as c', 'c.id = syl.class_id', 'left');
        $this->db->join('section as sec', 'sec.id = syl.section_id', 'left');
        $this->db->join('subject as sub', 'sub.id = syl.subject_id', 'left');
        $this->db->join('staff as st', 'st.id = syl.created_by', 'left');

        if (!empty($branch_id)) {
            $this->db->where('syl.branch_id', $branch_id);
        }
        if (!empty($class_id)) {
            $this->db->where('syl.class_id', $class_id);
        }
        if (!empty($section_id)) {
            $this->db->where('syl.section_id', $section_id);
        }
        if (!empty($subject_id)) {
            $this->db->where('syl.subject_id', $subject_id);
        }
        if (!empty($status)) {
            $this->db->where('syl.status', $status);
        }

        $this->db->order_by('syl.date', 'DESC');
        $this->db->order_by('syl.id', 'DESC');
        return $this->db->get()->result_array();
    }

    public function getSyllabus($id)
    {
        return $this->db->where('id', $id)->get('lesson_plan_syllabus')->row_array();
    }

    public function saveSyllabus($data)
    {
        $syllabusData = [
            'branch_id'   => !empty($data['branch_id']) ? (int)$data['branch_id'] : get_loggedin_branch_id(),
            'class_id'    => (int)$data['class_id'],
            'section_id'  => (int)$data['section_id'],
            'subject_id'  => (int)$data['subject_id'],
            'topic_id'    => (int)$data['topic_id'],
            'lesson_id'   => (int)$data['lesson_id'],
            'date'        => date('Y-m-d', strtotime($data['date'])),
            'time_from'   => !empty($data['time_from']) ? date('H:i:s', strtotime($data['time_from'])) : null,
            'time_to'     => !empty($data['time_to']) ? date('H:i:s', strtotime($data['time_to'])) : null,
            'status'      => !empty($data['status']) ? $data['status'] : 'pending',
            'note'        => !empty($data['note']) ? $data['note'] : null,
            'created_by'  => get_loggedin_user_id(),
        ];

        if (!empty($data['attachment'])) {
            $syllabusData['attachment'] = $data['attachment'];
        }

        // Check if an entry already exists for this lesson
        $existing = $this->db->where('lesson_id', $syllabusData['lesson_id'])->get('lesson_plan_syllabus')->row_array();

        if (!empty($data['id'])) {
            $syllabusData['updated_at'] = date('Y-m-d H:i:s');
            $this->db->where('id', $data['id'])->update('lesson_plan_syllabus', $syllabusData);
            return $data['id'];
        } elseif ($existing) {
            $syllabusData['updated_at'] = date('Y-m-d H:i:s');
            $this->db->where('id', $existing['id'])->update('lesson_plan_syllabus', $syllabusData);
            return $existing['id'];
        } else {
            $this->db->insert('lesson_plan_syllabus', $syllabusData);
            return $this->db->insert_id();
        }
    }

    public function deleteSyllabus($id)
    {
        $s = $this->getSyllabus($id);
        if ($s && !empty($s['attachment']) && file_exists('./uploads/attachments/lesson_plan/' . $s['attachment'])) {
            unlink('./uploads/attachments/lesson_plan/' . $s['attachment']);
        }
        $this->db->where('id', $id)->delete('lesson_plan_syllabus');
    }

    // --- OVERVIEW & ANALYTICS ---

    public function getOverviewSummary($branch_id, $class_id, $section_id, $subject_id = '')
    {
        // Fetch topics
        $topics = $this->getTopicsBySubject($class_id, $section_id, $subject_id);
        $totalTopics = count($topics);
        $totalLessons = 0;
        $completedLessons = 0;
        $inProgressLessons = 0;
        $pendingLessons = 0;

        $detailedTopics = [];

        foreach ($topics as $t) {
            $lessons = $this->db->select('l.*, syl.id as syllabus_id, syl.date as syllabus_date, syl.time_from, syl.time_to, syl.status as syllabus_status, syl.note as syllabus_note, syl.attachment as syllabus_attachment')
                ->from('lesson_plan_lesson as l')
                ->join('lesson_plan_syllabus as syl', 'syl.lesson_id = l.id', 'left')
                ->where('l.topic_id', $t['id'])
                ->order_by('l.id', 'ASC')
                ->get()
                ->result_array();

            $tCompleted = 0;
            $tTotal = count($lessons);
            $totalLessons += $tTotal;

            foreach ($lessons as $l) {
                $st = !empty($l['syllabus_status']) ? $l['syllabus_status'] : 'pending';
                if ($st === 'completed') {
                    $completedLessons++;
                    $tCompleted++;
                } elseif ($st === 'in_progress') {
                    $inProgressLessons++;
                } else {
                    $pendingLessons++;
                }
            }

            $tProgress = ($tTotal > 0) ? round(($tCompleted / $tTotal) * 100) : 0;
            $t['lessons'] = $lessons;
            $t['total_lessons'] = $tTotal;
            $t['completed_lessons'] = $tCompleted;
            $t['progress'] = $tProgress;

            $detailedTopics[] = $t;
        }

        $overallProgress = ($totalLessons > 0) ? round(($completedLessons / $totalLessons) * 100) : 0;

        return [
            'total_topics'       => $totalTopics,
            'total_lessons'      => $totalLessons,
            'completed_lessons'  => $completedLessons,
            'in_progress_lessons'=> $inProgressLessons,
            'pending_lessons'    => $pendingLessons,
            'overall_progress'   => $overallProgress,
            'topics'             => $detailedTopics,
        ];
    }
}

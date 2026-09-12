<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Biometric_model extends MY_Model
{
    public function __construct()
    {
        parent::__construct();
        $this->load->library('zkteco_lib');
    }

    public function getDevices($branchID = '')
    {
        $this->db->select('biometric_device.*, branch.name as branch_name');
        $this->db->from('biometric_device');
        $this->db->join('branch', 'branch.id = biometric_device.branch_id', 'left');
        if (!empty($branchID) && !is_superadmin_loggedin()) {
            $this->db->where('biometric_device.branch_id', $branchID);
        }
        $this->db->order_by('biometric_device.id', 'DESC');
        return $this->db->get()->result_array();
    }

    public function getDevice($id)
    {
        return $this->db->where('id', $id)->get('biometric_device')->row_array();
    }

    public function saveDevice($data)
    {
        $device = [
            'name' => $data['name'],
            'ip_address' => trim($data['ip_address']),
            'port' => !empty($data['port']) ? (int)$data['port'] : 4370,
            'protocol' => !empty($data['protocol']) ? $data['protocol'] : 'UDP',
            'device_type' => !empty($data['device_type']) ? $data['device_type'] : 'both',
            'branch_id' => !empty($data['branch_id']) ? (int)$data['branch_id'] : get_loggedin_branch_id(),
            'status' => isset($data['status']) ? (int)$data['status'] : 1,
            'auto_clear' => isset($data['auto_clear']) ? (int)$data['auto_clear'] : 0,
        ];

        if (!empty($data['id'])) {
            $device['updated_at'] = date('Y-m-d H:i:s');
            $this->db->where('id', $data['id'])->update('biometric_device', $device);
            return $data['id'];
        } else {
            $this->db->insert('biometric_device', $device);
            return $this->db->insert_id();
        }
    }

    public function deleteDevice($id)
    {
        $this->db->where('id', $id)->delete('biometric_device');
    }

    public function getSettings($branchID = 1)
    {
        $row = $this->db->where('branch_id', $branchID)->get('biometric_settings')->row_array();
        if (empty($row)) {
            return [
                'branch_id' => $branchID,
                'student_in_time' => '09:00:00',
                'student_out_time' => '17:00:00',
                'staff_in_time' => '09:00:00',
                'staff_out_time' => '17:00:00',
                'auto_late_detect' => 1,
                'adms_push_enabled' => 1
            ];
        }
        return $row;
    }

    public function saveSettings($data)
    {
        $branchID = !empty($data['branch_id']) ? $data['branch_id'] : get_loggedin_branch_id();
        $fields = [
            'branch_id' => $branchID,
            'student_in_time' => $data['student_in_time'],
            'student_out_time' => $data['student_out_time'],
            'staff_in_time' => $data['staff_in_time'],
            'staff_out_time' => $data['staff_out_time'],
            'auto_late_detect' => isset($data['auto_late_detect']) ? (int)$data['auto_late_detect'] : 0,
            'adms_push_enabled' => isset($data['adms_push_enabled']) ? (int)$data['adms_push_enabled'] : 1,
        ];

        $exist = $this->db->where('branch_id', $branchID)->get('biometric_settings')->row();
        if ($exist) {
            $this->db->where('branch_id', $branchID)->update('biometric_settings', $fields);
        } else {
            $this->db->insert('biometric_settings', $fields);
        }
    }

    public function getUserMaps($branchID, $userType = '')
    {
        $this->db->select('bum.*, 
            CASE 
                WHEN bum.user_type = "student" THEN CONCAT(s.first_name, " ", IFNULL(s.last_name, ""))
                WHEN bum.user_type = "staff" THEN st.name
            END as full_name,
            CASE 
                WHEN bum.user_type = "student" THEN s.register_no
                WHEN bum.user_type = "staff" THEN st.staff_id
            END as code_no,
            c.name as class_name, sec.name as section_name, r.name as role_name', FALSE);
        $this->db->from('biometric_user_map as bum');
        $this->db->join('enroll as e', 'e.id = bum.user_id AND bum.user_type = "student"', 'left');
        $this->db->join('student as s', 's.id = e.student_id', 'left');
        $this->db->join('class as c', 'c.id = e.class_id', 'left');
        $this->db->join('section as sec', 'sec.id = e.section_id', 'left');
        $this->db->join('staff as st', 'st.id = bum.user_id AND bum.user_type = "staff"', 'left');
        $this->db->join('login_credential as lc', 'lc.user_id = st.id AND lc.role NOT IN (6,7)', 'left');
        $this->db->join('roles as r', 'r.id = lc.role', 'left');

        if (!empty($branchID)) {
            $this->db->where('bum.branch_id', $branchID);
        }
        if (!empty($userType)) {
            $this->db->where('bum.user_type', $userType);
        }
        $this->db->order_by('bum.id', 'DESC');
        return $this->db->get()->result_array();
    }

    public function saveUserMap($deviceUserID, $userType, $userID, $branchID)
    {
        $data = [
            'device_user_id' => trim($deviceUserID),
            'user_type' => $userType,
            'user_id' => (int)$userID,
            'branch_id' => (int)$branchID
        ];

        $exist = $this->db->where(['device_user_id' => $data['device_user_id'], 'branch_id' => $branchID])->get('biometric_user_map')->row();
        if ($exist) {
            $this->db->where('id', $exist->id)->update('biometric_user_map', $data);
            return $exist->id;
        } else {
            $this->db->insert('biometric_user_map', $data);
            return $this->db->insert_id();
        }
    }

    public function deleteUserMap($id)
    {
        $this->db->where('id', $id)->delete('biometric_user_map');
    }

    public function autoMapUsers($branchID, $mode = 'register_no')
    {
        $count = 0;
        // Auto map Students: match device_user_id with student.register_no or enroll.roll
        $session_id = get_session_id();
        $this->db->select('e.id as enroll_id, e.roll, s.register_no');
        $this->db->from('enroll as e');
        $this->db->join('student as s', 's.id = e.student_id', 'inner');
        $this->db->where('e.branch_id', $branchID);
        if (!empty($session_id)) {
            $this->db->where('e.session_id', $session_id);
        }
        $students = $this->db->get()->result_array();

        foreach ($students as $stu) {
            $devId = ($mode == 'roll') ? trim($stu['roll']) : trim($stu['register_no']);
            if (!empty($devId)) {
                $this->saveUserMap($devId, 'student', $stu['enroll_id'], $branchID);
                $count++;
            }
        }

        // Auto map Staff: match device_user_id with staff.staff_id or staff.id
        $this->db->select('staff.id, staff.staff_id');
        $this->db->from('staff');
        $this->db->where('staff.branch_id', $branchID);
        $staffs = $this->db->get()->result_array();

        foreach ($staffs as $st) {
            $devId = trim($st['staff_id']);
            if (empty($devId)) {
                $devId = (string)$st['id'];
            }
            if (!empty($devId)) {
                $this->saveUserMap($devId, 'staff', $st['id'], $branchID);
                $count++;
            }
        }

        return $count;
    }

    public function processPunches($punches, $deviceID = null, $branchID = 1)
    {
        $device = null;
        if (!empty($deviceID)) {
            $device = $this->getDevice($deviceID);
            if ($device) {
                $branchID = $device['branch_id'];
            }
        }

        $settings = $this->getSettings($branchID);
        $syncedCount = 0;

        foreach ($punches as $punch) {
            $rawUID = trim($punch['id'] ?? $punch['pin'] ?? '');
            $punchTime = trim($punch['timestamp'] ?? $punch['time'] ?? '');

            if (empty($rawUID) || empty($punchTime)) {
                continue;
            }

            $punchTimestamp = strtotime($punchTime);
            if (!$punchTimestamp) {
                continue;
            }

            $punchDate = date('Y-m-d', $punchTimestamp);
            $punchTimeOnly = date('H:i:s', $punchTimestamp);

            // Check mapping
            $map = $this->db->where(['device_user_id' => $rawUID, 'branch_id' => $branchID])->get('biometric_user_map')->row();
            
            // Fallback student check by register_no or roll
            if (!$map) {
                $stu = $this->db->select('enroll.id')->from('enroll')->join('student', 'student.id = enroll.student_id')
                    ->where('enroll.branch_id', $branchID)
                    ->group_start()
                        ->where('student.register_no', $rawUID)
                        ->or_where('enroll.roll', $rawUID)
                    ->group_end()->get()->row();
                if ($stu) {
                    $this->saveUserMap($rawUID, 'student', $stu->id, $branchID);
                    $map = (object)['user_type' => 'student', 'user_id' => $stu->id];
                }
            }

            // Fallback staff check by staff_id
            if (!$map) {
                $st = $this->db->where(['staff_id' => $rawUID, 'branch_id' => $branchID])->get('staff')->row();
                if ($st) {
                    $this->saveUserMap($rawUID, 'staff', $st->id, $branchID);
                    $map = (object)['user_type' => 'staff', 'user_id' => $st->id];
                }
            }

            $userType = $map ? $map->user_type : 'unknown';
            $mappedID = $map ? $map->user_id : null;
            $remark = "Biometric: " . ($device ? $device['name'] : 'ZKTeco');

            // Log raw punch
            $this->db->insert('biometric_log', [
                'device_id' => $deviceID,
                'device_user_id' => $rawUID,
                'punch_time' => $punchTime,
                'punch_type' => $punch['type'] ?? 0,
                'user_type' => $userType,
                'mapped_id' => $mappedID,
                'status' => $map ? 'synced' : 'unmapped',
                'remark' => $remark,
                'branch_id' => $branchID
            ]);

            if (!$map) {
                continue;
            }

            if ($userType === 'student') {
                $inTimeSetting = $settings['student_in_time'] ?: '09:00:00';
                $status = 'P';
                if ($settings['auto_late_detect'] && strtotime($punchTimeOnly) > strtotime($inTimeSetting)) {
                    $status = 'L';
                }

                $existing = $this->db->where(['enroll_id' => $mappedID, 'date' => $punchDate])->get('student_attendance')->row();
                if (empty($existing)) {
                    $this->db->insert('student_attendance', [
                        'enroll_id' => $mappedID,
                        'date' => $punchDate,
                        'in_time' => $punchTimeOnly,
                        'status' => $status,
                        'qr_code' => 2, // 2 indicates Biometric
                        'remark' => $remark,
                        'branch_id' => $branchID
                    ]);
                    $syncedCount++;
                } else {
                    // Update out_time if subsequent punch
                    if (empty($existing->out_time) || strtotime($punchTimeOnly) > strtotime($existing->out_time)) {
                        $this->db->where('id', $existing->id)->update('student_attendance', [
                            'out_time' => $punchTimeOnly,
                            'remark' => $remark
                        ]);
                        $syncedCount++;
                    }
                }
            } elseif ($userType === 'staff') {
                $inTimeSetting = $settings['staff_in_time'] ?: '09:00:00';
                $status = 'P';
                if ($settings['auto_late_detect'] && strtotime($punchTimeOnly) > strtotime($inTimeSetting)) {
                    $status = 'L';
                }

                $existing = $this->db->where(['staff_id' => $mappedID, 'date' => $punchDate])->get('staff_attendance')->row();
                if (empty($existing)) {
                    $this->db->insert('staff_attendance', [
                        'staff_id' => $mappedID,
                        'date' => $punchDate,
                        'in_time' => $punchTimeOnly,
                        'status' => $status,
                        'qr_code' => 2,
                        'remark' => $remark,
                        'branch_id' => $branchID
                    ]);
                    $syncedCount++;
                } else {
                    if (empty($existing->out_time) || strtotime($punchTimeOnly) > strtotime($existing->out_time)) {
                        $this->db->where('id', $existing->id)->update('staff_attendance', [
                            'out_time' => $punchTimeOnly,
                            'remark' => $remark
                        ]);
                        $syncedCount++;
                    }
                }
            }
        }

        if ($device) {
            $this->db->where('id', $device['id'])->update('biometric_device', [
                'last_sync' => date('Y-m-d H:i:s')
            ]);
        }

        return $syncedCount;
    }

    public function getLogs($branchID = '', $date = '', $limit = 200)
    {
        $this->db->select('biometric_log.*, biometric_device.name as device_name');
        $this->db->from('biometric_log');
        $this->db->join('biometric_device', 'biometric_device.id = biometric_log.device_id', 'left');
        if (!empty($branchID) && !is_superadmin_loggedin()) {
            $this->db->where('biometric_log.branch_id', $branchID);
        }
        if (!empty($date)) {
            $this->db->where('DATE(biometric_log.punch_time)', $date);
        }
        $this->db->order_by('biometric_log.id', 'DESC');
        $this->db->limit($limit);
        return $this->db->get()->result_array();
    }
}

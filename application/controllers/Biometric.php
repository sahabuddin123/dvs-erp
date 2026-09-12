<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Biometric extends MY_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('biometric_model');
        $this->load->library('zkteco_lib');

        $method = $this->router->fetch_method();
        $public_methods = ['adms', 'cdata', 'cron_sync'];
        if (!in_array($method, $public_methods)) {
            if (!is_loggedin()) {
                $this->session->set_userdata('redirect_url', current_url());
                redirect(base_url('authentication'), 'refresh');
            }
        }
    }

    public function index()
    {
        if (!get_permission('biometric_device', 'is_view')) {
            access_denied();
        }

        $branchID = $this->application_model->get_branch_id();
        $this->data['devices'] = $this->biometric_model->getDevices($branchID);
        $this->data['recent_logs'] = $this->biometric_model->getLogs($branchID, date('Y-m-d'), 10);
        $this->data['settings'] = $this->biometric_model->getSettings($branchID);
        
        $this->data['title'] = translate('biometric') . ' ' . translate('attendance');
        $this->data['sub_page'] = 'biometric/index';
        $this->data['main_menu'] = 'biometric_attendance';
        $this->load->view('layout/index', $this->data);
    }

    public function devices()
    {
        if (!get_permission('biometric_device', 'is_view')) {
            access_denied();
        }

        $branchID = $this->application_model->get_branch_id();
        if ($_POST) {
            if (!get_permission('biometric_device', 'is_add')) {
                access_denied();
            }
            $this->form_validation->set_rules('name', translate('name'), 'trim|required');
            $this->form_validation->set_rules('ip_address', 'IP Address', 'trim|required|valid_ip');
            $this->form_validation->set_rules('port', 'Port', 'trim|required|numeric');
            if (is_superadmin_loggedin()) {
                $this->form_validation->set_rules('branch_id', translate('branch'), 'required');
            }

            if ($this->form_validation->run() == true) {
                $postData = $this->input->post();
                if (!is_superadmin_loggedin()) {
                    $postData['branch_id'] = $branchID;
                }
                $this->biometric_model->saveDevice($postData);
                set_alert('success', translate('information_has_been_saved_successfully'));
                redirect(base_url('biometric/devices'));
            }
        }

        $this->data['devices'] = $this->biometric_model->getDevices($branchID);
        $this->data['title'] = translate('devices');
        $this->data['sub_page'] = 'biometric/devices';
        $this->data['main_menu'] = 'biometric_attendance';
        $this->load->view('layout/index', $this->data);
    }

    public function device_edit($id)
    {
        if (!get_permission('biometric_device', 'is_edit')) {
            access_denied();
        }

        $device = $this->biometric_model->getDevice($id);
        if (!$device) {
            redirect(base_url('biometric/devices'));
        }

        if ($_POST) {
            $this->form_validation->set_rules('name', translate('name'), 'trim|required');
            $this->form_validation->set_rules('ip_address', 'IP Address', 'trim|required|valid_ip');
            $this->form_validation->set_rules('port', 'Port', 'trim|required|numeric');

            if ($this->form_validation->run() == true) {
                $postData = $this->input->post();
                $postData['id'] = $id;
                $this->biometric_model->saveDevice($postData);
                set_alert('success', translate('information_has_been_updated_successfully'));
                redirect(base_url('biometric/devices'));
            }
        }

        $this->data['device'] = $device;
        $this->data['title'] = translate('edit') . ' ' . translate('device');
        $this->data['sub_page'] = 'biometric/device_edit';
        $this->data['main_menu'] = 'biometric_attendance';
        $this->load->view('layout/index', $this->data);
    }

    public function device_delete($id)
    {
        if (!get_permission('biometric_device', 'is_delete')) {
            access_denied();
        }
        $this->biometric_model->deleteDevice($id);
        set_alert('success', translate('information_deleted'));
        redirect(base_url('biometric/devices'));
    }

    public function test_connection($deviceID)
    {
        if (!get_permission('biometric_device', 'is_view')) {
            ajax_access_denied();
        }

        $device = $this->biometric_model->getDevice($deviceID);
        if (!$device) {
            echo json_encode(['status' => 'fail', 'message' => 'Device not found']);
            return;
        }

        $this->zkteco_lib->init($device['ip_address'], $device['port'], $device['protocol']);
        if ($this->zkteco_lib->connect()) {
            $info = $this->zkteco_lib->getDeviceInfo();
            $this->zkteco_lib->disconnect();
            echo json_encode([
                'status' => 'success',
                'message' => 'Connected successfully!',
                'data' => $info
            ]);
        } else {
            echo json_encode([
                'status' => 'fail',
                'message' => 'Connection failed: ' . ($this->zkteco_lib->error ?: 'Device unreachable. Check device IP, port 4370 and network cable/WiFi.')
            ]);
        }
    }

    public function sync($deviceID = null)
    {
        if (!get_permission('biometric_sync', 'is_view')) {
            access_denied();
        }

        $devices = [];
        if (!empty($deviceID)) {
            $dev = $this->biometric_model->getDevice($deviceID);
            if ($dev) {
                $devices[] = $dev;
            }
        } else {
            $branchID = $this->application_model->get_branch_id();
            $devices = $this->biometric_model->getDevices($branchID);
        }

        $totalSynced = 0;
        $errors = [];

        foreach ($devices as $device) {
            if (!$device['status']) {
                continue;
            }

            $this->zkteco_lib->init($device['ip_address'], $device['port'], $device['protocol']);
            if ($this->zkteco_lib->connect()) {
                $punches = $this->zkteco_lib->getAttendance();
                if (!empty($punches)) {
                    $synced = $this->biometric_model->processPunches($punches, $device['id'], $device['branch_id']);
                    $totalSynced += $synced;

                    if ($device['auto_clear']) {
                        $this->zkteco_lib->clearAttendance();
                    }
                }
                $this->zkteco_lib->disconnect();
            } else {
                $errors[] = "{$device['name']} ({$device['ip_address']}): " . ($this->zkteco_lib->error ?: 'Unreachable');
            }
        }

        if (empty($errors)) {
            set_alert('success', "Sync complete! {$totalSynced} attendance punch records processed.");
        } else {
            set_alert('warning', "Sync finished with issues: " . implode('; ', $errors));
        }

        redirect(base_url('biometric'));
    }

    public function mapping()
    {
        if (!get_permission('biometric_mapping', 'is_view')) {
            access_denied();
        }

        $branchID = $this->application_model->get_branch_id();
        $this->data['branch_id'] = $branchID;
        $this->data['user_maps'] = $this->biometric_model->getUserMaps($branchID);
        $this->data['title'] = translate('biometric') . ' ' . translate('user_mapping');
        $this->data['sub_page'] = 'biometric/mapping';
        $this->data['main_menu'] = 'biometric_attendance';
        $this->load->view('layout/index', $this->data);
    }

    public function save_mapping()
    {
        if (!get_permission('biometric_mapping', 'is_add')) {
            ajax_access_denied();
        }

        $this->form_validation->set_rules('device_user_id', 'Device User ID / PIN', 'trim|required');
        $this->form_validation->set_rules('user_type', 'User Type', 'trim|required');
        $this->form_validation->set_rules('user_id', 'User', 'trim|required');

        if ($this->form_validation->run() == true) {
            $branchID = $this->application_model->get_branch_id();
            $this->biometric_model->saveUserMap(
                $this->input->post('device_user_id'),
                $this->input->post('user_type'),
                $this->input->post('user_id'),
                $branchID
            );
            echo json_encode(['status' => 'success', 'message' => translate('information_has_been_saved_successfully')]);
        } else {
            echo json_encode(['status' => 'fail', 'error' => $this->form_validation->error_array()]);
        }
    }

    public function auto_map()
    {
        if (!get_permission('biometric_mapping', 'is_add')) {
            ajax_access_denied();
        }

        $branchID = $this->application_model->get_branch_id();
        $mode = $this->input->post('mode') ?: 'register_no';
        $count = $this->biometric_model->autoMapUsers($branchID, $mode);
        echo json_encode(['status' => 'success', 'message' => "Auto-mapped {$count} users successfully!"]);
    }

    public function delete_mapping($id)
    {
        if (!get_permission('biometric_mapping', 'is_delete')) {
            access_denied();
        }
        $this->biometric_model->deleteUserMap($id);
        set_alert('success', translate('information_deleted'));
        redirect(base_url('biometric/mapping'));
    }

    public function logs()
    {
        if (!get_permission('biometric_log', 'is_view')) {
            access_denied();
        }

        $branchID = $this->application_model->get_branch_id();
        $date = $this->input->get('date') ?: date('Y-m-d');
        $this->data['selected_date'] = $date;
        $this->data['logs'] = $this->biometric_model->getLogs($branchID, $date, 300);
        $this->data['title'] = translate('biometric') . ' ' . translate('logs');
        $this->data['sub_page'] = 'biometric/logs';
        $this->data['main_menu'] = 'biometric_attendance';
        $this->load->view('layout/index', $this->data);
    }

    public function settings()
    {
        if (!get_permission('biometric_device', 'is_edit')) {
            access_denied();
        }

        $branchID = $this->application_model->get_branch_id();
        if ($_POST) {
            $this->form_validation->set_rules('student_in_time', 'Student In Time', 'trim|required');
            $this->form_validation->set_rules('student_out_time', 'Student Out Time', 'trim|required');
            $this->form_validation->set_rules('staff_in_time', 'Staff In Time', 'trim|required');
            $this->form_validation->set_rules('staff_out_time', 'Staff Out Time', 'trim|required');

            if ($this->form_validation->run() == true) {
                $post = $this->input->post();
                $post['branch_id'] = $branchID;
                $this->biometric_model->saveSettings($post);
                set_alert('success', translate('the_configuration_has_been_updated'));
                redirect(base_url('biometric/settings'));
            }
        }

        $this->data['settings'] = $this->biometric_model->getSettings($branchID);
        $this->data['title'] = translate('biometric') . ' ' . translate('settings');
        $this->data['sub_page'] = 'biometric/settings';
        $this->data['main_menu'] = 'biometric_attendance';
        $this->load->view('layout/index', $this->data);
    }

    public function import_csv()
    {
        if (!get_permission('biometric_sync', 'is_add')) {
            access_denied();
        }

        if (isset($_FILES['csv_file']['name']) && !empty($_FILES['csv_file']['name'])) {
            $file = $_FILES['csv_file']['tmp_name'];
            $handle = fopen($file, 'r');
            $punches = [];
            $branchID = $this->application_model->get_branch_id();

            while (($line = fgetcsv($handle, 1000, ",")) !== false) {
                // Typical CSV format: PIN/UserID, Timestamp, [Type/Status]
                if (isset($line[0]) && isset($line[1]) && strtotime($line[1])) {
                    $punches[] = [
                        'id' => trim($line[0]),
                        'timestamp' => trim($line[1]),
                        'type' => isset($line[2]) ? (int)$line[2] : 0
                    ];
                }
            }
            fclose($handle);

            if (!empty($punches)) {
                $count = $this->biometric_model->processPunches($punches, null, $branchID);
                set_alert('success', "Imported and processed {$count} attendance records from CSV!");
            } else {
                set_alert('error', "No valid attendance records found in file. Ensure format: UserID, YYYY-MM-DD HH:MM:SS");
            }
        }
        redirect(base_url('biometric'));
    }

    /**
     * ADMS / Cloud Push Protocol Endpoint
     * ZKTeco devices with ADMS push attendance punches via HTTP POST to this endpoint
     */
    public function adms()
    {
        $input = file_get_contents('php://input');
        $deviceSN = $this->input->get('SN') ?: '';

        if (!empty($input)) {
            $lines = explode("\n", trim($input));
            $punches = [];
            foreach ($lines as $line) {
                $cols = preg_split("/[\t,]/", trim($line));
                if (count($cols) >= 2) {
                    $punches[] = [
                        'id' => trim($cols[0]),
                        'timestamp' => trim($cols[1]),
                        'type' => isset($cols[2]) ? (int)$cols[2] : 0
                    ];
                }
            }
            if (!empty($punches)) {
                $this->biometric_model->processPunches($punches, null, 1);
            }
        }
        echo "OK";
        exit;
    }

    public function cdata()
    {
        $this->adms();
    }

    /**
     * Background Cron Sync for Scheduled Tasks
     */
    public function cron_sync()
    {
        $devices = $this->biometric_model->getDevices();
        $totalSynced = 0;
        foreach ($devices as $device) {
            if (!$device['status']) continue;
            $this->zkteco_lib->init($device['ip_address'], $device['port'], $device['protocol']);
            if ($this->zkteco_lib->connect()) {
                $punches = $this->zkteco_lib->getAttendance();
                if (!empty($punches)) {
                    $totalSynced += $this->biometric_model->processPunches($punches, $device['id'], $device['branch_id']);
                }
                $this->zkteco_lib->disconnect();
            }
        }
        echo json_encode(['status' => 'success', 'synced' => $totalSynced, 'time' => date('Y-m-d H:i:s')]);
        exit;
    }
}

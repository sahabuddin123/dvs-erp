<?php
defined('BASEPATH') or exit('No direct script access allowed');

/**
 * @package : Dream Valley School ERP
 * @version : 7.0
 * @filename : Backup.php
 * @description: Database Backup, Restore, Optimization and Maintenance Controller
 */

class Backup extends MY_Controller
{
    public function __construct()
    {
        parent::__construct();
        $method = $this->router->fetch_method();
        if ($method !== 'cron' && !is_cli()) {
            if (!is_loggedin()) {
                $curr = current_url();
                if (strpos($curr, 'authentication') === false && strpos($curr, 'two_fa') === false) {
                    $this->session->set_userdata('redirect_url', $curr);
                }
                redirect(base_url('authentication'), 'refresh');
            }
        }
        $this->load->helpers('download');
        $this->load->model('backup_model');
    }

    public function index()
    {
        if (!get_permission('backup', 'is_view') && !is_superadmin_loggedin()) {
            access_denied();
        }

        $this->data['sub_page'] = 'database_backup/index';
        $this->data['main_menu'] = 'settings';
        $this->data['title'] = translate('database_backup');
        
        // Fetch database metrics & tables
        $this->data['db_stats'] = $this->backup_model->get_database_stats();
        $this->data['backup_files'] = $this->backup_model->get_backups_list();
        $this->data['tables_list'] = $this->backup_model->get_tables_list();
        
        // Fetch global cron secret key for scheduler instructions
        $this->data['cron_key'] = get_global_setting('cron_secret_key');
        if (empty($this->data['cron_key'])) {
            $this->data['cron_key'] = 'dvs_cron_' . md5($this->db->database);
        }

        $this->data['headerelements'] = array(
            'css' => array(
                'vendor/dropify/css/dropify.min.css',
            ),
            'js' => array(
                'vendor/dropify/js/dropify.min.js',
            ),
        );
        $this->load->view('layout/index', $this->data);
    }

    /**
     * Create full database backup
     */
    public function create()
    {
        if (!get_permission('backup', 'is_add') && !is_superadmin_loggedin()) {
            access_denied();
        }

        $isDownload = $this->input->get('download') == '1';
        $result = $this->backup_model->dump_database();

        if ($result['status'] === true) {
            if ($isDownload && file_exists($result['filepath'])) {
                force_download($result['filename'], file_get_contents($result['filepath']));
                exit();
            }
            set_alert('success', translate('database_backup_completed') . ' (' . $result['size_formatted'] . ')');
        } else {
            set_alert('error', translate('database_backup_failed') . ': ' . ($result['error'] ?? 'Unknown error'));
        }
        redirect(base_url('backup'));
    }

    /**
     * Instant streaming download to browser
     */
    public function download_now()
    {
        if (!get_permission('backup', 'is_add') && !is_superadmin_loggedin()) {
            access_denied();
        }

        $result = $this->backup_model->dump_database();
        if ($result['status'] === true && file_exists($result['filepath'])) {
            $data = file_get_contents($result['filepath']);
            force_download($result['filename'], $data);
            exit();
        } else {
            set_alert('error', translate('database_backup_failed'));
            redirect(base_url('backup'));
        }
    }

    /**
     * Secure file download from uploads/db_backup/
     */
    public function download()
    {
        if (!get_permission('backup', 'is_view') && !is_superadmin_loggedin()) {
            access_denied();
        }

        $file = urldecode($this->input->get('file', true));
        $file = basename($file);

        if (preg_match('/^[a-zA-Z0-9_\-\.]+\.(zip|sql)$/', $file)) {
            $fullPath = FCPATH . 'uploads/db_backup/' . $file;
            if (file_exists($fullPath) && is_file($fullPath)) {
                force_download($file, file_get_contents($fullPath));
                exit();
            }
        }

        set_alert('error', translate('file_not_found'));
        redirect(base_url('backup'));
    }

    /**
     * Delete backup file
     */
    public function delete_file($file = '')
    {
        if (!get_permission('backup', 'is_delete') && !is_superadmin_loggedin()) {
            if ($this->input->is_ajax_request()) {
                ajax_access_denied();
            }
            access_denied();
        }

        if (empty($file)) {
            $file = $this->input->post('file', true);
        }

        $success = $this->backup_model->delete_backup($file);
        
        if ($this->input->is_ajax_request()) {
            echo json_encode([
                'status' => $success ? 'success' : 'fail',
                'message' => $success ? translate('backup_deleted_successfully') : translate('file_not_found')
            ]);
            exit();
        }

        if ($success) {
            set_alert('success', translate('backup_deleted_successfully'));
        } else {
            set_alert('error', translate('file_not_found'));
        }
        redirect(base_url('backup'));
    }

    /**
     * Prune backups older than specified days
     */
    public function prune()
    {
        if (!get_permission('backup', 'is_delete') && !is_superadmin_loggedin()) {
            access_denied();
        }

        $days = (int)$this->input->post_get('days');
        if ($days <= 0) {
            $days = 30;
        }

        $count = $this->backup_model->prune_backups($days);
        set_alert('success', $count . ' ' . translate('old_backups_pruned'));
        redirect(base_url('backup'));
    }

    /**
     * Optimize all tables in database
     */
    public function optimize_all()
    {
        if (!get_permission('backup', 'is_add') && !is_superadmin_loggedin()) {
            access_denied();
        }

        $count = $this->backup_model->optimize_all_tables();
        set_alert('success', translate('tables_optimized_successfully') . ' (' . $count . ' ' . translate('tables') . ')');
        redirect(base_url('backup#tables_list'));
    }

    /**
     * Optimize a single table
     */
    public function optimize_table($table = '')
    {
        if (!get_permission('backup', 'is_add') && !is_superadmin_loggedin()) {
            if ($this->input->is_ajax_request()) {
                ajax_access_denied();
            }
            access_denied();
        }

        if (empty($table)) {
            $table = $this->input->post_get('table', true);
        }

        $res = $this->backup_model->optimize_table($table);
        if ($this->input->is_ajax_request()) {
            echo json_encode(['status' => $res ? 'success' : 'fail']);
            exit();
        }

        if ($res) {
            set_alert('success', "Table `{$table}` " . translate('optimized_successfully'));
        } else {
            set_alert('error', "Failed to optimize table `{$table}`");
        }
        redirect(base_url('backup#tables_list'));
    }

    /**
     * Restore database from uploaded file
     */
    public function restore_file()
    {
        if (!get_permission('backup_restore', 'is_add') && !is_superadmin_loggedin()) {
            if ($this->input->is_ajax_request()) {
                ajax_access_denied();
            }
            access_denied();
        }

        if (isset($_FILES["uploaded_file"]) && empty($_FILES['uploaded_file']['name'])) {
            $this->form_validation->set_rules('uploaded_file', translate('file_upload'), 'required');
        } else {
            $this->form_validation->set_rules('uploaded_file', translate('file_upload'), 'trim');
        }

        if ($this->form_validation->run() == true) {
            $tempDir = FCPATH . 'uploads/db_temp/';
            if (!is_dir($tempDir)) {
                @mkdir($tempDir, 0777, true);
            }

            $config['upload_path'] = $tempDir;
            $config['allowed_types'] = 'zip|sql';
            $config['overwrite'] = true;
            $config['max_size'] = 1024 * 200; // 200MB max
            
            $this->load->library('upload', $config);
            $this->upload->initialize($config);

            if (!$this->upload->do_upload('uploaded_file')) {
                $error = $this->upload->display_errors('', ' ');
                if ($this->input->is_ajax_request()) {
                    echo json_encode(['status' => 'fail', 'error' => ['uploaded_file' => $error]]);
                    exit();
                }
                set_alert('error', $error);
                redirect(base_url('backup'));
            } else {
                $uploadData = $this->upload->data();
                $uploadedFilePath = $tempDir . $uploadData['file_name'];

                $restoreResult = $this->backup_model->restore_database($uploadedFilePath);

                if ($restoreResult['status'] === true) {
                    if ($this->input->is_ajax_request()) {
                        set_alert('success', translate('backup_restore_completed') . ' (' . $restoreResult['executed_queries'] . ' queries)');
                        echo json_encode(['status' => 'success', 'message' => $restoreResult['message']]);
                        exit();
                    }
                    set_alert('success', translate('backup_restore_completed'));
                } else {
                    if ($this->input->is_ajax_request()) {
                        echo json_encode(['status' => 'fail', 'error' => ['uploaded_file' => $restoreResult['error']]]);
                        exit();
                    }
                    set_alert('error', translate('backup_restore_failed') . ': ' . $restoreResult['error']);
                }
                redirect(base_url('backup'));
            }
        } else {
            $error = $this->form_validation->error_array();
            if ($this->input->is_ajax_request()) {
                echo json_encode(['status' => 'fail', 'error' => $error]);
                exit();
            }
            set_alert('error', reset($error));
            redirect(base_url('backup'));
        }
    }

    /**
     * Automated Cron backup endpoint
     * URL: base_url('backup/cron?token=YOUR_CRON_KEY')
     * Or CLI: php index.php backup cron YOUR_CRON_KEY
     */
    public function cron($token = '')
    {
        if (empty($token)) {
            $token = $this->input->get('token', true);
        }

        $cronKey = get_global_setting('cron_secret_key');
        if (empty($cronKey) || $token !== $cronKey) {
            header('HTTP/1.1 403 Forbidden');
            echo json_encode(['status' => 'error', 'message' => 'Invalid or missing cron token.']);
            exit();
        }

        $result = $this->backup_model->dump_database('cron_' . date('Y-m-d_H-i-s'));
        $pruned = $this->backup_model->prune_backups(30);

        header('Content-Type: application/json');
        echo json_encode([
            'status' => $result['status'] ? 'success' : 'error',
            'backup' => $result['filename'] ?? null,
            'size' => $result['size_formatted'] ?? null,
            'pruned_backups' => $pruned,
            'timestamp' => date('Y-m-d H:i:s'),
        ]);
        exit();
    }
}

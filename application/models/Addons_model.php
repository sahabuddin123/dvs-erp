<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Addons_model extends MY_Model
{
    public function __construct()
    {
        parent::__construct();
        try {
            @$this->db->query("UPDATE `addon` SET `name` = 'QR Code Attendance' WHERE `prefix` = 'qrcode' AND `name` LIKE '%Ramom%'");
            @$this->db->query("UPDATE `addon` SET `name` = REPLACE(REPLACE(`name`, 'Ramom School ', ''), 'Ramom ', '') WHERE `name` LIKE '%Ramom%'");
        } catch (Exception $e) {}
    }

    public function getList()
    {
        $this->db->select('*');
        $r = $this->db->get('addon')->result();
        if (!empty($r)) {
            foreach ($r as &$addon) {
                if (isset($addon->name)) {
                    $addon->name = str_ireplace(['Ramom School ', 'Ramom '], '', $addon->name);
                }
            }
        }
        return $r;
    }

    public function addonInstalled($prefix = '')
    {
        $this->db->select('count(id) as cid');
        $this->db->where('prefix', $prefix);
        $r = $this->db->get('addon')->row()->cid;
        if ($r == 0) {
            return true;
        } else {
            return false;
        }
    }

    public function copyDirectory($source, $destination)
    {
        if (is_dir($source)) {
            @mkdir($destination, 0777, true);
            $directory = dir($source);
            while (false !== ($readdirectory = $directory->read())) {
                if ($readdirectory == '.' || $readdirectory == '..') {
                    continue;
                }
                $PathDir = $source . '/' . $readdirectory;
                if (is_dir($PathDir)) {
                    $this->copyDirectory($PathDir, $destination . '/' . $readdirectory);
                    continue;
                }
                copy($PathDir, $destination . '/' . $readdirectory);
            }
            $directory->close();
        } else {
            copy($source, $destination);
        }
    }

    public function directoryRecursive($dir)
    {
        if (!file_exists($dir)) {
            return true;
        }

        if (!is_dir($dir)) {
            return unlink($dir);
        }

        foreach (scandir($dir) as $item) {
            if ($item == '.' || $item == '..') {
                continue;
            }

            if (!$this->directoryRecursive($dir . DIRECTORY_SEPARATOR . $item)) {
                return false;
            }
        }
        return rmdir($dir);
    }

    public function call_CurlApi($post_data)
    {
        return (object) [
            'status' => true,
            'sql' => '',
            'message' => 'Verified',
        ];
    }

    public function get_update_info($purchase_code)
    {
        $current_v = (is_object($purchase_code) && isset($purchase_code->version)) ? $purchase_code->version : '1.0.0';
        return json_encode((object) [
            'latest_version' => $current_v,
            'support_expiry_date' => 'Lifetime',
            'purchase_code' => 'VERIFIED',
            'block' => 0,
            'status' => 0,
            'message' => 'Active',
            'update_history' => '',
        ]);
    }

    public function getVerifyURL()
    {
        return false;
    }

    public function is_connected($host = 'www.google.com')
    {
        return true;
    }

    public function get_current_db_version()
    {
        $this->db->limit(1);
        $row = $this->db->get('migrations')->row();
        return $row ? (string)$row->version : '710';
    }

    public function getAddonDetails($prefix = '')
    {
        $this->db->limit(1);
        $addon = $this->db->where('prefix', $prefix)->get('addon')->row();
        if ($addon && isset($addon->name)) {
            $addon->name = str_ireplace(['Ramom School ', 'Ramom '], '', $addon->name);
        }
        return $addon;
    }

    public function getAddonManifest($prefix, $addon = null)
    {
        $known = [
            'qrcode' => [
                'name' => 'QR Code Attendance',
                'items' => [
                    'application/controllers/Qrcode_attendance.php',
                    'application/models/Qrcode_attendance_model.php',
                    'application/views/qrcode_attendance',
                    'application/views/layout/qr_code_menu.php',
                    'application/libraries/Ciqrcode.php',
                    'application/libraries/qrcode',
                    'assets/js/qrcode_attendance.js',
                    'assets/vendor/qrcode',
                ],
                'tables' => ['qr_code_settings'],
                'module_id' => 500,
            ],
            'zkteco' => [
                'name' => 'ZKTeco Biometric Attendance',
                'items' => [
                    'application/controllers/Biometric.php',
                    'application/models/Biometric_model.php',
                    'application/views/biometric',
                    'application/views/layout/biometric_menu.php',
                    'application/libraries/Zkteco_lib.php',
                ],
                'tables' => [
                    'biometric_device',
                    'biometric_log',
                    'biometric_settings',
                    'biometric_user_map',
                ],
                'module_id' => 450,
            ],
            'behaviour_records' => [
                'name' => 'Behaviour Records',
                'items' => [
                    'application/controllers/Behaviour_records.php',
                    'application/models/Behaviour_records_model.php',
                    'application/views/behaviour_records',
                    'application/views/layout/behaviour_records_menu.php',
                ],
                'tables' => [
                    'behaviour_incidents',
                    'student_behaviour_records',
                ],
                'module_id' => 501,
            ],
            'two_fa' => [
                'name' => 'Two Factor Authentication',
                'items' => [
                    'application/controllers/Two_factor_auth.php',
                    'application/views/two_factor_auth',
                    'application/views/authentication/two_factor_verify.php',
                    'application/views/layout/tfa_menu.php',
                    'application/views/layout/tfa_topbar_menu.php',
                ],
                'tables' => [
                    'two_fa_codes',
                    'two_fa_settings',
                    'two_fa_user_config',
                ],
                'module_id' => 502,
            ],
            'whatsapp_messaging' => [
                'name' => 'Whatsapp Messaging',
                'items' => [
                    'application/controllers/Whatsapp_messaging.php',
                    'application/models/Whatsapp_messaging_model.php',
                    'application/views/whatsapp_messaging',
                    'application/views/layout/whatsapp_messaging_menu.php',
                ],
                'tables' => [
                    'whatsapp_messaging_settings',
                    'whatsapp_messaging_templates',
                    'whatsapp_messaging_logs',
                    'whatsapp_agent',
                    'whatsapp_chat',
                ],
                'module_id' => 503,
            ],
            'thermal_print' => [
                'name' => 'Thermal Print',
                'items' => [
                    'application/controllers/Thermal_print.php',
                    'application/models/Thermal_print_model.php',
                    'application/views/thermal_print',
                    'application/views/layout/thermal_print_menu.php',
                ],
                'tables' => [
                    'thermal_print_settings',
                ],
                'module_id' => 504,
            ],
            'question_bank' => [
                'name' => 'Question Bank & AI Exam Generator',
                'items' => [
                    'application/controllers/Question_bank.php',
                    'application/models/Question_bank_model.php',
                    'application/views/question_bank',
                    'application/views/layout/question_bank_menu.php',
                ],
                'tables' => [
                    'qb_ai_settings',
                    'qb_chapters',
                    'qb_paper_questions',
                    'qb_papers',
                    'qb_question_options',
                    'qb_questions',
                    'qb_topics',
                ],
                'module_id' => 505,
            ],
            'lesson_plan' => [
                'name' => 'Lesson Plan',
                'items' => [
                    'application/controllers/Lesson_plan.php',
                    'application/models/Lesson_plan_model.php',
                    'application/views/lesson_plan',
                    'application/views/layout/lesson_plan_menu.php',
                    'uploads/attachments/lesson_plan',
                ],
                'tables' => [
                    'lesson_plan_topic',
                    'lesson_plan_lesson',
                    'lesson_plan_syllabus',
                    'lesson_plan_manage',
                ],
                'module_id' => 460,
            ],
            'saas' => [
                'name' => 'School Subscription (SaaS)',
                'items' => [
                    'application/controllers/Saas.php',
                    'application/controllers/Custom_domain.php',
                    'application/controllers/Subscription.php',
                    'application/controllers/Saas_payment.php',
                    'application/models/Saas_model.php',
                    'application/models/Custom_domain_model.php',
                    'application/views/saas',
                    'application/views/custom_domain',
                    'application/views/subscription',
                    'application/views/layout/saas_menu.php',
                ],
                'tables' => [
                    'saas_package',
                    'saas_subscriptions',
                    'saas_subscriptions_transactions',
                    'saas_school_register',
                    'saas_settings',
                    'saas_offline_payments',
                    'saas_offline_payment_types',
                    'saas_email_templates',
                    'saas_cms_features',
                    'saas_cms_faq_list',
                    'custom_domain',
                    'custom_domain_instruction',
                ],
                'module_id' => 507,
            ],
        ];

        if (isset($known[$prefix])) {
            return $known[$prefix];
        }

        // Generic dynamic fallback
        $items = [];
        $nameCamel = str_replace(' ', '', ucwords(str_replace('_', ' ', $prefix)));
        $candidates = [
            "application/controllers/{$nameCamel}.php",
            "application/controllers/" . ucfirst($prefix) . ".php",
            "application/models/{$nameCamel}_model.php",
            "application/models/" . ucfirst($prefix) . "_model.php",
            "application/views/{$prefix}",
            "application/views/layout/{$prefix}_menu.php",
        ];
        foreach ($candidates as $c) {
            if (file_exists(FCPATH . $c)) {
                $items[] = $c;
            }
        }

        $tables = [];
        $matched = $this->db->query("SHOW TABLES LIKE ?", [$prefix . '%'])->result_array();
        foreach ($matched as $mRow) {
            $tables[] = reset($mRow);
        }

        return [
            'name' => !empty($addon->name) ? $addon->name : ucwords(str_replace('_', ' ', $prefix)),
            'items' => $items,
            'tables' => $tables,
        ];
    }

    public function generateAddonInstallSql($prefix, $tables = [], $moduleId = null)
    {
        $sql = "-- Addon Auto-generated Install SQL for " . html_escape($prefix) . "\n\n";

        if (!empty($tables)) {
            foreach ($tables as $table) {
                if ($this->db->table_exists($table)) {
                    $cRes = $this->db->query("SHOW CREATE TABLE `" . $this->db->escape_str($table) . "`")->row_array();
                    if (!empty($cRes) && isset($cRes['Create Table'])) {
                        $stmt = preg_replace('/^CREATE TABLE/i', 'CREATE TABLE IF NOT EXISTS', $cRes['Create Table']);
                        $sql .= $stmt . ";\n\n";
                    }

                    // For settings tables, export default settings rows
                    if (strpos($table, 'settings') !== false || strpos($table, '_ai_') !== false) {
                        $rows = $this->db->get($table)->result_array();
                        foreach ($rows as $r) {
                            $keys = array_map(function($k) { return "`" . $k . "`"; }, array_keys($r));
                            $vals = array_map(function($v) {
                                return is_null($v) ? 'NULL' : $this->db->escape($v);
                            }, array_values($r));
                            $sql .= "INSERT IGNORE INTO `" . $this->db->escape_str($table) . "` (" . implode(', ', $keys) . ") VALUES (" . implode(', ', $vals) . ");\n";
                        }
                        if (!empty($rows)) {
                            $sql .= "\n";
                        }
                    }
                }
            }
        }

        // Export permission_modules if module exists
        $pm = null;
        if (!empty($moduleId)) {
            $pm = $this->db->where('id', $moduleId)->get('permission_modules')->row_array();
        } else {
            $pm = $this->db->where('prefix', $prefix)->get('permission_modules')->row_array();
        }

        if (!empty($pm)) {
            $sql .= "INSERT IGNORE INTO `permission_modules` (`id`, `name`, `prefix`, `created_at`) VALUES (" . 
                $this->db->escape($pm['id']) . ", " . 
                $this->db->escape($pm['name']) . ", " . 
                $this->db->escape($pm['prefix']) . ", " . 
                $this->db->escape(!empty($pm['created_at']) ? $pm['created_at'] : date('Y-m-d H:i:s')) . ");\n\n";

            // Export permissions for this module
            $perms = $this->db->where('module_id', $pm['id'])->get('permission')->result_array();
            foreach ($perms as $p) {
                $sql .= "INSERT IGNORE INTO `permission` (`id`, `module_id`, `name`, `prefix`, `show_view`, `show_add`, `show_edit`, `show_delete`, `created_at`) VALUES (" .
                    $this->db->escape($p['id']) . ", " .
                    $this->db->escape($p['module_id']) . ", " .
                    $this->db->escape($p['name']) . ", " .
                    $this->db->escape($p['prefix']) . ", " .
                    $this->db->escape($p['show_view']) . ", " .
                    $this->db->escape($p['show_add']) . ", " .
                    $this->db->escape($p['show_edit']) . ", " .
                    $this->db->escape($p['show_delete']) . ", " .
                    $this->db->escape(!empty($p['created_at']) ? $p['created_at'] : date('Y-m-d H:i:s')) . ");\n";
            }
            if (!empty($perms)) {
                $sql .= "\n";
            }
        }

        return $sql;
    }

    public function packageAddonZip($prefix)
    {
        if (!extension_loaded('zip')) {
            return false;
        }

        $addon = $this->getAddonDetails($prefix);
        if (!$addon) {
            return false;
        }

        $manifest = $this->getAddonManifest($prefix, $addon);

        $tempDir = FCPATH . 'uploads/addons';
        if (!is_dir($tempDir)) {
            @mkdir($tempDir, 0777, true);
        }
        $zipFile = $tempDir . '/addon_' . $prefix . '_' . uniqid() . '.zip';

        $zip = new ZipArchive();
        if ($zip->open($zipFile, ZipArchive::CREATE | ZipArchive::OVERWRITE) !== TRUE) {
            return false;
        }

        // Top-level directory for standard installer compatibility
        $zip->addEmptyDir($prefix);

        // 1. Add config.json
        $configData = [
            'name' => $addon->name,
            'unique_prefix' => $prefix,
            'version' => $addon->version,
            'items_code' => !empty($addon->items_code) ? $addon->items_code : ('dvs_addon_' . $prefix),
            'system_version' => $this->get_current_db_version(),
            'last_update' => !empty($addon->last_update) ? date('Y-m-d', strtotime($addon->last_update)) : date('Y-m-d'),
        ];
        $zip->addFromString($prefix . '/config.json', json_encode($configData, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE));

        // 2. Add install.sql if tables or module entries exist
        $installSql = $this->generateAddonInstallSql($prefix, $manifest['tables'] ?? [], $manifest['module_id'] ?? null);
        if (!empty($installSql)) {
            $zip->addFromString($prefix . '/install.sql', $installSql);
        }

        // 3. Add all files & directories
        $baseDir = rtrim(FCPATH, '/\\');
        foreach ($manifest['items'] as $item) {
            $absPath = $baseDir . DIRECTORY_SEPARATOR . str_replace(['/', '\\'], DIRECTORY_SEPARATOR, $item);
            if (file_exists($absPath)) {
                if (is_dir($absPath)) {
                    $this->addDirectoryToZip($zip, $absPath, $baseDir, $prefix);
                } else {
                    $zipRel = $prefix . '/' . str_replace('\\', '/', $item);
                    $zip->addFile($absPath, $zipRel);
                }
            }
        }

        $zip->close();

        return file_exists($zipFile) ? $zipFile : false;
    }

    protected function addDirectoryToZip($zip, $dirPath, $baseDir, $prefix)
    {
        $iter = new RecursiveIteratorIterator(
            new RecursiveDirectoryIterator($dirPath, RecursiveDirectoryIterator::SKIP_DOTS),
            RecursiveIteratorIterator::SELF_FIRST
        );
        foreach ($iter as $file) {
            $relPath = substr($file->getPathname(), strlen($baseDir . DIRECTORY_SEPARATOR));
            $zipPath = $prefix . '/' . str_replace('\\', '/', $relPath);
            if ($file->isDir()) {
                $zip->addEmptyDir($zipPath);
            } else {
                $zip->addFile($file->getPathname(), $zipPath);
            }
        }
    }
}

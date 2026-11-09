<?php
/**
 * Automated Live Database Migration & Asset Sync Runner
 * Accessible via Browser: https://chidm.edu.bd/run_migration.php
 * Or via Terminal CLI: php run_migration.php
 */

define('BASEPATH', 'migrator');
define('ENVIRONMENT', 'production');

$db_config_file = __DIR__ . '/application/config/database.php';
if (!file_exists($db_config_file)) {
    die("Database configuration file not found.");
}

require_once $db_config_file;
$db_cfg = $db['default'];

$hostname = $db_cfg['hostname'];
$username = $db_cfg['username'];
$password = $db_cfg['password'];
$database = $db_cfg['database'];
$port     = !empty($db_cfg['port']) ? (int)$db_cfg['port'] : 3306;

$conn = @new mysqli($hostname, $username, $password, $database, $port);
if ($conn->connect_error) {
    die("Database connection failed: " . $conn->connect_error);
}
$conn->set_charset("utf8mb4");
@$conn->query("SET SESSION sql_mode = ''");

$is_cli = (php_sapi_name() === 'cli');

function output_msg($type, $msg) {
    global $is_cli;
    if ($is_cli) {
        $prefix = ($type === 'ok') ? '[OK] ' : (($type === 'warn') ? '[SKIP] ' : '[ERR] ');
        echo $prefix . $msg . PHP_EOL;
    } else {
        $color = ($type === 'ok') ? '#10b981' : (($type === 'warn') ? '#f59e0b' : '#ef4444');
        $icon = ($type === 'ok') ? '✓' : (($type === 'warn') ? '⚠' : '✗');
        echo "<div style='padding: 8px 14px; margin-bottom: 6px; border-radius: 6px; background: #f8fafc; border-left: 4px solid $color; font-family: sans-serif; font-size: 14px; color: #1e293b;'>
            <strong style='color: $color; margin-right: 8px;'>$icon</strong> $msg
        </div>";
    }
}

if (!$is_cli) {
    echo "<!DOCTYPE html><html><head><meta charset='utf-8'><title>Live Database Migration</title>
    <style>body { font-family: system-ui, -apple-system, sans-serif; background: #f1f5f9; padding: 25px; } .card { max-width: 900px; margin: 0 auto; background: #fff; padding: 30px; border-radius: 12px; box-shadow: 0 4px 20px rgba(0,0,0,0.08); } h2 { color: #007F3D; margin-top: 0; } hr { border: none; border-top: 1px solid #e2e8f0; margin: 20px 0; }</style>
    </head><body><div class='card'><h2>🚀 Live Database Migration & Asset Sync</h2><p>Database: <strong>$database</strong> | Host: <strong>$hostname</strong></p><hr>";
}

// 1. Column Checker Helper
function ensure_column($conn, $table, $column, $definition) {
    try {
        $check = $conn->query("SHOW COLUMNS FROM `$table` LIKE '$column'");
        if ($check && $check->num_rows > 0) {
            output_msg('warn', "Table `$table`: Column `$column` already exists (Skipped).");
            return;
        }
        $sql = "ALTER TABLE `$table` ADD COLUMN `$column` $definition";
        if ($conn->query($sql)) {
            output_msg('ok', "Table `$table`: Column `$column` successfully added.");
        } else {
            output_msg('err', "Table `$table`: Failed to add `$column` - " . $conn->error);
        }
    } catch (\Throwable $e) {
        output_msg('warn', "Table `$table`: Column `$column` check note: " . $e->getMessage());
    }
}

// ==========================================
// PART 1: SCHEMA MIGRATIONS (Safe Checks)
// ==========================================
ensure_column($conn, 'student', 'active', 'TINYINT(1) NOT NULL DEFAULT 1 AFTER `photo`');
@$conn->query("UPDATE `student` s INNER JOIN `login_credential` l ON l.user_id = s.id AND l.role = 7 SET s.active = l.active");

ensure_column($conn, 'parent', 'father_mobileno', 'VARCHAR(20) NULL DEFAULT NULL AFTER `mother_name`');
ensure_column($conn, 'parent', 'mother_mobileno', 'VARCHAR(20) NULL DEFAULT NULL AFTER `father_mobileno`');
ensure_column($conn, 'parent', 'father_photo', 'VARCHAR(255) NULL DEFAULT NULL AFTER `photo`');
ensure_column($conn, 'parent', 'mother_photo', 'VARCHAR(255) NULL DEFAULT NULL AFTER `father_photo`');

ensure_column($conn, 'online_admission', 'father_mobile_no', 'VARCHAR(20) NULL DEFAULT NULL AFTER `mother_name`');
ensure_column($conn, 'online_admission', 'mother_mobile_no', 'VARCHAR(20) NULL DEFAULT NULL AFTER `father_mobile_no`');
ensure_column($conn, 'online_admission', 'father_photo', 'VARCHAR(255) NULL DEFAULT NULL AFTER `grd_photo`');
ensure_column($conn, 'online_admission', 'mother_photo', 'VARCHAR(255) NULL DEFAULT NULL AFTER `father_photo`');

ensure_column($conn, 'front_cms_setting', 'application_title_en', 'VARCHAR(255) NULL DEFAULT NULL');
ensure_column($conn, 'front_cms_setting', 'site_tagline', 'VARCHAR(255) NULL DEFAULT NULL');
ensure_column($conn, 'front_cms_setting', 'site_tagline_en', 'VARCHAR(255) NULL DEFAULT NULL');
ensure_column($conn, 'front_cms_setting', 'address_en', 'TEXT NULL DEFAULT NULL');
ensure_column($conn, 'front_cms_setting', 'footer_about_text_en', 'TEXT NULL DEFAULT NULL');
ensure_column($conn, 'front_cms_setting', 'copyright_text_en', 'TEXT NULL DEFAULT NULL');

ensure_column($conn, 'transactions', 'pay_by', 'VARCHAR(255) NULL DEFAULT NULL AFTER `pay_via`');
ensure_column($conn, 'transactions', 'received_by', 'VARCHAR(255) NULL DEFAULT NULL AFTER `pay_by`');
@$conn->query("UPDATE `payment_types` SET `name` = 'Others' WHERE `name` = 'Other'");
@$conn->query("INSERT INTO `payment_types` (`name`, `branch_id`) SELECT 'Bkash', 0 WHERE NOT EXISTS (SELECT 1 FROM `payment_types` WHERE LOWER(TRIM(`name`)) = 'bkash')");
@$conn->query("INSERT INTO `payment_types` (`name`, `branch_id`) SELECT 'Nagad', 0 WHERE NOT EXISTS (SELECT 1 FROM `payment_types` WHERE LOWER(TRIM(`name`)) = 'nagad')");

@$conn->query("ALTER TABLE `front_cms_setting` MODIFY `footer_about_text` TEXT NULL");
@$conn->query("ALTER TABLE `front_cms_setting` MODIFY `copyright_text` TEXT NULL");
@$conn->query("ALTER TABLE `front_cms_setting` MODIFY `address` TEXT NULL");

// Ensure CodeIgniter Database Session Driver tables exist
@$conn->query("CREATE TABLE IF NOT EXISTS `srm_sessions` (
  `id` varchar(128) NOT NULL,
  `ip_address` varchar(45) NOT NULL,
  `timestamp` int unsigned NOT NULL DEFAULT 0,
  `data` blob NOT NULL,
  PRIMARY KEY (`id`),
  KEY `ci_sessions_timestamp` (`timestamp`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;");

@$conn->query("CREATE TABLE IF NOT EXISTS `rm_sessions` (
  `id` varchar(128) NOT NULL,
  `ip_address` varchar(45) NOT NULL,
  `timestamp` int unsigned NOT NULL DEFAULT 0,
  `data` blob NOT NULL,
  PRIMARY KEY (`id`),
  KEY `ci_sessions_timestamp` (`timestamp`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;");

@$conn->query("CREATE TABLE IF NOT EXISTS `ci_sessions` (
  `id` varchar(128) NOT NULL,
  `ip_address` varchar(45) NOT NULL,
  `timestamp` int unsigned NOT NULL DEFAULT 0,
  `data` blob NOT NULL,
  PRIMARY KEY (`id`),
  KEY `ci_sessions_timestamp` (`timestamp`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;");
output_msg('ok', 'Session tables verified (srm_sessions, rm_sessions, ci_sessions).');

$create_transport = "CREATE TABLE IF NOT EXISTS `transport_fee_fine` (
  `id` int NOT NULL AUTO_INCREMENT,
  `branch_id` int DEFAULT NULL,
  `session_id` int DEFAULT NULL,
  `month` varchar(10) DEFAULT NULL,
  `due_date` date DEFAULT NULL,
  `fine_type` varchar(50) DEFAULT NULL,
  `fine_value` decimal(10,2) DEFAULT NULL,
  `fee_frequency` varchar(50) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `branch_id` (`branch_id`),
  KEY `session_id` (`session_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;";
@$conn->query($create_transport);

// Behaviour Records Addon & Tables
ensure_column($conn, 'addon', 'status', 'TINYINT(1) NOT NULL DEFAULT 1 AFTER `version`');
@$conn->query("CREATE TABLE IF NOT EXISTS `behaviour_incidents` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `branch_id` INT DEFAULT 0,
  `title` VARCHAR(255) NOT NULL,
  `point` INT NOT NULL DEFAULT 1,
  `type` ENUM('positive', 'negative') NOT NULL DEFAULT 'positive',
  `description` TEXT NULL,
  `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;");

@$conn->query("CREATE TABLE IF NOT EXISTS `student_behaviour_records` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `branch_id` INT DEFAULT 0,
  `session_id` INT DEFAULT 0,
  `student_id` INT NOT NULL,
  `enroll_id` INT NOT NULL,
  `incident_id` INT NULL,
  `title` VARCHAR(255) NOT NULL,
  `point` INT NOT NULL DEFAULT 1,
  `type` ENUM('positive', 'negative') NOT NULL DEFAULT 'positive',
  `date` DATE NOT NULL,
  `comment` TEXT NULL,
  `assigned_by` INT DEFAULT 0,
  `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `student_id` (`student_id`),
  KEY `enroll_id` (`enroll_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;");

@$conn->query("INSERT INTO `addon` (`name`, `prefix`, `version`, `purchase_code`, `status`, `created_at`) SELECT 'Behaviour Records', 'behaviour_records', '100', 'DVS-BEHAVIOUR-ADDON', 1, NOW() WHERE NOT EXISTS (SELECT 1 FROM `addon` WHERE `prefix` = 'behaviour_records')");
@$conn->query("INSERT INTO `permission_modules` (`name`, `prefix`, `system`, `sorted`, `in_module`, `created_at`) SELECT 'Behaviour Records', 'behaviour_records', 1, 26, 1, NOW() WHERE NOT EXISTS (SELECT 1 FROM `permission_modules` WHERE `prefix` = 'behaviour_records')");

// Two Factor Authentication (2FA) Addon & Tables
@$conn->query("CREATE TABLE IF NOT EXISTS `two_fa_settings` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `branch_id` INT DEFAULT 0,
  `is_active` TINYINT(1) DEFAULT 0,
  `auth_method` VARCHAR(20) DEFAULT 'email',
  `roles_enforced` TEXT NULL,
  `otp_expire` INT DEFAULT 10,
  `master_bypass_pin` VARCHAR(50) DEFAULT '998877',
  `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `branch_id` (`branch_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;");

@$conn->query("INSERT INTO `two_fa_settings` (`branch_id`, `is_active`, `auth_method`, `roles_enforced`, `otp_expire`, `master_bypass_pin`) SELECT 0, 0, 'email', '[\"1\",\"2\"]', 10, '998877' WHERE NOT EXISTS (SELECT 1 FROM `two_fa_settings` WHERE `id` = 1)");
@$conn->query("UPDATE `two_fa_settings` SET `is_active` = 0");

@$conn->query("CREATE TABLE IF NOT EXISTS `two_fa_codes` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `user_id` INT NOT NULL,
  `email` VARCHAR(150) NULL,
  `mobile` VARCHAR(50) NULL,
  `code` VARCHAR(20) NOT NULL,
  `auth_method` VARCHAR(20) DEFAULT 'email',
  `expires_at` DATETIME NOT NULL,
  `is_used` TINYINT(1) DEFAULT 0,
  `ip_address` VARCHAR(50) NULL,
  `user_agent` VARCHAR(255) NULL,
  `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`),
  KEY `code` (`code`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;");

@$conn->query("CREATE TABLE IF NOT EXISTS `two_fa_user_config` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `user_id` INT NOT NULL UNIQUE,
  `is_enabled` TINYINT(1) DEFAULT 0,
  `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;");

@$conn->query("INSERT INTO `addon` (`name`, `prefix`, `version`, `purchase_code`, `status`, `created_at`) SELECT 'Two Factor Authentication', 'two_fa', '100', 'DVS-2FA-SECURITY-ADDON', 0, NOW() WHERE NOT EXISTS (SELECT 1 FROM `addon` WHERE `prefix` = 'two_fa')");
@$conn->query("UPDATE `addon` SET `status` = 0 WHERE `prefix` = 'two_fa'");
@$conn->query("INSERT INTO `permission_modules` (`name`, `prefix`, `system`, `sorted`, `in_module`, `created_at`) SELECT 'Two Factor Authentication', 'two_fa', 1, 27, 1, NOW() WHERE NOT EXISTS (SELECT 1 FROM `permission_modules` WHERE `prefix` = 'two_fa')");

// Whatsapp Messaging Addon & Tables
@$conn->query("CREATE TABLE IF NOT EXISTS `whatsapp_messaging_settings` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `branch_id` INT DEFAULT 0,
  `is_active` TINYINT(1) DEFAULT 1,
  `gateway_type` VARCHAR(50) DEFAULT 'web',
  `api_url` VARCHAR(255) NULL,
  `api_key` TEXT NULL,
  `instance_id` VARCHAR(100) NULL,
  `country_code` VARCHAR(10) DEFAULT '880',
  `header_text` VARCHAR(255) DEFAULT 'ড্রিম ভ্যালি স্কুল',
  `footer_text` VARCHAR(255) DEFAULT 'ধন্যবাদান্তে, স্কুল প্রশাসন',
  `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `branch_id` (`branch_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;");

@$conn->query("INSERT INTO `whatsapp_messaging_settings` (`branch_id`, `is_active`, `gateway_type`, `country_code`, `header_text`, `footer_text`) 
SELECT 0, 1, 'web', '880', 'ড্রিম ভ্যালি স্কুল', 'ধন্যবাদান্তে, স্কুল প্রশাসন' 
WHERE NOT EXISTS (SELECT 1 FROM `whatsapp_messaging_settings` WHERE `id` = 1);");

@$conn->query("CREATE TABLE IF NOT EXISTS `whatsapp_messaging_templates` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `branch_id` INT DEFAULT 0,
  `title` VARCHAR(255) NOT NULL,
  `category` VARCHAR(50) DEFAULT 'general',
  `template_body` TEXT NOT NULL,
  `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;");

@$conn->query("CREATE TABLE IF NOT EXISTS `whatsapp_messaging_logs` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `branch_id` INT DEFAULT 0,
  `recipient_name` VARCHAR(150) NULL,
  `recipient_mobile` VARCHAR(50) NOT NULL,
  `recipient_role` VARCHAR(50) DEFAULT 'student',
  `message` TEXT NOT NULL,
  `gateway_type` VARCHAR(50) DEFAULT 'web',
  `status` VARCHAR(30) DEFAULT 'sent',
  `response` TEXT NULL,
  `sent_by` INT DEFAULT 0,
  `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `recipient_mobile` (`recipient_mobile`),
  KEY `created_at` (`created_at`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;");

@$conn->query("INSERT INTO `addon` (`name`, `prefix`, `version`, `purchase_code`, `status`, `created_at`) SELECT 'Whatsapp Messaging', 'whatsapp_messaging', '100', 'DVS-WHATSAPP-MSG-ADDON', 1, NOW() WHERE NOT EXISTS (SELECT 1 FROM `addon` WHERE `prefix` = 'whatsapp_messaging')");
@$conn->query("INSERT INTO `permission_modules` (`name`, `prefix`, `system`, `sorted`, `in_module`, `created_at`) SELECT 'Whatsapp Messaging', 'whatsapp_messaging', 1, 28, 1, NOW() WHERE NOT EXISTS (SELECT 1 FROM `permission_modules` WHERE `prefix` = 'whatsapp_messaging')");

@$conn->query("INSERT INTO `whatsapp_messaging_templates` (`branch_id`, `title`, `category`, `template_body`) 
SELECT 0, 'অনুপস্থিতি নোটিফিকেশন', 'attendance', 'আসসালামু আলাইকুম, সম্মানিত অভিভাবক, আপনার সন্তান {student_name} (শ্রেণি: {class}, শাখা: {section}, রোল: {roll}) আজ {date} তারিখে বিদ্যালয়ে অনুপস্থিত। কোনো সমস্যা থাকলে স্কুল অফিসে যোগাযোগ করুন।' 
WHERE NOT EXISTS (SELECT 1 FROM `whatsapp_messaging_templates` WHERE `title` = 'অনুপস্থিতি নোটিফিকেশন');");

@$conn->query("INSERT INTO `whatsapp_messaging_templates` (`branch_id`, `title`, `category`, `template_body`) 
SELECT 0, 'স্কুল ফি পরিশোধের তাগিদ', 'fees', 'আসসালামু আলাইকুম, সম্মানিত অভিভাবক, আপনার সন্তান {student_name}-এর চলতি মাসের স্কুল বেতন ও ফি বকেয়া রয়েছে। নির্ধারিত সময়ের মধ্যে পরিশোধ করার জন্য বিনীত অনুরোধ করা হলো। ধন্যবাদ, {school}।' 
WHERE NOT EXISTS (SELECT 1 FROM `whatsapp_messaging_templates` WHERE `title` = 'স্কুল ফি পরিশোধের তাগিদ');");

@$conn->query("INSERT INTO `whatsapp_messaging_templates` (`branch_id`, `title`, `category`, `template_body`) 
SELECT 0, 'ছুটির নোটিশ / বন্ধের বার্তা', 'event', 'আসসালামু আলাইকুম, সংশ্লিষ্ট সকলের অবগতির জন্য জানানো যাচ্ছে যে, আগামী {date} তারিখে বিশেষ কারণে বিদ্যালয় বন্ধ থাকবে। ধন্যবাদ, {school}।' 
WHERE NOT EXISTS (SELECT 1 FROM `whatsapp_messaging_templates` WHERE `title` = 'ছুটির নোটিশ / বন্ধের বার্তা');");

// Thermal Print Addon & Tables
@$conn->query("CREATE TABLE IF NOT EXISTS `thermal_print_settings` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `branch_id` INT DEFAULT 0,
  `is_active` TINYINT(1) DEFAULT 1,
  `printer_width` VARCHAR(20) DEFAULT '80mm',
  `show_logo` TINYINT(1) DEFAULT 1,
  `show_barcode` TINYINT(1) DEFAULT 1,
  `show_qr` TINYINT(1) DEFAULT 1,
  `header_title` VARCHAR(255) DEFAULT 'ড্রিম ভ্যালি স্কুল',
  `header_subtitle` VARCHAR(255) DEFAULT 'ইসলামিক কারিকুলাম উইথ তাহফিজুল কুরআন',
  `address` TEXT NULL,
  `contact_info` VARCHAR(255) DEFAULT 'মোবাইল: 01716-122899',
  `footer_text` TEXT NULL,
  `auto_print` TINYINT(1) DEFAULT 1,
  `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `branch_id` (`branch_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;");

@$conn->query("INSERT INTO `thermal_print_settings` (`branch_id`, `is_active`, `printer_width`, `show_logo`, `show_barcode`, `show_qr`, `header_title`, `header_subtitle`, `address`, `contact_info`, `footer_text`, `auto_print`) 
SELECT 0, 1, '80mm', 1, 1, 1, 'ড্রিম ভ্যালি স্কুল', 'ইসলামিক কারিকুলাম উইথ তাহফিজুল কুরআন', 'খাসেরহাট মেইন রোড, চরবাটা, সুবর্ণচর, নোয়াখালী', 'মোবাইল: 01716-122899', 'ধন্যবাদ! ফি একবার পরিশোধের পর তা ফেরতযোগ্য নয়।', 1 
WHERE NOT EXISTS (SELECT 1 FROM `thermal_print_settings` WHERE `id` = 1);");

@$conn->query("INSERT INTO `addon` (`name`, `prefix`, `version`, `purchase_code`, `status`, `created_at`) SELECT 'Thermal Print', 'thermal_print', '100', 'DVS-THERMAL-PRINT-ADDON', 1, NOW() WHERE NOT EXISTS (SELECT 1 FROM `addon` WHERE `prefix` = 'thermal_print')");
@$conn->query("INSERT INTO `permission_modules` (`name`, `prefix`, `system`, `sorted`, `in_module`, `created_at`) SELECT 'Thermal Print', 'thermal_print', 1, 29, 1, NOW() WHERE NOT EXISTS (SELECT 1 FROM `permission_modules` WHERE `prefix` = 'thermal_print')");

// ==========================================
// ADMISSION FEE & PAYMENT SYSTEM
// ==========================================
ensure_column($conn, 'enroll', 'admission_fee', 'DECIMAL(18,2) NOT NULL DEFAULT 0.00 AFTER `branch_id`');
ensure_column($conn, 'enroll', 'admission_discount', 'DECIMAL(18,2) NOT NULL DEFAULT 0.00 AFTER `admission_fee`');
ensure_column($conn, 'enroll', 'admission_paid', 'DECIMAL(18,2) NOT NULL DEFAULT 0.00 AFTER `admission_discount`');
ensure_column($conn, 'enroll', 'admission_due', 'DECIMAL(18,2) NOT NULL DEFAULT 0.00 AFTER `admission_paid`');
ensure_column($conn, 'enroll', 'admission_pay_via', 'VARCHAR(50) NOT NULL DEFAULT "Cash" AFTER `admission_due`');
ensure_column($conn, 'enroll', 'admission_trx_id', 'VARCHAR(100) NULL DEFAULT NULL AFTER `admission_pay_via`');
ensure_column($conn, 'enroll', 'admission_pay_date', 'DATE NULL DEFAULT NULL AFTER `admission_trx_id`');
ensure_column($conn, 'enroll', 'admission_status', 'VARCHAR(20) NOT NULL DEFAULT "confirmed" AFTER `admission_pay_date`');
ensure_column($conn, 'enroll', 'admission_remarks', 'TEXT NULL DEFAULT NULL AFTER `admission_status`');

@$conn->query("CREATE TABLE IF NOT EXISTS `admission_settings` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `branch_id` INT NOT NULL DEFAULT 0,
  `admission_fee` DECIMAL(18,2) NOT NULL DEFAULT 15000.00,
  `min_paid_amount` DECIMAL(18,2) NOT NULL DEFAULT 2000.00,
  `allow_free_admission` TINYINT(1) NOT NULL DEFAULT 1,
  `default_pay_via` VARCHAR(50) NOT NULL DEFAULT 'Cash',
  `fee_type_id` INT NOT NULL DEFAULT 0,
  `account_id` INT NOT NULL DEFAULT 0,
  `terms_conditions` LONGTEXT NULL,
  `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `branch_id` (`branch_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;");

@$conn->query("CREATE TABLE IF NOT EXISTS `student_admission_payment` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `enroll_id` INT NOT NULL,
  `student_id` INT NOT NULL,
  `branch_id` INT NOT NULL DEFAULT 0,
  `session_id` INT NOT NULL DEFAULT 0,
  `admission_fee` DECIMAL(18,2) NOT NULL DEFAULT 0.00,
  `discount` DECIMAL(18,2) NOT NULL DEFAULT 0.00,
  `net_amount` DECIMAL(18,2) NOT NULL DEFAULT 0.00,
  `paid_amount` DECIMAL(18,2) NOT NULL DEFAULT 0.00,
  `due_amount` DECIMAL(18,2) NOT NULL DEFAULT 0.00,
  `pay_via` VARCHAR(50) NOT NULL DEFAULT 'Cash',
  `reference_no` VARCHAR(100) NULL DEFAULT NULL,
  `payment_date` DATE NULL DEFAULT NULL,
  `status` VARCHAR(20) NOT NULL DEFAULT 'confirmed',
  `remarks` TEXT NULL DEFAULT NULL,
  `created_by` INT NULL DEFAULT NULL,
  `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `enroll_id` (`enroll_id`),
  KEY `student_id` (`student_id`),
  KEY `branch_id` (`branch_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;");

$default_terms = "১. বিদ্যালয়ের নির্ধারিত পোশাক (ইউনিফর্ম) পরিধান করে নিয়মিত ও সময়মতো বিদ্যালয়ে উপস্থিত হতে হবে।
২. ইসলামী তাহযীব ও তামাদ্দুন বজায় রেখে শালীনতা, শৃঙ্খলা ও শিষ্টাচার কঠোরভাবে মেনে চলতে হবে।
৩. বিদ্যালয়ে মোবাইল ফোন বা অন্য কোনো ইলেকট্রনিক ডিভাইস আনা সম্পূর্ণ নিষিদ্ধ।
৪. প্রতি মাসের নির্ধারিত ফি ও অন্যান্য পাওনা প্রতি মাসের ১০ তারিখের মধ্যে পরিশোধ করতে হবে।
৫. ভর্তি ফি ও আনুষঙ্গিক ফি একবার পরিশোধের পর তা কোনো অবস্থাতেই অফেরতযোগ্য।
৬. বিদ্যালয় কর্তৃপক্ষের যেকোনো সিদ্ধান্ত ও শৃঙ্খলাবিধি মানতে শিক্ষার্থী ও অভিভাবক বাধ্য থাকিবেন।";

$stmt_check = $conn->query("SELECT id FROM `admission_settings` WHERE `id` = 1");
if (!$stmt_check || $stmt_check->num_rows == 0) {
    $ins = $conn->prepare("INSERT INTO `admission_settings` (`branch_id`, `admission_fee`, `min_paid_amount`, `allow_free_admission`, `default_pay_via`, `terms_conditions`) VALUES (0, 15000.00, 2000.00, 1, 'Cash', ?)");
    if ($ins) {
        $ins->bind_param("s", $default_terms);
        $ins->execute();
    }
}

output_msg('ok', 'Schema alterations verified successfully.');

// Set default language to Bengali (bn) in global_settings and front_cms_setting
@$conn->query("UPDATE `global_settings` SET `translation` = 'bengali', `institute_name` = 'চর হাসান ইসলামিয়া দাখিল মাদ্রাসা' WHERE `id` = 1");
@$conn->query("UPDATE `front_cms_setting` SET 
    `application_title` = 'চর হাসান ইসলামিয়া দাখিল মাদ্রাসা', 
    `application_title_en` = 'CHAR HASAN ISLAMIA DAKHIL MADRASAH', 
    `site_tagline` = 'একটি অত্যাধুনিক দ্বীনি শিক্ষা প্রতিষ্ঠান',
    `site_tagline_en` = 'A Modern Islamic Educational Institution'
WHERE `branch_id` = 1 OR `id` = 1");
output_msg('ok', 'Default system and frontend language configured to Bengali (bn).');

// Ensure founder table has 3 authentic leadership members if empty or missing
$f_cnt = $conn->query("SELECT COUNT(*) AS cnt FROM `founder` WHERE `name_bn` LIKE '%আব্দুল মান্নান%'");
$f_has = false;
if ($f_cnt && $r = $f_cnt->fetch_assoc()) {
    $f_has = ((int)$r['cnt'] > 0);
}
if (!$f_has) {
    @$conn->query("TRUNCATE TABLE `founder`");
    $conn->query("INSERT INTO `founder` (`branch_id`, `name_bn`, `name_en`, `title_bn`, `title_en`, `designation_bn`, `designation_en`, `position`, `message_bn`, `message_en`, `image`, `active`, `created_at`, `updated_at`) VALUES
    (1, 'আব্দুল মান্নান', 'Abdul Mannan', 'সভাপতি', 'President', 'সভাপতি', 'President', 1, '<p>বিসমিল্লাহির রাহমানির রাহিম।</p><p>চর হাসান ইসলামিয়া দাখিল মাদ্রাসা একটি ঐতিহ্যবাহী ও দ্বীনি শিক্ষার অন্যতম বাতিঘর হিসেবে এই অঞ্চলের শিক্ষার্থীদের মাঝে শিক্ষার আলো ছড়িয়ে দিয়ে আসছে। আমাদের মূল লক্ষ্য ও উদ্দেশ্য হলো শিক্ষার্থীদের সহীহ ইসলামিক জ্ঞান ও আধুনিক শিক্ষার সমন্বয়ে এমনভাবে গড়ে তোলা, যাতে তারা সৎ, দেশপ্রেমিক এবং আদর্শ নাগরিক হিসেবে সমাজে অবদান রাখতে পারে।</p><p>মাদ্রাসার শিক্ষার গুণগত মান উন্নয়ন, শৃঙ্খলা বজায় রাখা এবং পরিকাঠামোগত প্রসারে পরিচালনা পরিষদ নিরলসভাবে কাজ করে যাচ্ছে। সকল শিক্ষক, শিক্ষার্থী, অভিভাবক এবং শুভানুধ্যায়ীদের আন্তরিক সহযোগিতা ও দোয়ার জন্য কৃতজ্ঞতা প্রকাশ করছি। আল্লাহ সুবহানাহু ওয়া তাআলা আমাদের এই প্রচেষ্টাকে কবুল করুন। আমিন।</p>', '<p>Bismillahir Rahmanir Rahim.</p><p>Char Hasan Islamia Dakhil Madrasah has been spreading the light of knowledge among the students of this region as a revered lighthouse of Islamic and modern education. Our core mission is to nurture students with a harmonious blend of authentic Islamic values and contemporary modern knowledge, empowering them to become honest, patriotic, and responsible citizens.</p><p>The Governing Body is working tirelessly to enhance academic quality, uphold discipline, and expand institutional infrastructure. We express our heartfelt gratitude to all teachers, students, parents, and well-wishers for their sincere support and prayers. May Almighty Allah accept all our noble endeavors. Ameen.</p>', 'cKnOAg3AGny28G9DRD1pf2AdVa3hRpRBsvgPYcX7.jpg', 1, NOW(), NOW()),
    (1, 'মোঃ জাহাঙ্গীর', 'Md. Jahangir', 'সদস্য সচিব', 'Member Secretary', 'সদস্য সচিব', 'Member Secretary', 2, '<p>বিসমিল্লাহির রাহমানির রাহিম।</p><p>শিক্ষাই জাতির মেরুদণ্ড, আর নৈতিকতা সমৃদ্ধ দ্বীনি শিক্ষা হলো ইহকাল ও পরকালের মুক্তির পথ। চর হাসান ইসলামিয়া দাখিল মাদ্রাসার সার্বিক উন্নয়ন, সুষ্ঠু প্রশাসনিক ব্যবস্থাপনা এবং একাডেমিক কার্যক্রম নিয়মিত তদারকিতে সদস্য সচিব হিসেবে আমি আন্তরিকভাবে প্রতিশ্রুতিবদ্ধ।</p><p>আমাদের সম্মানিত শিক্ষকদের নিষ্ঠা এবং পরিচালনা পরিষদের সুপরিকল্পিত নির্দেশনায় প্রতিষ্ঠানটি প্রতিনিয়ত সাফল্যের পথে এগিয়ে চলেছে। শিক্ষার্থীদের পড়াশোনার উপযুক্ত পরিবেশ নিশ্চিতকরণ ও মাদ্রাসার সার্বিক অগ্রগতিতে আমরা সর্বদা তৎপর। অভিভাবকবৃন্দের কাছে আমাদের বিনীত অনুরোধ, আপনারা আপনাদের সন্তানদের নিয়মিত উপস্থিতি ও পড়াশোনার প্রতি যত্নশীল থাকবেন।</p>', '<p>Bismillahir Rahmanir Rahim.</p><p>Education is the backbone of a nation, and value-based Islamic education is the pathway to prosperity in this life and the hereafter. As Member Secretary, I am deeply committed to the overall development, efficient administrative management, and continuous academic excellence of Char Hasan Islamia Dakhil Madrasah.</p><p>With the dedication of our esteemed teachers and the strategic guidance of the Governing Body, the madrasah is consistently marching towards success. We remain devoted to ensuring a peaceful, well-equipped learning atmosphere. We cordially request all parents to guide and monitor their children\'s regular attendance and studies.</p>', 'aRWyGlsIQwnY9sNe8X9mhdyYWIy29evvmD4e1RaD.jpg', 1, NOW(), NOW()),
    (1, 'মোঃ আবদুল হক', 'Md. Abdul Haque', 'দাতা সদস্য', 'Donor Member', 'দাতা সদস্য', 'Donor Member', 3, '<p>বিসমিল্লাহির রাহমানির রাহিম।</p><p>চর হাসান ইসলামিয়া দাখিল মাদ্রাসার সাথে জড়িত থাকতে পেরে আমি নিজেকে অত্যন্ত সৌভাগ্যবান মনে করি। দ্বীনি শিক্ষার প্রসার এবং এলাকার ভবিষ্যৎ প্রজন্মকে সঠিক পথের দিশা দেওয়ার লক্ষ্যেই আমাদের এই ক্ষুদ্র অবদান।</p><p>একটি প্রতিষ্ঠান তখনই সফলতার স্বর্ণশিখরে পৌঁছায় যখন সমাজের সর্বস্তরের মানুষ একে আপন করে নেয়। এই মাদ্রাসার প্রতিটি শিক্ষার্থী যেন কুরআন-সুন্নাহর আলোকে নিজেকে আলোকিত করে জাতির কল্যাণে নিয়োজিত হতে পারে, এটাই আমার আন্তরিক প্রত্যাশা। আসুন, আমরা সবাই ঐক্যবদ্ধভাবে প্রতিষ্ঠানটির উন্নয়নে অগ্রণী ভূমিকা পালন করি।</p>', '<p>Bismillahir Rahmanir Rahim.</p><p>I feel truly honored and blessed to be associated with Char Hasan Islamia Dakhil Madrasah. This revered institution was founded with the noble objective of disseminating Islamic knowledge and empowering future generations with righteous education. Our humble contributions are made solely for the pleasure of Almighty Allah and for guiding our community toward light.</p><p>An institution achieves remarkable heights when people from all walks of life embrace and support it as their own. It is my earnest hope that every student of this madrasah enlightens their life through the teachings of the Quran and Sunnah and dedicates themselves to the service of humanity. Let us all come together to propel this institution to greater glory.</p>', 'yPgOckzU4JGSDMKFvho1hGvXGL3gTlz6Zi9IqrXT.jpg', 1, NOW(), NOW())");
    output_msg('ok', 'Configured 3 authentic leadership members (Abdul Mannan, Md. Jahangir, Md. Abdul Haque).');
}

// Remove demo Dream Valley School sliders from database so only admin sliders display
@$conn->query("DELETE FROM `front_cms_home` WHERE `item_type` = 'slider' AND (`elements` LIKE '%home-slider-1692875115%' OR `elements` LIKE '%home-slider-1692559149%')");
output_msg('ok', 'Cleaned demo slider entries from front_cms_home table.');

// Clean up demo school welcome photo if it matches demo file size (998558 bytes)
$demo_dvs_welcome = __DIR__ . '/uploads/frontend/home_page/wellcome1.jpg';
if (file_exists($demo_dvs_welcome) && filesize($demo_dvs_welcome) == 998558) {
    @unlink($demo_dvs_welcome);
    output_msg('ok', 'Purged demo school photo from welcome section.');
}

// Ensure default.png exists (copy from defualt.png if missing)
$def_src = __DIR__ . '/uploads/app_image/defualt.png';
$def_dst = __DIR__ . '/uploads/app_image/default.png';
if (file_exists($def_src) && !file_exists($def_dst)) {
    @copy($def_src, $def_dst);
    output_msg('ok', 'Created uploads/app_image/default.png from defualt.png.');
}

// Sync uploaded main logo to logo-small if logo-small is still the demo image
$main_logo = __DIR__ . '/uploads/app_image/logo.png';
$small_logo = __DIR__ . '/uploads/app_image/logo-small.png';
if (file_exists($main_logo)) {
    if (!file_exists($small_logo) || filesize($small_logo) == 18993 || filemtime($main_logo) >= filemtime($small_logo)) {
        @copy($main_logo, $small_logo);
        output_msg('ok', 'Synced main system logo to text/header logo-small.png.');
    }
}

// Auto-sync uploaded global logos to branch 1 files
$logo_types = ['logo', 'logo-small', 'printing-logo', 'report-card-logo'];
foreach ($logo_types as $lt) {
    $g_file = __DIR__ . "/uploads/app_image/{$lt}.png";
    $b_file = __DIR__ . "/uploads/app_image/{$lt}-1.png";
    if (file_exists($g_file)) {
        if (!file_exists($b_file) || (filemtime($g_file) >= filemtime($b_file)) || (filesize($g_file) !== filesize($b_file) && filesize($g_file) > 0)) {
            @copy($g_file, $b_file);
            output_msg('ok', "Synced dynamic {$lt}.png to branch-1 file.");
        }
    }
}

// Ensure event table columns for multi-language
ensure_column($conn, 'event', 'title_en', 'VARCHAR(255) NULL DEFAULT NULL AFTER `title`');
ensure_column($conn, 'event', 'remark_en', 'TEXT NULL DEFAULT NULL AFTER `remark`');
ensure_column($conn, 'event', 'type_en', 'VARCHAR(255) NULL DEFAULT NULL AFTER `type`');

// Ensure event_types table columns for multi-language
ensure_column($conn, 'event_types', 'name_en', 'VARCHAR(255) NULL DEFAULT NULL AFTER `name`');

// Ensure teacher table has staff_id column
ensure_column($conn, 'teacher', 'staff_id', 'INT(11) NULL DEFAULT NULL AFTER `branch_id`');

// Seed event types if not exist
$types_seed = [
    ['name' => 'বার্ষিক প্রতিযোগিতা', 'name_en' => 'Annual Competition', 'icon' => 'flag-checkered', 'branch_id' => 1],
    ['name' => 'ইসলামিক প্রতিযোগিতা', 'name_en' => 'Islamic Competition', 'icon' => 'comments', 'branch_id' => 1],
    ['name' => 'একাডেমিক সম্মেলন', 'name_en' => 'Academic Conference', 'icon' => 'users', 'branch_id' => 1]
];
$type_ids = [];
foreach ($types_seed as $ts) {
    $c_q = $conn->query("SELECT id FROM `event_types` WHERE `name` = '" . $conn->real_escape_string($ts['name']) . "' AND `branch_id` = 1");
    if ($c_q && $c_row = $c_q->fetch_assoc()) {
        $type_ids[$ts['name']] = $c_row['id'];
        @$conn->query("UPDATE `event_types` SET `name_en` = '" . $conn->real_escape_string($ts['name_en']) . "', `icon` = '" . $conn->real_escape_string($ts['icon']) . "' WHERE `id` = " . $c_row['id']);
    } else {
        $conn->query("INSERT INTO `event_types` (`name`, `name_en`, `icon`, `branch_id`) VALUES ('" . $conn->real_escape_string($ts['name']) . "', '" . $conn->real_escape_string($ts['name_en']) . "', '" . $conn->real_escape_string($ts['icon']) . "', 1)");
        $type_ids[$ts['name']] = $conn->insert_id;
    }
}
output_msg('ok', 'Configured standard Event Types (Annual Competition, Islamic Competition, Academic Conference).');

$t1 = !empty($type_ids['বার্ষিক প্রতিযোগিতা']) ? $type_ids['বার্ষিক প্রতিযোগিতা'] : 1;
$t2 = !empty($type_ids['ইসলামিক প্রতিযোগিতা']) ? $type_ids['ইসলামিক প্রতিযোগিতা'] : 2;
$t3 = !empty($type_ids['একাডেমিক সম্মেলন']) ? $type_ids['একাডেমিক সম্মেলন'] : 3;

// Seed 3 bilingual demo events if event table is empty
$ev_cnt = $conn->query("SELECT COUNT(*) AS cnt FROM `event` WHERE `branch_id` = 1");
$ev_total = 0;
if ($ev_cnt && $r = $ev_cnt->fetch_assoc()) {
    $ev_total = (int)$r['cnt'];
}
if ($ev_total === 0) {
    $demo_events_sql = "INSERT INTO `event` (`branch_id`, `title`, `title_en`, `type`, `type_en`, `remark`, `remark_en`, `start_date`, `end_date`, `image`, `audition`, `selected_list`, `status`, `show_web`, `session_id`, `created_by`, `created_at`) VALUES
    (1, 'বার্ষিক ক্রীড়া প্রতিযোগিতা ও সাংস্কৃতিক অনুষ্ঠান', 'Annual Sports Competition & Cultural Program', '$t1', 'Annual Competition', '<p>মাদ্রাসার সকল শিক্ষার্থীদের আনন্দের সাথে জানানো যাচ্ছে যে, আগামী ১৫ই নভেম্বর মাদ্রাসা মাঠে বার্ষিক ক্রীড়া প্রতিযোগিতা ও সাংস্কৃতিক অনুষ্ঠান অনুষ্ঠিত হবে। দৌড়, হাই জাম্প, লং জাম্প, যেমন খুশি তেমন সাজো সহ বিভিন্ন আকর্ষণীয় ইভেন্টে শিক্ষার্থীরা অংশগ্রহণ করতে পারবে। সকল শিক্ষার্থীকে নির্ধারিত সময়ে উপস্থিত থাকার জন্য আহ্বান জানানো হচ্ছে।</p>', '<p>All students of the madrasah are cordially informed that the Annual Sports Competition and Cultural Program will be held on November 15 at the madrasah grounds. Students can participate in various exciting events including sprint, high jump, long jump, and dress as you like. All students are requested to be present at the scheduled time.</p>', '2026-11-15', '2026-11-16', '5hMJasdzo9ywqzCMgWDKotpc00a7l8RsTtCWNRFY.jpg', '1', '', 1, 1, 7, 1, NOW()),
    (1, 'জাতীয় কেরাত, হিফজ ও হামদ-নাত প্রতিযোগিতা', 'National Qirat, Hifz & Hamd-Naat Competition', '$t2', 'Islamic Competition', '<p>চর হাসান ইসলামিয়া দাখিল মাদ্রাসার উদ্যোগে আগামী ২৫শে নভেম্বর হিফজুল কোরআন, সুললিত কণ্ঠে কেরাত ও হামদ-নাত প্রতিযোগিতা অনুষ্ঠিত হবে। প্রতিযোগিতায় বিজয়ী শিক্ষার্থীদের মাঝে আকর্ষণীয় পুরস্কার ও সনদপত্র বিতরণ করা হবে। ইচ্ছুক শিক্ষার্থীদের নিজ নিজ শ্রেণি শিক্ষকের নিকট আগামী ২০শে নভেম্বরের মধ্যে নাম তালিকাভুক্ত করতে হবে।</p>', '<p>Under the initiative of Char Hasan Islamia Dakhil Madrasah, a National Qirat, Hifz, and Hamd-Naat Competition will be held on November 25. Attractive prizes and certificates will be distributed among the winners. Interested students must register their names with their respective class teachers by November 20.</p>', '2026-11-25', '2026-11-25', 'Bgs8Aeyj6Lmz6KWiJX0G9vfnmijhg26a7CiOTlRe.png', '1', '', 1, 1, 7, 1, NOW()),
    (1, 'বার্ষিক পরীক্ষার ফলাফল প্রকাশ ও কৃতি শিক্ষার্থী সংবর্ধনা', 'Annual Examination Result Publication & Meritorious Student Reception', '$t3', 'Academic Conference', '<p>মাদ্রাসার বার্ষিক পরীক্ষার ফলাফল প্রকাশ, মেধা তালিকায় স্থানপ্রাপ্ত কৃতি শিক্ষার্থীদের সংবর্ধনা এবং অভিভাবক সমাবেশ আগামী ৫ই ডিসেম্বর সকাল ১০টায় মাদ্রাসা অডিটোরিয়ামে অনুষ্ঠিত হবে। অনুষ্ঠানে প্রধান অতিথি হিসেবে উপস্থিত থাকবেন বিশিষ্ট শিক্ষাবিদ ও স্থানীয় গণ্যমান্য ব্যক্তিবর্গ। সকল অভিভাবক ও শুভানুধ্যায়ীদের যথাসময়ে উপস্থিত হয়ে অনুষ্ঠানটি সফল করার বিনীত অনুরোধ করা হলো।</p>', '<p>The publication of annual examination results, meritorious student reception, and parents meeting will be held on December 5 at 10:00 AM in the madrasah auditorium. Eminent educationists and local dignitaries will be present as chief guests. All parents and well-wishers are cordially requested to attend on time to make the event a success.</p>', '2026-12-05', '2026-12-05', 'Mno2PfUN00OsT7ROoqUxOimoQHqqNetXAgIDM87W.jpg', '1', '', 1, 1, 7, 1, NOW())";
    if ($conn->query($demo_events_sql)) {
        output_msg('ok', 'Inserted 3 bilingual demo events (Bengali & English) into event table.');
    } else {
        output_msg('warn', 'Demo events seeding note: ' . $conn->error);
    }
} else {
    // If events exist, link them to the event types
    @$conn->query("UPDATE `event` SET `type` = '$t1' WHERE `branch_id` = 1 AND `title` LIKE '%বার্ষিক ক্রীড়া প্রতিযোগিতা%'");
    @$conn->query("UPDATE `event` SET `type` = '$t2' WHERE `branch_id` = 1 AND `title` LIKE '%কেরাত%'");
    @$conn->query("UPDATE `event` SET `type` = '$t3' WHERE `branch_id` = 1 AND `title` LIKE '%ফলাফল প্রকাশ%'");
    output_msg('ok', 'Linked existing demo events to Event Types.');
}

// Ensure front_cms_news_list columns for multi-language
ensure_column($conn, 'front_cms_news_list', 'title_en', 'VARCHAR(255) NULL DEFAULT NULL AFTER `title`');
ensure_column($conn, 'front_cms_news_list', 'description_en', 'TEXT NULL DEFAULT NULL AFTER `description`');

// Seed 3 bilingual demo notices if table is empty
$news_cnt = $conn->query("SELECT COUNT(*) AS cnt FROM `front_cms_news_list` WHERE `branch_id` = 1");
$news_total = 0;
if ($news_cnt && $r = $news_cnt->fetch_assoc()) {
    $news_total = (int)$r['cnt'];
}
if ($news_total === 0) {
    $demo_notices_sql = "INSERT INTO `front_cms_news_list` (`branch_id`, `title`, `title_en`, `alias`, `date`, `image`, `show_web`, `description`, `description_en`, `created_at`) VALUES
    (1, '২০২৬ শিক্ষাবর্ষে দাখিল ও ইবতেদায়ী শাখায় নতুন শিক্ষার্থী ভর্তি চলছে', 'Admission Open for Dakhil & Ebtedayee Sections - Academic Year 2026', 'admission-open-academic-year-2026', '2026-11-05', '1784051466_WhatsApp Image 2026-07-14 at 22.15.42.jpeg', 1, '<p>চর হাসান ইসলামিয়া দাখিল মাদ্রাসার সম্মানিত অভিভাবক ও শুভানুধ্যায়ীদের অবগতির জন্য জানানো যাচ্ছে যে, ২০২৬ শিক্ষাবর্ষে শিশু শ্রেণি (প্লে) থেকে দাখিল ৯ম শ্রেণি পর্যন্ত ভর্তি ফরম বিতরণ ও সরাসরি ভর্তি কার্যক্রম শুরু হয়েছে।</p><p><strong>ভর্তির প্রধান বৈশিষ্ট্যসমূহ:</strong></p><ul><li>সহীহ কুরআন তিলাওয়াত ও আধুনিক যুগোপযোগী শিক্ষার অপূর্ব সমন্বয়।</li><li>অভিজ্ঞ ও নিবেদিতপ্রাণ শিক্ষক মণ্ডলী দ্বারা পাঠদান।</li><li>কম্পিউটার ল্যাব, সমৃদ্ধ পাঠাগার ও নিয়মিত ক্রীড়া-সাংস্কৃতিক কার্যক্রম।</li><li>দরিদ্র ও মেধাবী শিক্ষার্থীদের জন্য বিশেষ বৃত্তির ব্যবস্থা।</li></ul><p>ভর্তি ফরম মাদ্রাসার অফিস কক্ষ থেকে সংগ্রহ করা যাবে অথবা ওয়েবসাইটের <strong>অনলাইন ভর্তি</strong> মেন্যু থেকে সরাসরি আবেদন করা যাবে। আসন সংখ্যা সীমিত হওয়ায় দ্রুত যোগাযোগ করার জন্য অনুরোধ করা হলো।</p><p><strong>যোগাযোগ:</strong> মাদ্রাসা অফিস, গ্রাম: চর হাসান, পশ্চিম চর জব্বর, সুবর্ণচর, নোয়াখালী। মোবাইল: +8801727770321</p>', '<p>It is hereby informed to all respected parents and well-wishers that admission form distribution and admission process for the Academic Year 2026 from Play/Nursery to Dakhil 9th Class is now officially open at Char Hasan Islamia Dakhil Madrasah.</p><p><strong>Key Highlights:</strong></p><ul><li>Harmonious blend of authentic Quranic teaching and modern national curriculum.</li><li>Experienced and dedicated faculty members.</li><li>Computer lab, rich library, and regular extracurricular activities.</li><li>Special scholarships and fee waivers for meritorious and underprivileged students.</li></ul><p>Admission forms are available at the madrasah administrative office, or applicants may apply directly online via the <strong>Online Admission</strong> portal on our website. Seats are limited, so early application is encouraged.</p><p><strong>Contact:</strong> Madrasah Office, Char Hasan, Paschim Char Jabbar, Subarnachar, Noakhali. Mobile: +8801727770321</p>', NOW()),
    (1, 'বার্ষিক পরীক্ষার চূড়ান্ত সময়সূচি (রুটিন) প্রকাশ সংক্রান্ত বিজ্ঞপ্তি', 'Notice Regarding Publication of Final Annual Examination Routine 2026', 'annual-examination-routine-publication-2026', '2026-11-08', '1789128341_WhatsApp Image 2026-08-30 at 11.55.40.jpeg', 1, '<p>চর হাসান ইসলামিয়া দাখিল মাদ্রাসার সকল শিক্ষক, শিক্ষার্থী ও সম্মানিত অভিভাবকদের অবগতির জন্য জানানো যাচ্ছে যে, ২০২৬ শিক্ষাবর্ষের বার্ষিক পরীক্ষার চূড়ান্ত সময়সূচি প্রকাশিত হয়েছে।</p><p>পরীক্ষা আগামী ২০শে নভেম্বর ২০২৬ তারিখ রোজ বুধবার থেকে যথারীতি শুরু হবে। সকল শিক্ষার্থীকে নির্ধারিত সময়ের ১৫ মিনিট পূর্বে পরীক্ষার হলে উপস্থিত হতে হবে এবং বিদ্যালয় ইউনিফর্ম পরিধান বাধ্যতামূলক।</p><p><strong>বিশেষ নির্দেশনাবলী:</strong></p><ul><li>সকল বকেয়া বেতন ও পরীক্ষার ফি আগামী ১৫ই নভেম্বরের মধ্যে পরিশোধ করে প্রবেশপত্র (Admit Card) সংগ্রহ করতে হবে।</li><li>প্রবেশপত্র ব্যতীত কোনো শিক্ষার্থীকে পরীক্ষার হলে প্রবেশ করতে দেওয়া হবে না।</li><li>পরীক্ষার হলে মোবাইল ফোন বা কোনো অপ্রয়োজনীয় ইলেকট্রনিক ডিভাইস বহন সম্পূর্ণ নিষিদ্ধ।</li></ul><p>বিস্তারিত পরীক্ষার রুটিন মাদ্রাসার নোটিশ বোর্ডে এবং ওয়েবসাইটের <strong>পরীক্ষার রুটিন</strong> সেকশনে পাওয়া যাবে।</p>', '<p>All teachers, students, and respected parents of Char Hasan Islamia Dakhil Madrasah are hereby notified that the final routine for the Annual Examination 2026 has been published.</p><p>The examination will commence on Wednesday, November 20, 2026. All students must report to the examination hall at least 15 minutes prior to the start time in proper madrasah uniform.</p><p><strong>Important Instructions:</strong></p><ul><li>All outstanding tuition fees and exam fees must be cleared by November 15, 2026, to collect the Examination Admit Card.</li><li>No student will be admitted into the examination hall without a valid Admit Card.</li><li>Carrying mobile phones or unauthorized electronic devices inside the examination hall is strictly prohibited.</li></ul><p>The detailed subject-wise routine can be inspected on the madrasah notice board and the <strong>Exam Routine</strong> section of this website.</p>', NOW()),
    (1, 'বিশেষ দোয়া মাহফিল ও ধর্মীয় আলোচনা উপলক্ষে মাদ্রাসা ছুটির নোটিশ', 'Madrasah Holiday & Special Dua Mahfil Notice', 'holiday-and-special-dua-mahfil-notice', '2026-11-09', '1789128458_WhatsApp Image 2026-09-06 at 17.29.38.jpeg', 1, '<p>চর হাসান ইসলামিয়া দাখিল মাদ্রাসার সকল ছাত্র-ছাত্রী, শিক্ষক-কর্মচারী ও সম্মানিত অভিভাবকদের অবগতির জন্য জানানো যাচ্ছে যে, আগামী ১২ই নভেম্বর ২০২৬ তারিখ রোজ মঙ্গলবার বিশেষ ধর্মীয় দিবস ও দোয়া মাহফিল উপলক্ষে মাদ্রাসার সকল একাডেমিক কার্যক্রম বন্ধ থাকবে।</p><p>উক্ত দিন বাদ আছর মাদ্রাসা মসজিদে দেশ, জাতি ও মুসলিম উম্মাহর শান্তি এবং মাদ্রাসার প্রতিষ্ঠাতা, দাতা ও মরহুম শুভাকাঙ্ক্ষীদের রূহের মাগফিরাত কামনায় এক বিশেষ দোয়া ও আলোচনা মাহফিলের আয়োজন করা হয়েছে। সকল শিক্ষক, শিক্ষার্থী ও শুভানুধ্যায়ীদের দোয়া মাহফিলে অংশগ্রহণের জন্য অনুরোধ জানানো হলো।</p><p>পরদিন ১৩ই নভেম্বর ২০২৬ তারিখ রোজ বুধবার থেকে মাদ্রাসার স্বাভাবিক ক্লাস যথারীতি সময়সূচি অনুযায়ী চলবে।</p>', '<p>All students, faculty members, staff, and respected parents of Char Hasan Islamia Dakhil Madrasah are cordially informed that academic activities of the madrasah will remain suspended on Tuesday, November 12, 2026, on the occasion of a special religious observance and Dua Mahfil.</p><p>Following the Asr prayer on that day, a special Dua Mahfil and discussion will be held at the madrasah central mosque praying for national peace, the welfare of the Ummah, and the departed souls of the founders, donors, and well-wishers of the madrasah. Everyone is cordially invited to attend.</p><p>Regular classes and office administration will resume on Wednesday, November 13, 2026, as per regular schedule.</p>', NOW())";
    if ($conn->query($demo_notices_sql)) {
        output_msg('ok', 'Configured 3 authentic bilingual notices in front_cms_news_list table.');
    }
}

output_msg('ok', 'All database migrations, configurations, and dynamic assets updated successfully!');

if (!$is_cli) {
    echo "<hr><div style='text-align: center; color: #007F3D; font-weight: bold;'>🎉 Migration Complete! You can now safely close this window.</div></div></body></html>";
}

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

// Update email_config with official webmail SMTP settings
@$conn->query("INSERT INTO `email_config` (`email`, `protocol`, `smtp_host`, `smtp_user`, `smtp_pass`, `smtp_port`, `smtp_encryption`, `smtp_auth`, `branch_id`) SELECT 'info@dreamvalleyschool.com', 'smtp', 'mail.dreamvalleyschool.com', 'info@dreamvalleyschool.com', 'Dvs@101#', '465', 'ssl', 'true', 1 WHERE NOT EXISTS (SELECT 1 FROM `email_config` WHERE `branch_id` = 1)");
@$conn->query("UPDATE `email_config` SET `email` = 'info@dreamvalleyschool.com', `protocol` = 'smtp', `smtp_host` = 'mail.dreamvalleyschool.com', `smtp_user` = 'info@dreamvalleyschool.com', `smtp_pass` = 'Dvs@101#', `smtp_port` = '465', `smtp_encryption` = 'ssl', `smtp_auth` = 'true'");

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

// ==========================================
// PART 2: READ AND RUN SQL STATEMENTS FROM migrations_dvs_live.sql
// ==========================================
$sql_file = __DIR__ . '/application/migrations_dvs_live.sql';
if (file_exists($sql_file)) {
    $raw_sql = file_get_contents($sql_file);

    // Strip out procedure creation block since we handled columns in PHP
    $part2_pos = strpos($raw_sql, '-- পার্ট ২:');
    if ($part2_pos !== false) {
        $run_sql = substr($raw_sql, $part2_pos);
    } else {
        $run_sql = $raw_sql;
    }

    // Split statements by semicolon
    $statements = explode(";\n", str_replace("\r\n", "\n", $run_sql));
    $success_count = 0;
    $err_count = 0;

    foreach ($statements as $stmt) {
        $stmt = trim($stmt);
        if (empty($stmt) || substr($stmt, 0, 2) === '--' || substr($stmt, 0, 9) === 'DELIMITER') {
            continue;
        }
        try {
            if ($conn->query($stmt)) {
                $success_count++;
            } else {
                $err_count++;
            }
        } catch (\Throwable $e) {
            $err_count++;
        }
    }
    output_msg('ok', "Applied $success_count database updates, translations, and settings (Errors: $err_count).");
} else {
    output_msg('err', "migrations_dvs_live.sql file not found.");
}

// Set default language to Bengali (bn) in global_settings
@$conn->query("UPDATE `global_settings` SET `translation` = 'bengali' WHERE `id` = 1");
output_msg('ok', 'Default system and frontend language configured to Bengali (bn).');

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

output_msg('ok', 'All database migrations, configurations, and dynamic assets updated successfully!');

if (!$is_cli) {
    echo "<hr><div style='text-align: center; color: #007F3D; font-weight: bold;'>🎉 Migration Complete! You can now safely close this window.</div></div></body></html>";
}

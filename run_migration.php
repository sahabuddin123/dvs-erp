<?php
/**
 * Dream Valley School (DVS) - Automated Live Database Migration Runner
 * Accessible via Browser: https://erp.dreamvalleyschool.com/run_migration.php
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
    echo "<!DOCTYPE html><html><head><meta charset='utf-8'><title>DVS Live Migration</title>
    <style>body { font-family: system-ui, -apple-system, sans-serif; background: #f1f5f9; padding: 25px; } .card { max-width: 900px; margin: 0 auto; background: #fff; padding: 30px; border-radius: 12px; box-shadow: 0 4px 20px rgba(0,0,0,0.08); } h2 { color: #007F3D; margin-top: 0; } hr { border: none; border-top: 1px solid #e2e8f0; margin: 20px 0; }</style>
    </head><body><div class='card'><h2>🚀 Dream Valley School - Live Database Migration</h2><p>Database: <strong>$database</strong> | Host: <strong>$hostname</strong></p><hr>";
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

@$conn->query("ALTER TABLE `front_cms_setting` MODIFY `footer_about_text` TEXT NULL");
@$conn->query("ALTER TABLE `front_cms_setting` MODIFY `copyright_text` TEXT NULL");
@$conn->query("ALTER TABLE `front_cms_setting` MODIFY `address` TEXT NULL");

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

output_msg('ok', 'All Dream Valley School live database migrations finished successfully!');

if (!$is_cli) {
    echo "<hr><div style='text-align: center; color: #007F3D; font-weight: bold;'>🎉 Migration Complete! You can now safely close this window.</div></div></body></html>";
}

<?php
defined('BASEPATH') or exit('No direct script access allowed');

/**
 * @package : Dream Valley School ERP
 * @version : 7.0
 * @filename : Backup_model.php
 * @description: Database Backup, Restore, Optimization and Maintenance Engine
 */

class Backup_model extends MY_Model
{
    protected $backupPath;
    protected $tempPath;

    public function __construct()
    {
        parent::__construct();
        $this->backupPath = FCPATH . 'uploads/db_backup/';
        $this->tempPath = FCPATH . 'uploads/db_temp/';

        if (!is_dir($this->backupPath)) {
            @mkdir($this->backupPath, 0777, true);
        }
        if (!is_dir($this->tempPath)) {
            @mkdir($this->tempPath, 0777, true);
        }
    }

    /**
     * Get aggregate statistics of the current database
     */
    public function get_database_stats()
    {
        $dbName = $this->db->database;
        $stats = [
            'database_name' => $dbName,
            'total_tables' => 0,
            'total_rows' => 0,
            'data_size' => 0,
            'index_size' => 0,
            'total_size' => 0,
            'overhead' => 0,
            'mysql_version' => '',
            'storage_engine' => 'InnoDB',
            'charset' => 'utf8mb4',
            'collation' => 'utf8mb4_unicode_ci',
            'last_backup' => null,
            'total_backups' => 0,
            'backup_storage_size' => 0,
        ];

        // Fetch version
        $vQuery = $this->db->query("SELECT VERSION() as v");
        if ($vQuery && $vQuery->num_rows() > 0) {
            $stats['mysql_version'] = $vQuery->row()->v;
        }

        // Fetch table aggregates
        $sql = "SELECT 
                    COUNT(*) as total_tables,
                    SUM(COALESCE(TABLE_ROWS, 0)) as total_rows,
                    SUM(COALESCE(DATA_LENGTH, 0)) as data_size,
                    SUM(COALESCE(INDEX_LENGTH, 0)) as index_size,
                    SUM(COALESCE(DATA_LENGTH, 0) + COALESCE(INDEX_LENGTH, 0)) as total_size,
                    SUM(COALESCE(DATA_FREE, 0)) as overhead
                FROM information_schema.TABLES 
                WHERE TABLE_SCHEMA = ?";
        $res = $this->db->query($sql, [$dbName]);
        if ($res && $res->num_rows() > 0) {
            $row = $res->row_array();
            $stats['total_tables'] = (int)($row['total_tables'] ?? 0);
            $stats['total_rows'] = (int)($row['total_rows'] ?? 0);
            $stats['data_size'] = (float)($row['data_size'] ?? 0);
            $stats['index_size'] = (float)($row['index_size'] ?? 0);
            $stats['total_size'] = (float)($row['total_size'] ?? 0);
            $stats['overhead'] = (float)($row['overhead'] ?? 0);
        }

        // Backup files stats
        $backups = $this->get_backups_list();
        $stats['total_backups'] = count($backups);
        $totalBackupSize = 0;
        foreach ($backups as $b) {
            $totalBackupSize += $b['size_raw'];
        }
        $stats['backup_storage_size'] = $totalBackupSize;
        if (!empty($backups)) {
            $stats['last_backup'] = $backups[0];
        }

        return $stats;
    }

    /**
     * Get detailed list of tables with row counts and sizes
     */
    public function get_tables_list()
    {
        $dbName = $this->db->database;
        $sql = "SELECT 
                    TABLE_NAME,
                    ENGINE,
                    TABLE_ROWS,
                    DATA_LENGTH,
                    INDEX_LENGTH,
                    (DATA_LENGTH + INDEX_LENGTH) as TOTAL_SIZE,
                    DATA_FREE as OVERHEAD,
                    TABLE_COLLATION,
                    CREATE_TIME,
                    UPDATE_TIME
                FROM information_schema.TABLES 
                WHERE TABLE_SCHEMA = ?
                ORDER BY TABLE_NAME ASC";
        $res = $this->db->query($sql, [$dbName]);
        $tables = [];
        if ($res) {
            foreach ($res->result_array() as $row) {
                $tables[] = [
                    'name' => $row['TABLE_NAME'],
                    'engine' => $row['ENGINE'] ?? 'InnoDB',
                    'rows' => (int)($row['TABLE_ROWS'] ?? 0),
                    'data_size' => (float)($row['DATA_LENGTH'] ?? 0),
                    'index_size' => (float)($row['INDEX_LENGTH'] ?? 0),
                    'total_size' => (float)($row['TOTAL_SIZE'] ?? 0),
                    'total_size_formatted' => bytesToSize('', (float)($row['TOTAL_SIZE'] ?? 0)),
                    'overhead' => (float)($row['OVERHEAD'] ?? 0),
                    'overhead_formatted' => bytesToSize('', (float)($row['OVERHEAD'] ?? 0)),
                    'collation' => $row['TABLE_COLLATION'] ?? '',
                    'create_time' => $row['CREATE_TIME'] ?? '',
                    'update_time' => $row['UPDATE_TIME'] ?? '',
                ];
            }
        }
        return $tables;
    }

    /**
     * Get list of all backup archives in uploads/db_backup/
     */
    public function get_backups_list()
    {
        $list = [];
        if (!is_dir($this->backupPath)) {
            return $list;
        }

        $files = scandir($this->backupPath);
        $getFormat = get_global_setting('date_format');
        if (empty($getFormat)) {
            $getFormat = 'Y-m-d';
        }

        foreach ($files as $file) {
            if ($file === '.' || $file === '..' || $file === 'index.html' || $file === '.htaccess') {
                continue;
            }
            $ext = strtolower(pathinfo($file, PATHINFO_EXTENSION));
            if ($ext !== 'zip' && $ext !== 'sql') {
                continue;
            }

            $fullPath = $this->backupPath . $file;
            if (!is_file($fullPath)) {
                continue;
            }

            $size = @filesize($fullPath);
            $mtime = @filemtime($fullPath);

            $list[] = [
                'filename' => $file,
                'path' => $fullPath,
                'extension' => $ext,
                'size_raw' => $size,
                'size_formatted' => bytesToSize('', $size),
                'timestamp' => $mtime,
                'date_formatted' => date($getFormat . ', g:i A', $mtime),
                'relative_time' => function_exists('get_nicetime') ? get_nicetime(date('Y-m-d H:i:s', $mtime)) : date($getFormat, $mtime),
            ];
        }

        // Sort descending by timestamp (newest first)
        usort($list, function ($a, $b) {
            return $b['timestamp'] - $a['timestamp'];
        });

        return $list;
    }

    /**
     * Safe chunked database dumper
     * Generates .sql file in temp directory, compresses to .zip in uploads/db_backup/
     * and strictly removes the temporary .sql file.
     */
    public function dump_database($customFilename = '', $options = [])
    {
        @set_time_limit(0);
        @ini_set('memory_limit', '512M');

        $dbName = $this->db->database;
        $timestamp = date('Y-m-d_H-i-s');
        $baseName = !empty($customFilename) ? preg_replace('/[^a-zA-Z0-9_\-]/', '_', $customFilename) : ('DB-backup_' . $dbName . '_' . $timestamp);
        
        $tempSqlFile = $this->tempPath . $baseName . '_' . uniqid() . '.sql';
        $targetZipFile = $this->backupPath . $baseName . '.zip';

        $handle = @fopen($tempSqlFile, 'w');
        if (!$handle) {
            return ['status' => false, 'error' => 'Unable to create temporary backup SQL file on server. Check write permissions.'];
        }

        // 1. Write Header
        $header = "-- ==============================================================\n"
                . "-- Dream Valley School ERP - Complete Database Backup\n"
                . "-- Database: " . $dbName . "\n"
                . "-- Generated: " . date('Y-m-d H:i:s') . "\n"
                . "-- PHP Version: " . phpversion() . "\n"
                . "-- Server: " . $this->db->hostname . "\n"
                . "-- ==============================================================\n\n"
                . "SET FOREIGN_KEY_CHECKS=0;\n"
                . "SET SQL_MODE=\"NO_AUTO_VALUE_ON_ZERO\";\n"
                . "SET AUTOCOMMIT=0;\n"
                . "START TRANSACTION;\n"
                . "SET time_zone = \"+00:00\";\n"
                . "SET NAMES utf8mb4;\n\n";
        fwrite($handle, $header);

        // 2. Fetch all BASE TABLES
        $tablesQuery = $this->db->query("SHOW FULL TABLES WHERE Table_type = 'BASE TABLE'");
        $tables = [];
        if ($tablesQuery) {
            foreach ($tablesQuery->result_array() as $tRow) {
                $tables[] = reset($tRow);
            }
        }

        // 3. Dump each table structure and chunked data
        $chunkSize = 300;
        foreach ($tables as $table) {
            // Drop statement
            fwrite($handle, "-- -------------------------------------------------------------\n");
            fwrite($handle, "-- Table structure for table `" . $table . "`\n");
            fwrite($handle, "-- -------------------------------------------------------------\n");
            fwrite($handle, "DROP TABLE IF EXISTS `" . $table . "`;\n");

            // Create statement
            $cQuery = $this->db->query("SHOW CREATE TABLE `" . $this->db->escape_str($table) . "`");
            if ($cQuery && $cQuery->num_rows() > 0) {
                $cRow = $cQuery->row_array();
                $createSql = $cRow['Create Table'] ?? reset($cRow);
                fwrite($handle, $createSql . ";\n\n");
            }

            // Dump Data in chunks
            $countQuery = $this->db->query("SELECT COUNT(*) as cnt FROM `" . $this->db->escape_str($table) . "`");
            $rowCount = ($countQuery && $countQuery->num_rows() > 0) ? (int)$countQuery->row()->cnt : 0;

            if ($rowCount > 0) {
                fwrite($handle, "-- Dumping data for table `" . $table . "` (" . $rowCount . " rows)\n");

                $offset = 0;
                while ($offset < $rowCount) {
                    $rowsQuery = $this->db->query("SELECT * FROM `" . $this->db->escape_str($table) . "` LIMIT " . (int)$chunkSize . " OFFSET " . (int)$offset);
                    if ($rowsQuery && $rowsQuery->num_rows() > 0) {
                        $rows = $rowsQuery->result_array();
                        if (!empty($rows)) {
                            // Column names
                            $firstRow = $rows[0];
                            $cols = array_map(function ($col) {
                                return '`' . $col . '`';
                            }, array_keys($firstRow));
                            $colsSql = implode(', ', $cols);

                            $valsList = [];
                            foreach ($rows as $r) {
                                $escapedVals = [];
                                foreach ($r as $val) {
                                    if (is_null($val)) {
                                        $escapedVals[] = 'NULL';
                                    } else {
                                        $escapedVals[] = $this->db->escape($val);
                                    }
                                }
                                $valsList[] = '(' . implode(', ', $escapedVals) . ')';
                            }

                            if (!empty($valsList)) {
                                $insertSql = "INSERT INTO `" . $table . "` (" . $colsSql . ") VALUES \n" . implode(",\n", $valsList) . ";\n";
                                fwrite($handle, $insertSql);
                            }
                        }
                    }
                    $offset += $chunkSize;
                }
                fwrite($handle, "\n");
            }
        }

        // 4. Write Footer
        $footer = "COMMIT;\n"
                . "SET FOREIGN_KEY_CHECKS=1;\n"
                . "-- ==============================================================\n"
                . "-- Backup completed successfully.\n"
                . "-- Total tables: " . count($tables) . "\n"
                . "-- ==============================================================\n";
        fwrite($handle, $footer);
        fclose($handle);

        // 5. Compress to ZIP using ZipArchive
        if (!class_exists('ZipArchive')) {
            // Fallback: If ZipArchive not present, keep in backup folder as zip or txt
            @copy($tempSqlFile, $this->backupPath . $baseName . '.sql');
            @unlink($tempSqlFile);
            return [
                'status' => true,
                'filename' => $baseName . '.sql',
                'filepath' => $this->backupPath . $baseName . '.sql',
                'size' => @filesize($this->backupPath . $baseName . '.sql'),
            ];
        }

        $zip = new ZipArchive();
        if ($zip->open($targetZipFile, ZipArchive::CREATE | ZipArchive::OVERWRITE) === TRUE) {
            $zip->addFile($tempSqlFile, $baseName . '.sql');
            $zip->close();
        } else {
            @unlink($tempSqlFile);
            return ['status' => false, 'error' => 'Failed to create zip archive.'];
        }

        // CRITICAL: Strictly delete the temporary .sql file immediately!
        @unlink($tempSqlFile);

        if (!file_exists($targetZipFile) || filesize($targetZipFile) == 0) {
            return ['status' => false, 'error' => 'Generated backup zip file is empty or missing.'];
        }

        return [
            'status' => true,
            'filename' => $baseName . '.zip',
            'filepath' => $targetZipFile,
            'size' => filesize($targetZipFile),
            'size_formatted' => bytesToSize('', filesize($targetZipFile)),
            'total_tables' => count($tables),
        ];
    }

    /**
     * Delete a single backup file with strict security validation
     */
    public function delete_backup($filename)
    {
        $filename = basename($filename);
        if (!preg_match('/^[a-zA-Z0-9_\-\.]+\.(zip|sql)$/', $filename)) {
            return false;
        }

        $target = $this->backupPath . $filename;
        if (file_exists($target) && is_file($target)) {
            return @unlink($target);
        }
        return false;
    }

    /**
     * Prune backups older than specified number of days
     */
    public function prune_backups($days = 30)
    {
        $days = (int)$days;
        if ($days <= 0) {
            $days = 30;
        }

        $backups = $this->get_backups_list();
        $cutoff = time() - ($days * 86400);
        $deleted = 0;

        foreach ($backups as $b) {
            if ($b['timestamp'] < $cutoff) {
                if ($this->delete_backup($b['filename'])) {
                    $deleted++;
                }
            }
        }
        return $deleted;
    }

    /**
     * Restore database from uploaded .zip or .sql file
     */
    public function restore_database($sourceFile)
    {
        @set_time_limit(0);
        @ini_set('memory_limit', '512M');

        if (!file_exists($sourceFile)) {
            return ['status' => false, 'error' => 'Backup file does not exist on server.'];
        }

        $ext = strtolower(pathinfo($sourceFile, PATHINFO_EXTENSION));
        $sqlFilePath = '';
        $tempExtractDir = '';

        if ($ext === 'zip') {
            if (!class_exists('ZipArchive')) {
                return ['status' => false, 'error' => 'PHP ZipArchive extension is required to extract zip backups.'];
            }
            $tempExtractDir = $this->tempPath . 'restore_' . uniqid() . '/';
            @mkdir($tempExtractDir, 0777, true);

            $zip = new ZipArchive();
            if ($zip->open($sourceFile) === TRUE) {
                $zip->extractTo($tempExtractDir);
                $zip->close();
            } else {
                return ['status' => false, 'error' => 'Failed to extract uploaded zip backup archive.'];
            }

            // Find SQL file inside extracted folder
            $foundSql = '';
            $files = scandir($tempExtractDir);
            foreach ($files as $f) {
                if (strtolower(pathinfo($f, PATHINFO_EXTENSION)) === 'sql') {
                    $foundSql = $tempExtractDir . $f;
                    break;
                }
            }

            if (empty($foundSql) || !file_exists($foundSql)) {
                $this->recursive_delete($tempExtractDir);
                return ['status' => false, 'error' => 'No valid .sql dump found inside the zip archive.'];
            }
            $sqlFilePath = $foundSql;
        } elseif ($ext === 'sql') {
            $sqlFilePath = $sourceFile;
        } else {
            return ['status' => false, 'error' => 'Unsupported backup file format. Only .zip and .sql are accepted.'];
        }

        // Execute SQL file statements
        $dbName = $this->db->database;
        $this->db->query("USE `" . $this->db->escape_str($dbName) . "`");
        $this->db->query("SET FOREIGN_KEY_CHECKS=0");

        $handle = @fopen($sqlFilePath, 'r');
        if (!$handle) {
            if (!empty($tempExtractDir)) {
                $this->recursive_delete($tempExtractDir);
            }
            return ['status' => false, 'error' => 'Unable to read SQL file.'];
        }

        $currentQuery = '';
        $executed = 0;
        $errors = [];

        while (($line = fgets($handle)) !== false) {
            $trimmed = trim($line);
            // Skip comments and empty lines
            if ($trimmed === '' || strpos($trimmed, '--') === 0 || strpos($trimmed, '/*') === 0 || strpos($trimmed, '#') === 0) {
                continue;
            }

            $currentQuery .= $line;

            // Check if end of statement
            if (substr(rtrim($trimmed), -1) === ';') {
                $q = trim($currentQuery);
                if (!empty($q)) {
                    if (!$this->db->query($q)) {
                        $err = $this->db->error();
                        if (!empty($err['message'])) {
                            $errors[] = $err['message'];
                        }
                    } else {
                        $executed++;
                    }
                }
                $currentQuery = '';
            }
        }
        fclose($handle);

        $this->db->query("SET FOREIGN_KEY_CHECKS=1");

        // Clean up extracted files
        if (!empty($tempExtractDir)) {
            $this->recursive_delete($tempExtractDir);
        }
        // Clean up source file if it was uploaded to temp
        if (strpos($sourceFile, 'db_temp') !== false && file_exists($sourceFile)) {
            @unlink($sourceFile);
        }

        if (!empty($errors) && $executed === 0) {
            return ['status' => false, 'error' => 'SQL Execution failed: ' . implode('; ', array_slice($errors, 0, 5))];
        }

        return [
            'status' => true,
            'executed_queries' => $executed,
            'warnings' => count($errors),
            'message' => 'Database successfully restored (' . $executed . ' queries executed).',
        ];
    }

    /**
     * Optimize single database table
     */
    public function optimize_table($table)
    {
        $table = preg_replace('/[^a-zA-Z0-9_]/', '', $table);
        if (empty($table)) {
            return false;
        }
        $res = $this->db->query("OPTIMIZE TABLE `" . $table . "`");
        return $res ? true : false;
    }

    /**
     * Optimize all tables in the database
     */
    public function optimize_all_tables()
    {
        $tables = $this->db->list_tables();
        $optimized = 0;
        foreach ($tables as $t) {
            if ($this->optimize_table($t)) {
                $optimized++;
            }
        }
        return $optimized;
    }

    /**
     * Repair or check single database table
     */
    public function repair_table($table)
    {
        $table = preg_replace('/[^a-zA-Z0-9_]/', '', $table);
        if (empty($table)) {
            return false;
        }
        $res = $this->db->query("CHECK TABLE `" . $table . "`");
        return $res ? true : false;
    }

    /**
     * Helper to recursively delete temporary directory
     */
    protected function recursive_delete($dir)
    {
        if (!is_dir($dir)) {
            return;
        }
        $items = scandir($dir);
        foreach ($items as $item) {
            if ($item === '.' || $item === '..') {
                continue;
            }
            $path = $dir . DIRECTORY_SEPARATOR . $item;
            if (is_dir($path)) {
                $this->recursive_delete($path);
            } else {
                @unlink($path);
            }
        }
        @rmdir($dir);
    }
}

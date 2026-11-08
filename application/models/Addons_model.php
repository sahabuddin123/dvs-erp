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
}

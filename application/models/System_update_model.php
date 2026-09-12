<?php
defined('BASEPATH') or exit('No direct script access allowed');

class System_update_model extends MY_Model
{
    public function __construct()
    {
        parent::__construct();
        $this->load->library('user_agent');
    }

    public function get_update_info()
    {
        $purchaseCode = $this->getPurchaseCode();
        return json_encode((object)[
            'latest_version' => $this->get_current_db_version(),
            'support_expiry_date' => 'Lifetime',
            'purchase_code' => !empty($purchaseCode['purchase_code']) ? $purchaseCode['purchase_code'] : 'VERIFIED',
            'block' => 0,
            'message' => 'Valid',
            'update_history' => ''
        ]);
    }

    public function getPurchaseCode()
    {
        $array = array(
            'purchase_username' => "",
            'purchase_code' => "",
        );
        $file = APPPATH . 'config/purchase_key.php';
        if (file_exists($file)) {
            @chmod($file, FILE_WRITE_MODE);
            $purchase = file_get_contents($file);
            $purchase = json_decode($purchase);
            if (is_array($purchase)) {
                $array['purchase_username'] = trim($purchase[0]);
                $array['purchase_code'] = trim($purchase[1]);
            }
        }
        return $array;
    }

    public function get_current_db_version()
    {
        $this->db->limit(1);
        $row = $this->db->get('migrations')->row();
        return $row ? (string)$row->version : '710';
    }

    public function getIP()
    {
        $client = @$_SERVER['HTTP_CLIENT_IP'];
        $forward = @$_SERVER['HTTP_X_FORWARDED_FOR'];
        $remote = $_SERVER['REMOTE_ADDR'];
        if (filter_var($client, FILTER_VALIDATE_IP)) {
            $ip = $client;
        } elseif (filter_var($forward, FILTER_VALIDATE_IP)) {
            $ip = $forward;
        } else {
            $ip = ($remote == "::1" ? "127.0.0.1" : $remote);
        }
        return $ip;
    }

    public function is_connected($host = 'www.google.com')
    {
        $connected = @fsockopen($host, 80);
        //website, port  (try 80 or 443)
        if ($connected) {
            $is_conn = true; //action when connected
            fclose($connected);
        } else {
            $is_conn = false; //action in connection failure
        }
        return $is_conn;
    }

    public function upgrade_database_silent()
    {
        $this->load->config('migration');
        $this->load->library('migration', array(
            'migration_enabled' => true,
            'migration_type' => $this->config->item('migration_type'),
            'migration_table' => $this->config->item('migration_table'),
            'migration_auto_latest' => $this->config->item('migration_auto_latest'),
            'migration_version' => $this->config->item('migration_version'),
            'migration_path' => $this->config->item('migration_path'),
        ));
        if ($this->migration->current() === false) {
            return array(
                'success' => 0,
                'message' => $this->migration->error_string(),
            );
        } else {
            return array(
                'success' => 1,
            );
        }
    }

    public function addonChk()
    {
        $this->db->select('prefix,version');
        $addons = $this->db->get('addon')->result();
        $data = [];
        $result = "";
        if (!empty($addons)) {
            foreach ($addons as $key => $value) {
                $data[] = $value->prefix ."-". $value->version; 
            }
        }
        if (is_array($data) && !empty($data)) {
            $result = implode("||", $data);
        }
        return $result;
    }
}

<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Install_model extends CI_Model
{

    public function __construct()
    {
        parent::__construct();
    }

    public function write_database_config($data)
    {
        $hostname = $data['hostname'];
        $username = $data['username'];
        $password = $data['password'];
        $database = $data['database'];

        $database_path = APPPATH . 'config/database.php';
        $database_file = file_get_contents($database_path);
        $database_file = trim($database_file);

        $database_file = str_replace("APP_DB_HOST", $hostname, $database_file);
        $database_file = str_replace("APP_DB_USERNAME", $username, $database_file);
        $database_file = str_replace("APP_DB_PASSWORD", $password, $database_file);
        $database_file = str_replace("APP_DB_NAME", $database, $database_file);

        // Write the new database.php file
        $handle = fopen($database_path, 'w+');
        // Chmod the file, in case the user forgot
        @chmod($database_path, 0777);
        // Verify file permissions
        if (is_writable($database_path)) {
            // Write the file
            if (fwrite($handle, $database_file)) {
                return true;
            } else {
                //file not write
                return false;
            }
        } else {
            //file is not writeable
            return false;
        }
    }

    public function clean_up_db_query()
    {
        $CI = &get_instance();
        while (mysqli_more_results($CI->db->conn_id) && mysqli_next_result($CI->db->conn_id)) {
            $dummyResult = mysqli_use_result($CI->db->conn_id);
            if ($dummyResult instanceof mysqli_result) {
                mysqli_free_result($CI->db->conn_id);
            }
        }
    }

    public function pass_hashed($password)
    {
        $hashed = password_hash($password, PASSWORD_DEFAULT);
        return $hashed;
    }

    public function update_autoload_installed()
    {
        $autoload_path = APPPATH . 'config/autoload.php';
        $autoload_file = file_get_contents($autoload_path);
        $autoload_file = trim($autoload_file);
        $autoload_file = str_replace("\$autoload['libraries'] = array('pagination','xmlrpc','form_validation','upload')", "\$autoload['libraries'] = array('database','session','pagination','xmlrpc','form_validation','upload','app_lib')", $autoload_file);
        $autoload_file = str_replace("\$autoload['helper'] = array('url','file','form','security')", "\$autoload['helper'] = array('url','file','form','security','directory','general')", $autoload_file);
        $autoload_file = str_replace("\$autoload['model'] = array()", "\$autoload['model'] = array('application_model')", $autoload_file);

        // Write the new database.php file
        $handle = fopen($autoload_path, 'w+');
        // Chmod the file, in case the user forgot
        @chmod($autoload_path, 0777);
        // Verify file permissions
        if (is_writable($autoload_path)) {
            // Write the file
            if (fwrite($handle, $autoload_file)) {
                return true;
            } else {
                //file not write
                return false;
            }
        } else {
            //file is not writeable
            return false;
        }
    }

    public function write_routes_config()
    {
        $routes_path = APPPATH . 'config/routes.php';
        $routes_file = file_get_contents($routes_path);
        $routes_file = trim($routes_file);
        $routes_file = str_replace("install", "home", $routes_file);

        // Write the new database.php file
        $handle = fopen($routes_path, 'w+');
        // Chmod the file, in case the user forgot
        @chmod($routes_path, 0777);
        // Verify file permissions
        if (is_writable($routes_path)) {
            // Write the file
            if (fwrite($handle, $routes_file)) {
                return true;
            } else {
                //file not write
                return false;
            }
        } else {
            //file is not writeable
            return false;
        }
    }

    public function update_config_installed($encryption_key)
    {
        $config_path = APPPATH . 'config/config.php';
        $config_file = file_get_contents($config_path);
        $config_file = trim($config_file);
        $config_file = str_replace("\$config['encryption_key'] = '';", "\$config['encryption_key'] = '" . $encryption_key . "';", $config_file);
        $config_file = str_replace("\$config['installed'] = FALSE;", "\$config['installed'] = TRUE;", $config_file);

        // Write the new database.php file
        $handle = fopen($config_path, 'w+');
        // Chmod the file, in case the user forgot
        @chmod($config_path, 0777);
        // Verify file permissions
        if (is_writable($config_path)) {
            // Write the file
            if (fwrite($handle, $config_file)) {
                return true;
            } else {
                //file not write
                return false;
            }
        } else {
            //file is not writeable
            return false;
        }
    }

    public function is_secure($url)
    {
        if ((!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] != 'off') || $_SERVER['SERVER_PORT'] == 443) {
            $val = 'https://' . $url;
        } else {
            $val = 'http://' . $url;
        }
        return $val;
    }
	
	public function call_CurlApi($post_data)
	{
		return (object) [
			'status'  => true,
			'message' => 'Success'
		];
	}
	
	public function getVerifyURL()
	{
		if ($this->is_connected()) {
			return 'https://ramomcoder.com/purchase/api/verify';
		}
		return false;
	}
	
	function is_connected($host = 'www.google.com')
	{
		$connected = @fsockopen($host, 80); 
		//website, port  (try 80 or 443)
		if ($connected){
			$is_conn = true; //action when connected
			fclose($connected);
		}else{
			$is_conn = false; //action in connection failure
		}
		return $is_conn;
	}
	
	public function getIP()
	{
		$client  = @$_SERVER['HTTP_CLIENT_IP'];
		$forward = @$_SERVER['HTTP_X_FORWARDED_FOR'];
		$remote  = $_SERVER['REMOTE_ADDR'];
		if (filter_var($client, FILTER_VALIDATE_IP)) {
			$ip = $client;
		} elseif (filter_var($forward, FILTER_VALIDATE_IP)) {
			$ip = $forward;
		} else {
			$ip = ($remote == "::1" ? "127.0.0.1" : $remote);
		}
		return $ip;
	}
	
	function timezone_list()
	{
		static $timezones = null;
		if ($timezones === null) {
			$timezones = [];
			$offsets = [];
			$now = new DateTime('now', new DateTimeZone('UTC'));
				foreach (DateTimeZone::listIdentifiers() as $timezone) {
				$now->setTimezone(new DateTimeZone($timezone));
				$offsets[] = $offset = $now->getOffset();
				$timezones[$timezone] = '(' . $this->format_GMT_offset($offset) . ') ' . $this->format_timezone_name($timezone);
			}
			array_multisort($offsets, $timezones);
		}
		return $timezones;
	}

	function format_GMT_offset($offset)
	{
	    $hours = intval($offset / 3600);
	    $minutes = abs(intval($offset % 3600 / 60));
	    return 'GMT' . ($offset ? sprintf('%+03d:%02d', $hours, $minutes) : '');
	}

	function format_timezone_name($name)
	{
	    $name = str_replace('/', ', ', $name);
	    $name = str_replace('_', ' ', $name);
	    $name = str_replace('St ', 'St. ', $name);
	    return $name;
	}
}

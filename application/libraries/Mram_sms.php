<?php
defined('BASEPATH') or exit('No direct script access allowed');

/**
 * M-RAM Technologies Ltd SMS Gateway Library
 * Official API Integration for msg.mram.com.bd
 */
class Mram_sms
{
    protected $api_key;
    protected $sender_id;
    protected $api_url;
    protected $sms_type;
    protected $branch_id;
    protected $ci;

    public function __construct()
    {
        $this->ci = &get_instance();
        
        $branchID = 1;
        if (function_exists('is_superadmin_loggedin') && is_superadmin_loggedin()) {
            $branchID = $this->ci->input->post('branch_id');
            if (empty($branchID)) {
                $branchID = $this->ci->input->get('branch_id');
            }
            if (empty($branchID) && function_exists('get_loggedin_branch_id')) {
                $branchID = get_loggedin_branch_id();
            }
        } elseif (function_exists('get_loggedin_branch_id')) {
            $branchID = get_loggedin_branch_id();
        }

        if (empty($branchID)) {
            $branchID = 1;
        }

        $this->branch_id = $branchID;
        $this->loadCredentials($branchID);
    }

    /**
     * Load credentials from .env first, fallback to sms_credential table
     */
    public function loadCredentials($branchID)
    {
        $this->api_key   = function_exists('env') ? env('SMS_MRAM_API_KEY', '') : '';
        $this->sender_id = function_exists('env') ? env('SMS_MRAM_SENDER_ID', '') : '';
        $this->api_url   = function_exists('env') ? env('SMS_MRAM_API_URL', 'https://msg.mram.com.bd/smsapi') : 'https://msg.mram.com.bd/smsapi';
        $this->sms_type  = 'auto';

        // Fallback to database if .env is not set
        if (empty($this->api_key) || empty($this->sender_id)) {
            $credential = $this->ci->db->get_where('sms_credential', [
                'sms_api_id' => 9,
                'branch_id'  => $branchID
            ])->row_array();

            if (!empty($credential)) {
                if (empty($this->api_key))   $this->api_key   = isset($credential['field_one']) ? trim($credential['field_one']) : '';
                if (empty($this->sender_id)) $this->sender_id = isset($credential['field_two']) ? trim($credential['field_two']) : '';
                if (!empty($credential['field_three'])) $this->api_url   = trim($credential['field_three']);
                if (!empty($credential['field_four']))  $this->sms_type  = trim($credential['field_four']);
            }
        }

        // Hardcoded safety default if still empty (from user credentials)
        if (empty($this->api_key)) {
            $this->api_key = 'C40002956aa1a646106c41.09766335';
        }
        if (empty($this->sender_id)) {
            $this->sender_id = '8809601017199';
        }
    }

    /**
     * Send SMS to one or multiple recipients and log to sms_logs table
     * 
     * @param string|array $to Single mobile number or array/comma-separated numbers
     * @param string $message The SMS text
     * @param array $metadata Additional metadata: recipient_name, recipient_type, sent_by, branch_id
     * @return string|bool API response string or false on failure
     */
    public function send($to, $message, $metadata = [])
    {
        if (empty($this->api_key) || empty($this->sender_id)) {
            log_message('error', 'MRAM SMS: Missing API Key or Sender ID.');
            return false;
        }

        // Parse individual numbers
        $numberArray = $this->parseNumbers($to);
        if (empty($numberArray)) {
            log_message('error', 'MRAM SMS: Invalid contact number(s).');
            return false;
        }

        // Format contacts string for API (joined with '+')
        $contacts = implode('+', $numberArray);

        // Determine content type: text vs unicode
        $contentType = $this->sms_type;
        $isUnicode = preg_match('/[^\x00-\x7F]/', $message);
        if ($contentType === 'auto' || empty($contentType)) {
            $contentType = $isUnicode ? 'unicode' : 'text';
        }

        // Calculate SMS count (number of parts)
        $charLength = mb_strlen($message, 'UTF-8');
        if ($contentType === 'unicode') {
            $smsCount = ($charLength <= 70) ? 1 : (int)ceil($charLength / 67);
        } else {
            $smsCount = ($charLength <= 160) ? 1 : (int)ceil($charLength / 153);
        }

        $postData = [
            'api_key'  => $this->api_key,
            'type'     => $contentType,
            'contacts' => $contacts,
            'senderid' => $this->sender_id,
            'msg'      => $message,
        ];

        $url = !empty($this->api_url) ? $this->api_url : 'https://msg.mram.com.bd/smsapi';

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $postData);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt($ch, CURLOPT_TIMEOUT, 30);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 15);

        $response = curl_exec($ch);
        $err = curl_error($ch);
        curl_close($ch);

        $status = 'sent';
        if ($err) {
            $status = 'failed';
            $response = 'cURL Error: ' . $err;
            log_message('error', 'MRAM SMS cURL Error: ' . $err);
        } elseif (is_string($response) && (stripos($response, 'error') !== false || stripos($response, 'invalid') !== false || stripos($response, 'insufficient') !== false)) {
            $status = 'failed';
        }

        // Log into database sms_logs table
        $this->logSmsBatch($numberArray, $message, $contentType, $smsCount, $status, $response, $metadata);

        log_message('debug', 'MRAM SMS Response: ' . $response);
        return $response;
    }

    /**
     * Log SMS dispatch into sms_logs table
     */
    protected function logSmsBatch($numberArray, $message, $contentType, $smsCount, $status, $response, $metadata = [])
    {
        if (!$this->ci->db->table_exists('sms_logs')) {
            return;
        }

        $sentBy = $metadata['sent_by'] ?? (function_exists('get_loggedin_user_id') ? (int)get_loggedin_user_id() : 0);
        $branchID = $metadata['branch_id'] ?? $this->branch_id;
        $recipientName = $metadata['recipient_name'] ?? null;
        $recipientType = $metadata['recipient_type'] ?? 'custom';
        $now = date('Y-m-d H:i:s');

        $rows = [];
        foreach ($numberArray as $num) {
            $rows[] = [
                'branch_id'      => $branchID,
                'recipient'      => $num,
                'recipient_name' => $recipientName,
                'recipient_type' => $recipientType,
                'message'        => $message,
                'sms_type'       => $contentType,
                'sms_count'      => $smsCount,
                'gateway'        => 'mram',
                'status'         => $status,
                'response'       => substr((string)$response, 0, 500),
                'sent_by'        => $sentBy,
                'created_at'     => $now,
            ];
        }

        if (count($rows) === 1) {
            $this->ci->db->insert('sms_logs', $rows[0]);
        } elseif (count($rows) > 1) {
            $this->ci->db->insert_batch('sms_logs', $rows);
        }
    }

    /**
     * Parse and format mobile numbers into array of 8801XXXXXXXXX numbers
     */
    public function parseNumbers($numbers)
    {
        if (is_array($numbers)) {
            $numList = $numbers;
        } else {
            $numList = preg_split('/[\s,;+]+/', trim($numbers));
        }

        $formatted = [];
        foreach ($numList as $num) {
            $n = preg_replace('/[^0-9]/', '', $num);
            if (empty($n)) {
                continue;
            }

            // 11 digits starting with 01 -> prepend 88
            if (strlen($n) == 11 && substr($n, 0, 2) === '01') {
                $n = '88' . $n;
            } elseif (strlen($n) == 10 && substr($n, 0, 1) === '1') {
                $n = '880' . $n;
            }

            if (strlen($n) >= 11) {
                $formatted[] = $n;
            }
        }

        return array_values(array_unique($formatted));
    }

    /**
     * Get real-time credit balance from M-RAM API
     * 
     * @return array
     */
    public function getBalance()
    {
        if (empty($this->api_key)) {
            return [
                'success' => false,
                'balance' => 0.00,
                'raw'     => 'API Key not configured',
                'currency'=> 'BDT'
            ];
        }

        $url = 'https://msg.mram.com.bd/miscapi/' . rawurlencode($this->api_key) . '/getBalance';
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt($ch, CURLOPT_TIMEOUT, 15);
        $res = curl_exec($ch);
        $err = curl_error($ch);
        curl_close($ch);

        if ($err) {
            return [
                'success' => false,
                'balance' => 0.00,
                'raw'     => 'Error: ' . $err,
                'currency'=> 'BDT'
            ];
        }

        $raw = trim((string)$res);
        $balance = 0.00;
        $currency = 'BDT';

        // Example response: "Your Balance is:BDT 506.57"
        if (preg_match('/([A-Za-z]+)\s*([0-9.]+)/', $raw, $matches)) {
            $currency = $matches[1];
            $balance = floatval($matches[2]);
        } elseif (preg_match('/([0-9.]+)/', $raw, $matches)) {
            $balance = floatval($matches[1]);
        }

        return [
            'success'  => true,
            'balance'  => $balance,
            'raw'      => $raw,
            'currency' => $currency,
        ];
    }

    /**
     * Get SMS Price / Rate per SMS from API
     */
    public function getPrice()
    {
        if (empty($this->api_key)) {
            return false;
        }

        $url = 'https://msg.mram.com.bd/miscapi/' . rawurlencode($this->api_key) . '/getPrice';
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_TIMEOUT, 15);
        $res = curl_exec($ch);
        curl_close($ch);

        return trim((string)$res);
    }
}

<?php
defined('BASEPATH') or exit('No direct script access allowed');

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
        if (is_superadmin_loggedin()) {
            $branchID = $this->ci->input->post('branch_id');
            if (empty($branchID)) {
                $branchID = $this->ci->input->get('branch_id');
            }
            if (empty($branchID)) {
                $branchID = get_loggedin_branch_id();
            }
        } else {
            $branchID = get_loggedin_branch_id();
        }

        if (empty($branchID)) {
            $branchID = 1;
        }

        $this->branch_id = $branchID;
        $this->loadCredentials($branchID);
    }

    public function loadCredentials($branchID)
    {
        $credential = $this->ci->db->get_where('sms_credential', [
            'sms_api_id' => 9,
            'branch_id'  => $branchID
        ])->row_array();

        $this->api_key   = isset($credential['field_one']) ? trim($credential['field_one']) : '';
        $this->sender_id = isset($credential['field_two']) ? trim($credential['field_two']) : '';
        $this->api_url   = !empty($credential['field_three']) ? trim($credential['field_three']) : 'https://msg.mram.com.bd/smsapi';
        $this->sms_type  = !empty($credential['field_four']) ? trim($credential['field_four']) : 'auto';
    }

    /**
     * Send SMS to one or multiple recipients
     * 
     * @param string|array $to Single mobile number or array/comma-separated numbers
     * @param string $message The SMS text
     * @return string|bool API response string or false on failure
     */
    public function send($to, $message)
    {
        if (empty($this->api_key) || empty($this->sender_id)) {
            log_message('error', 'MRAM SMS: Missing API Key or Sender ID.');
            return false;
        }

        // Format and sanitize mobile numbers
        $contacts = $this->formatContacts($to);
        if (empty($contacts)) {
            log_message('error', 'MRAM SMS: Invalid contact number(s).');
            return false;
        }

        // Determine content type: text vs unicode
        $contentType = $this->sms_type;
        if ($contentType === 'auto' || empty($contentType)) {
            // Check if string contains non-ASCII characters (e.g. Bangla)
            if (preg_match('/[^\x00-\x7F]/', $message)) {
                $contentType = 'unicode';
            } else {
                $contentType = 'text';
            }
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

        if ($err) {
            log_message('error', 'MRAM SMS cURL Error: ' . $err);
            return false;
        }

        log_message('debug', 'MRAM SMS Response: ' . $response);
        return $response;
    }

    /**
     * Format mobile numbers into Bangladeshi international format (8801XXXXXXXXX)
     * Multiple numbers are joined with '+' as per API spec
     */
    protected function formatContacts($numbers)
    {
        if (is_array($numbers)) {
            $numList = $numbers;
        } else {
            // Split by comma, semicolon, newline, or plus
            $numList = preg_split('/[\s,;+]+/', trim($numbers));
        }

        $formatted = [];
        foreach ($numList as $num) {
            $n = preg_replace('/[^0-9]/', '', $num);
            if (empty($n)) {
                continue;
            }

            // If 11 digits starting with 01 (e.g. 01712345678), prepend 88
            if (strlen($n) == 11 && substr($n, 0, 2) === '01') {
                $n = '88' . $n;
            } elseif (strlen($n) == 10 && substr($n, 0, 1) === '1') {
                $n = '880' . $n;
            }

            $formatted[] = $n;
        }

        return implode('+', array_unique($formatted));
    }

    /**
     * Get credit balance from API
     */
    public function getBalance()
    {
        if (empty($this->api_key)) {
            return false;
        }

        $url = 'https://msg.mram.com.bd/miscapi/' . rawurlencode($this->api_key) . '/getBalance';
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_TIMEOUT, 15);
        $res = curl_exec($ch);
        curl_close($ch);

        return $res;
    }
}

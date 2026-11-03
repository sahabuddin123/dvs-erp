<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * bKash Payment Gateway Library (Tokenized Checkout API v1.2.0)
 * 
 * Supports both Live and Sandbox environments,
 * Token Grant, Payment Creation, Execution, Query, and Status check.
 */
class Bkash
{
    protected $app_key;
    protected $app_secret;
    protected $username;
    protected $password;
    protected $sandbox = true;
    protected $base_url;
    protected $token;

    public function __construct($config = [])
    {
        if (function_exists('get_instance')) {
            $CI =& get_instance();
        }

        // 1. Load from passed config
        // 2. Load from .env if present
        // 3. Fallback to database payment_config
        $this->app_key = $config['bkash_app_key'] ?? (function_exists('env') ? env('BKASH_APP_KEY', '') : '');
        $this->app_secret = $config['bkash_app_secret'] ?? (function_exists('env') ? env('BKASH_APP_SECRET', '') : '');
        $this->username = $config['bkash_username'] ?? (function_exists('env') ? env('BKASH_USERNAME', '') : '');
        $this->password = $config['bkash_password'] ?? (function_exists('env') ? env('BKASH_PASSWORD', '') : '');
        $this->sandbox = isset($config['bkash_sandbox']) ? (bool)$config['bkash_sandbox'] : (function_exists('env') ? (bool)env('BKASH_SANDBOX', true) : true);

        $this->base_url = $this->sandbox
            ? 'https://tokenized.sandbox.bka.sh/v1.2.0-beta/tokenized'
            : 'https://tokenized.pay.bka.sh/v1.2.0-beta/tokenized';
    }

    /**
     * Reinitialize with branch credentials
     */
    public function initialize($config = [])
    {
        if (!empty($config['bkash_app_key'])) $this->app_key = $config['bkash_app_key'];
        if (!empty($config['bkash_app_secret'])) $this->app_secret = $config['bkash_app_secret'];
        if (!empty($config['bkash_username'])) $this->username = $config['bkash_username'];
        if (!empty($config['bkash_password'])) $this->password = $config['bkash_password'];
        if (isset($config['bkash_sandbox'])) $this->sandbox = (bool)$config['bkash_sandbox'];

        $this->base_url = $this->sandbox
            ? 'https://tokenized.sandbox.bka.sh/v1.2.0-beta/tokenized'
            : 'https://tokenized.pay.bka.sh/v1.2.0-beta/tokenized';
        return $this;
    }

    /**
     * Check if bKash is configured
     */
    public function is_configured()
    {
        return !empty($this->app_key) && !empty($this->app_secret) && !empty($this->username) && !empty($this->password);
    }

    /**
     * Grant id_token
     */
    public function grant_token()
    {
        $postData = json_encode([
            'app_key'    => $this->app_key,
            'app_secret' => $this->app_secret,
        ]);

        $headers = [
            'Content-Type: application/json',
            'username: ' . $this->username,
            'password: ' . $this->password,
        ];

        $ch = curl_init($this->base_url . '/checkout/token/grant');
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'POST');
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $postData);
        curl_setopt($ch, CURLOPT_TIMEOUT, 30);
        $result = curl_exec($ch);
        curl_close($ch);

        $response = json_decode($result, true);
        if (!empty($response['id_token'])) {
            $this->token = $response['id_token'];
            return $response['id_token'];
        }

        return false;
    }

    /**
     * Create checkout payment
     */
    public function create_payment($amount, $invoiceNo, $callbackUrl, $payerReference = '')
    {
        if (empty($this->token)) {
            $this->grant_token();
        }

        if (empty($this->token)) {
            return ['status' => false, 'message' => 'Failed to obtain bKash authorization token'];
        }

        $postData = json_encode([
            'mode'                  => '0011',
            'amount'                => number_format((float)$amount, 2, '.', ''),
            'payerReference'        => $payerReference ?: "Invoice #{$invoiceNo}",
            'callbackURL'           => $callbackUrl,
            'currency'              => 'BDT',
            'intent'                => 'sale',
            'merchantInvoiceNumber' => 'INV' . time() . rand(10, 99),
        ]);

        $headers = [
            'Content-Type: application/json',
            'Authorization: ' . $this->token,
            'X-APP-Key: ' . $this->app_key,
        ];

        $ch = curl_init($this->base_url . '/checkout/create');
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'POST');
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $postData);
        curl_setopt($ch, CURLOPT_TIMEOUT, 30);
        $result = curl_exec($ch);
        curl_close($ch);

        $response = json_decode($result, true);
        if (!empty($response['bkashURL'])) {
            return [
                'status'    => true,
                'bkashURL'  => $response['bkashURL'],
                'paymentID' => $response['paymentID'] ?? '',
            ];
        }

        return [
            'status'  => false,
            'message' => $response['statusMessage'] ?? 'bKash payment initialization failed',
        ];
    }

    /**
     * Execute payment
     */
    public function execute_payment($paymentID)
    {
        if (empty($this->token)) {
            $this->grant_token();
        }

        $postData = json_encode(['paymentID' => $paymentID]);

        $headers = [
            'Content-Type: application/json',
            'Authorization: ' . $this->token,
            'X-APP-Key: ' . $this->app_key,
        ];

        $ch = curl_init($this->base_url . '/checkout/execute');
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'POST');
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $postData);
        curl_setopt($ch, CURLOPT_TIMEOUT, 30);
        $result = curl_exec($ch);
        curl_close($ch);

        return json_decode($result, true);
    }

    /**
     * Query payment status
     */
    public function query_payment($paymentID)
    {
        if (empty($this->token)) {
            $this->grant_token();
        }

        $postData = json_encode(['paymentID' => $paymentID]);

        $headers = [
            'Content-Type: application/json',
            'Authorization: ' . $this->token,
            'X-APP-Key: ' . $this->app_key,
        ];

        $ch = curl_init($this->base_url . '/checkout/payment/status');
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'POST');
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $postData);
        curl_setopt($ch, CURLOPT_TIMEOUT, 30);
        $result = curl_exec($ch);
        curl_close($ch);

        return json_decode($result, true);
    }
}

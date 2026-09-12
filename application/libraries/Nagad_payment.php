<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Nagad_payment
{
    protected $ci;
    public $api_config;

    public function __construct()
    {
        $this->ci = &get_instance();
        $this->initialize();
    }

    public function initialize($branchID = '')
    {
        if (empty($branchID)) {
            $branchID = get_loggedin_branch_id();
        }
        if (empty($branchID)) {
            $branchID = 1;
        }
        $this->api_config = $this->ci->db->select('nagad_merchant_id,nagad_merchant_number,nagad_public_key,nagad_private_key,nagad_sandbox,nagad_status')
            ->where('branch_id', $branchID)
            ->get('payment_config')
            ->row_array();

        if (empty($this->api_config)) {
            $this->api_config = [
                'nagad_merchant_id'      => '',
                'nagad_merchant_number'  => '',
                'nagad_public_key'       => '',
                'nagad_private_key'      => '',
                'nagad_sandbox'          => 0,
                'nagad_status'           => 0,
            ];
        }
    }

    public function getBaseUrl()
    {
        if (!empty($this->api_config['nagad_sandbox']) && $this->api_config['nagad_sandbox'] == 1) {
            return 'http://sandbox.mynagad.com:10080/remote-payment-gateway-1.0/api/dfs/';
        }
        return 'https://api.mynagad.com/api/dfs/';
    }

    public function generateRandomString($length = 40)
    {
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $charactersLength = strlen($characters);
        $randomString = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[rand(0, $charactersLength - 1)];
        }
        return $randomString;
    }

    public function encryptWithPublicKey($data)
    {
        $pgwPublicKey = $this->api_config['nagad_public_key'];
        if (empty($pgwPublicKey)) return false;
        if (strpos($pgwPublicKey, '-----BEGIN') === false) {
            $pgwPublicKey = "-----BEGIN PUBLIC KEY-----\n" . trim($pgwPublicKey) . "\n-----END PUBLIC KEY-----";
        }
        $pubKeyRes = openssl_pkey_get_public($pgwPublicKey);
        if (!$pubKeyRes) return false;
        openssl_public_encrypt($data, $crypttext, $pubKeyRes);
        return base64_encode($crypttext);
    }

    public function signatureGenerate($data)
    {
        $merchantPrivateKey = $this->api_config['nagad_private_key'];
        if (empty($merchantPrivateKey)) return false;
        if (strpos($merchantPrivateKey, '-----BEGIN') === false) {
            $merchantPrivateKey = "-----BEGIN RSA PRIVATE KEY-----\n" . trim($merchantPrivateKey) . "\n-----END RSA PRIVATE KEY-----";
        }
        $priKeyRes = openssl_pkey_get_private($merchantPrivateKey);
        if (!$priKeyRes) return false;
        openssl_sign($data, $signature, $priKeyRes, OPENSSL_ALGO_SHA256);
        return base64_encode($signature);
    }

    public function decryptWithPrivateKey($crypttext)
    {
        $merchantPrivateKey = $this->api_config['nagad_private_key'];
        if (empty($merchantPrivateKey)) return false;
        if (strpos($merchantPrivateKey, '-----BEGIN') === false) {
            $merchantPrivateKey = "-----BEGIN RSA PRIVATE KEY-----\n" . trim($merchantPrivateKey) . "\n-----END RSA PRIVATE KEY-----";
        }
        $priKeyRes = openssl_pkey_get_private($merchantPrivateKey);
        if (!$priKeyRes) return false;
        openssl_private_decrypt(base64_decode($crypttext), $decrypted, $priKeyRes);
        return $decrypted;
    }

    /**
     * Initiate Payment with Nagad
     */
    public function initiatePayment($orderId, $amount, $callbackUrl)
    {
        $merchantId = trim($this->api_config['nagad_merchant_id']);
        if (empty($merchantId)) {
            return ['status' => false, 'message' => 'Nagad Merchant ID is missing'];
        }

        $dateTime = date('YmdHis');
        $random = $this->generateRandomString(40);

        $sensitiveData = [
            'merchantId' => $merchantId,
            'datetime'   => $dateTime,
            'orderId'    => $orderId,
            'challenge'  => $random,
        ];

        $postData = [
            'accountNumber'     => $this->api_config['nagad_merchant_number'],
            'dateTime'          => $dateTime,
            'sensitiveData'     => $this->encryptWithPublicKey(json_encode($sensitiveData)),
            'signature'         => $this->signatureGenerate(json_encode($sensitiveData)),
        ];

        $url = $this->getBaseUrl() . "check-out/initialize/" . $merchantId . "/" . $orderId;

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Content-Type: application/json',
            'X-KM-Api-Version: v-0.2.0',
            'X-KM-IP-V4: ' . $this->ci->input->ip_address(),
            'X-KM-Client-Type: PC_WEB',
        ]);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($postData));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        $result = curl_exec($ch);
        curl_close($ch);

        $response = json_decode($result, true);
        if (isset($response['sensitiveData']) && isset($response['signature'])) {
            $plain = $this->decryptWithPrivateKey($response['sensitiveData']);
            $plainData = json_decode($plain, true);

            if (isset($plainData['paymentReferenceId'])) {
                $paymentRefId = $plainData['paymentReferenceId'];

                // Complete initialization step
                $sensitiveOrderData = [
                    'merchantId'   => $merchantId,
                    'orderId'      => $orderId,
                    'currencyCode' => '050',
                    'amount'       => $amount,
                    'challenge'    => $random,
                ];

                $postCompleteData = [
                    'paymentRefId'       => $paymentRefId,
                    'sensitiveData'      => $this->encryptWithPublicKey(json_encode($sensitiveOrderData)),
                    'signature'          => $this->signatureGenerate(json_encode($sensitiveOrderData)),
                    'merchantCallbackURL'=> $callbackUrl,
                ];

                $completeUrl = $this->getBaseUrl() . "check-out/complete/" . $paymentRefId;
                $ch2 = curl_init();
                curl_setopt($ch2, CURLOPT_URL, $completeUrl);
                curl_setopt($ch2, CURLOPT_HTTPHEADER, [
                    'Content-Type: application/json',
                    'X-KM-Api-Version: v-0.2.0',
                    'X-KM-IP-V4: ' . $this->ci->input->ip_address(),
                    'X-KM-Client-Type: PC_WEB',
                ]);
                curl_setopt($ch2, CURLOPT_POST, 1);
                curl_setopt($ch2, CURLOPT_POSTFIELDS, json_encode($postCompleteData));
                curl_setopt($ch2, CURLOPT_RETURNTRANSFER, true);
                curl_setopt($ch2, CURLOPT_SSL_VERIFYPEER, false);
                $completeResult = curl_exec($ch2);
                curl_close($ch2);

                $completeResponse = json_decode($completeResult, true);
                if (isset($completeResponse['callBackUrl']) && !empty($completeResponse['callBackUrl'])) {
                    return ['status' => true, 'url' => $completeResponse['callBackUrl']];
                }
            }
        }

        return ['status' => false, 'message' => isset($response['message']) ? $response['message'] : 'Nagad payment initiation failed.'];
    }

    /**
     * Verify payment status
     */
    public function verifyPayment($paymentRefId)
    {
        $url = $this->getBaseUrl() . "verify/payment/" . $paymentRefId;
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Content-Type: application/json',
            'X-KM-Api-Version: v-0.2.0',
            'X-KM-IP-V4: ' . $this->ci->input->ip_address(),
            'X-KM-Client-Type: PC_WEB',
        ]);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        $result = curl_exec($ch);
        curl_close($ch);

        return json_decode($result, true);
    }
}

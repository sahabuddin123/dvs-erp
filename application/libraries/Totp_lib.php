<?php
defined('BASEPATH') or exit('No direct script access allowed');

/**
 * Standard RFC 6238 Time-based One-Time Password (TOTP) Library
 * Fully compatible with Google Authenticator, Microsoft Authenticator, Authy, etc.
 */
class Totp_lib
{
    private static $base32Lookup = [
        'A' => 0,  'B' => 1,  'C' => 2,  'D' => 3,
        'E' => 4,  'F' => 5,  'G' => 6,  'H' => 7,
        'I' => 8,  'J' => 9,  'K' => 10, 'L' => 11,
        'M' => 12, 'N' => 13, 'O' => 14, 'P' => 15,
        'Q' => 16, 'R' => 17, 'S' => 18, 'T' => 19,
        'U' => 20, 'V' => 21, 'W' => 22, 'X' => 23,
        'Y' => 24, 'Z' => 25, '2' => 26, '3' => 27,
        '4' => 28, '5' => 29, '6' => 30, '7' => 31,
        '=' => 0
    ];

    /**
     * Generate standard 16-character Base32 Secret Key
     */
    public function createSecret($length = 16)
    {
        $validChars = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ234567';
        $secret = '';
        for ($i = 0; $i < $length; $i++) {
            $secret .= $validChars[random_int(0, 31)];
        }
        return $secret;
    }

    /**
     * Decode Base32 string to binary bytes
     */
    public function base32Decode($secret)
    {
        $secret = strtoupper($secret);
        $buffer = 0;
        $bitsLeft = 0;
        $output = '';

        for ($i = 0; $i < strlen($secret); $i++) {
            $char = $secret[$i];
            if (!isset(self::$base32Lookup[$char])) {
                continue;
            }
            $val = self::$base32Lookup[$char];
            $buffer = ($buffer << 5) | $val;
            $bitsLeft += 5;

            if ($bitsLeft >= 8) {
                $bitsLeft -= 8;
                $output .= chr(($buffer >> $bitsLeft) & 0xFF);
            }
        }
        return $output;
    }

    /**
     * Calculate 6-digit TOTP code for a given time slice
     */
    public function getCode($secret, $timeSlice = null)
    {
        if ($timeSlice === null) {
            $timeSlice = floor(time() / 30);
        }
        $secretKey = $this->base32Decode($secret);
        // Pack time slice into 8-byte big-endian binary
        $time = chr(0) . chr(0) . chr(0) . chr(0) . pack('N*', $timeSlice);
        $hmac = hash_hmac('sha1', $time, $secretKey, true);
        $offset = ord(substr($hmac, -1)) & 0x0F;
        $hashpart = substr($hmac, $offset, 4);
        $value = unpack('N', $hashpart);
        $value = $value[1] & 0x7FFFFFFF;
        $modulo = pow(10, 6);
        return str_pad($value % $modulo, 6, '0', STR_PAD_LEFT);
    }

    /**
     * Verify submitted 6-digit code with time drift window (default +/- 1 step = 30 seconds)
     */
    public function verifyCode($secret, $code, $discrepancy = 1)
    {
        $code = trim((string)$code);
        if (strlen($code) !== 6 || !ctype_digit($code)) {
            return false;
        }

        $currentTimeSlice = floor(time() / 30);
        for ($i = -$discrepancy; $i <= $discrepancy; ++$i) {
            $calculated = $this->getCode($secret, $currentTimeSlice + $i);
            if (hash_equals($calculated, $code)) {
                return true;
            }
        }
        return false;
    }

    /**
     * Generate standard otpauth URL for QR Code scanner
     */
    public function getOtpAuthUrl($issuer, $accountName, $secret)
    {
        $cleanIssuer = rawurlencode($issuer);
        $cleanAccount = rawurlencode($accountName);
        return "otpauth://totp/{$cleanIssuer}:{$cleanAccount}?secret={$secret}&issuer={$cleanIssuer}";
    }

    /**
     * Render QR Code directly to Base64 Data URI
     */
    public function getQrCodeBase64($otpAuthUrl)
    {
        if (!class_exists('Ciqrcode')) {
            $CI = &get_instance();
            $CI->load->library('Ciqrcode');
        }

        ob_start();
        QRcode::png($otpAuthUrl, false, QR_ECLEVEL_M, 6, 2);
        $imageData = ob_get_clean();
        return 'data:image/png;base64,' . base64_encode($imageData);
    }

    /**
     * Detect browser and OS platform from User Agent string
     */
    public function detectBrowserAndPlatform($userAgent)
    {
        $browser = 'Unknown Browser';
        $platform = 'Unknown Platform';

        // Platform detection
        if (preg_match('/windows|win32/i', $userAgent)) {
            $platform = 'Windows 10 / 11';
        } elseif (preg_match('/macintosh|mac os x/i', $userAgent)) {
            $platform = 'Mac OS X';
        } elseif (preg_match('/android/i', $userAgent)) {
            $platform = 'Android';
        } elseif (preg_match('/iphone|ipad|ipod/i', $userAgent)) {
            $platform = 'iOS';
        } elseif (preg_match('/linux/i', $userAgent)) {
            $platform = 'Linux';
        }

        // Browser detection
        if (preg_match('/edg\/([0-9\.]+)/i', $userAgent, $matches)) {
            $browser = 'Edge ' . $matches[1];
        } elseif (preg_match('/chrome\/([0-9\.]+)/i', $userAgent, $matches)) {
            $browser = 'Chrome ' . $matches[1];
        } elseif (preg_match('/firefox\/([0-9\.]+)/i', $userAgent, $matches)) {
            $browser = 'Firefox ' . $matches[1];
        } elseif (preg_match('/safari\/([0-9\.]+)/i', $userAgent, $matches) && !preg_match('/chrome/i', $userAgent)) {
            $browser = 'Safari ' . $matches[1];
        } elseif (preg_match('/opera|opr\/([0-9\.]+)/i', $userAgent, $matches)) {
            $browser = 'Opera ' . (isset($matches[1]) ? $matches[1] : '');
        }

        return [
            'browser' => $browser,
            'platform' => $platform
        ];
    }
}

<?php
defined('BASEPATH') or exit('No direct script access allowed');

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
require_once(APPPATH . 'third_party/phpmailer/autoload.php');

class Mailer
{
    private $CI;
    public function __construct()
    {
        $this->CI = &get_instance();
    }

    /**
     * Resolve email configuration:
     * 1. Priority: .env configuration (MAIL_HOST, MAIL_USERNAME, etc.)
     * 2. Fallback: Database email_config table
     */
    public function get_config($branch_id = 0)
    {
        $mail_host = function_exists('env') ? env('MAIL_HOST') : getenv('MAIL_HOST');
        $mail_user = function_exists('env') ? env('MAIL_USERNAME') : getenv('MAIL_USERNAME');
        $mail_pass = function_exists('env') ? env('MAIL_PASSWORD') : getenv('MAIL_PASSWORD');
        $mail_port = function_exists('env') ? env('MAIL_PORT', 465) : (getenv('MAIL_PORT') ?: 465);
        $mail_enc  = function_exists('env') ? env('MAIL_ENCRYPTION', 'ssl') : (getenv('MAIL_ENCRYPTION') ?: 'ssl');
        $mail_from = function_exists('env') ? env('MAIL_FROM_ADDRESS') : getenv('MAIL_FROM_ADDRESS');
        $mail_proto = function_exists('env') ? env('MAIL_PROTOCOL', 'smtp') : (getenv('MAIL_PROTOCOL') ?: 'smtp');

        if (!empty($mail_host) && !empty($mail_user)) {
            return (object)[
                'protocol'        => !empty($mail_proto) ? $mail_proto : 'smtp',
                'smtp_host'       => $mail_host,
                'smtp_user'       => $mail_user,
                'smtp_pass'       => $mail_pass,
                'smtp_port'       => (int)$mail_port,
                'smtp_encryption' => $mail_enc,
                'smtp_auth'       => 'true',
                'email'           => !empty($mail_from) ? $mail_from : $mail_user,
            ];
        }

        // Database fallback
        $getConfig = null;
        if (!empty($branch_id)) {
            $getConfig = $this->CI->db->get_where('email_config', array('branch_id' => $branch_id))->row();
        }
        if (empty($getConfig)) {
            $getConfig = $this->CI->db->order_by('id', 'ASC')->get('email_config')->row();
        }

        return $getConfig;
    }

    public function send($data = array(), $err = false)
    {
        $branch_id = !empty($data['branch_id']) ? $data['branch_id'] : 0;
        $getConfig = $this->get_config($branch_id);

        if (!empty($getConfig)) {
            $school_name = function_exists('env') && env('MAIL_FROM_NAME') ? env('MAIL_FROM_NAME') : get_global_setting('institute_name');
            if (empty($school_name)) {
                $school_name = 'Dream Valley School';
            }

            $mail = new PHPMailer();
            $mail->CharSet = 'UTF-8';

            if ($getConfig->protocol == 'smtp') {
                $mail->isSMTP();
                $mail->SMTPDebug = SMTP::DEBUG_OFF;
                $mail->Timeout = 3;
                $mail->Host = trim($getConfig->smtp_host);
                $mail->Port = (int)trim($getConfig->smtp_port);
                if (!empty($getConfig->smtp_encryption)) {
                    $mail->SMTPSecure = trim($getConfig->smtp_encryption);
                }
                $mail->SMTPAuth = ($getConfig->smtp_auth === 'true' || $getConfig->smtp_auth === true || $getConfig->smtp_auth === 1 || $getConfig->smtp_auth === '1');
                $mail->Username = trim($getConfig->smtp_user);
                $mail->Password = trim($getConfig->smtp_pass);

                // SSL options to ensure custom mail server certificates connect cleanly
                $mail->SMTPOptions = array(
                    'ssl' => array(
                        'verify_peer' => false,
                        'verify_peer_name' => false,
                        'allow_self_signed' => true
                    )
                );
            } else {
                $mail->isSendmail();
            }

            $mail->isHTML(true);

            if (!empty($data['file'])) {
               $mail->addStringAttachment($data['file'], $data['file_name']);
            }

            $from_email = !empty($getConfig->email) ? $getConfig->email : $getConfig->smtp_user;
            $mail->setFrom($from_email, $school_name);
            $mail->addReplyTo($from_email, $school_name);
            $mail->addAddress($data['recipient']);
            $mail->Subject = $data['subject'];
            $mail->AltBody = strip_tags($data['message']);
            $mail->Body = $data['message'];

            if ($mail->send()) {
                return true;
            } else {
                log_message('error', 'PHPMailer Send Error: ' . $mail->ErrorInfo);
                if ($err == false) {
                    return false;
                } else {
                    return $mail->ErrorInfo;
                }
            }
        } else {
            log_message('error', 'PHPMailer: No email configuration found.');
            return false;
        }
    }
}

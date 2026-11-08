<?php
defined('BASEPATH') or exit('No direct script access allowed');

require_once(APPPATH . 'third_party/bigbluebutton/vendor/autoload.php');
use BigBlueButton\BigBlueButton;
use BigBlueButton\Parameters\CreateMeetingParameters;
use BigBlueButton\Parameters\JoinMeetingParameters;
use BigBlueButton\Parameters\EndMeetingParameters;
use BigBlueButton\Parameters\GetMeetingInfoParameters;
use BigBlueButton\Parameters\GetRecordingsParameters;
use BigBlueButton\Parameters\DeleteRecordingsParameters;
use BigBlueButton\Parameters\IsMeetingRunningParameters;

#[\AllowDynamicProperties]
class Bigbluebutton_lib {

    public $CI;
    private $bbb_security_salt = "";
    private $bbb_server_base_url = "";

    public function __construct($api_keys = array()) {
        $this->CI = &get_instance();
        if (!empty($api_keys)) {
            $this->bbb_security_salt = $api_keys['bbb_security_salt'] ?? '';
            $this->bbb_server_base_url = $api_keys['bbb_server_base_url'] ?? '';
        }
    }

    private function putEnv()
    {
        $baseUrl = rtrim((string)$this->bbb_server_base_url, '/');
        if (!empty($baseUrl)) {
            if (substr($baseUrl, -14) !== '/bigbluebutton') {
                $baseUrl .= '/bigbluebutton/';
            } else {
                $baseUrl .= '/';
            }
        }
        putenv("BBB_SECURITY_SALT=" . $this->bbb_security_salt);
        putenv("BBB_SERVER_BASE_URL=" . $baseUrl);
        $_ENV['BBB_SECURITY_SALT'] = $this->bbb_security_salt;
        $_ENV['BBB_SERVER_BASE_URL'] = $baseUrl;
        $_SERVER['BBB_SECURITY_SALT'] = $this->bbb_security_salt;
        $_SERVER['BBB_SERVER_BASE_URL'] = $baseUrl;
    }

    public function createMeeting($data = array())
    {
        try {
            $this->putEnv();
            $bbb = new BigBlueButton();
            $urlLogout = base_url('live_class/bbb_callback');
            $createMeetingParams = new CreateMeetingParameters($data['meeting_id'], $data['title']);
            $createMeetingParams->setAttendeePassword($data['attendee_password']);
            $createMeetingParams->setModeratorPassword($data['moderator_password']);
            $createMeetingParams->setDuration($data['duration']);
            $createMeetingParams->setMaxParticipants($data['max_participants']);
            $createMeetingParams->setMuteOnStart($data['mute_on_start'] == 0 ? false : true);
            $createMeetingParams->setWebcamsOnlyForModerator(true);
            $createMeetingParams->setRecord($data['set_record'] == 0 ? false : true);
            $createMeetingParams->setAllowStartStopRecording($data['set_record'] == 0 ? false : true);
            $createMeetingParams->setAutoStartRecording($data['set_record'] ? false : true);
            $createMeetingParams->setLogoutUrl($urlLogout);
            $response = $bbb->createMeeting($createMeetingParams);
            if ($response->getReturnCode() == 'FAILED') {
                return false;
            } else {
                $joinMeetingParams = new JoinMeetingParameters($data['meeting_id'], $data['title'], $data['moderator_password']);
                $joinMeetingParams->setUsername($data['presen_name']);
                $joinMeetingParams->setRedirect(true);
                $url = $bbb->getJoinMeetingURL($joinMeetingParams);
                return $url;
            }
        } catch (\Throwable $e) {
            log_message('error', 'BigBlueButton createMeeting error: ' . $e->getMessage());
            return false;
        }
    }

    public function joinMeeting($data = array())
    {
        try {
            $this->putEnv();
            $bbb = new BigBlueButton();
            $joinMeetingParams = new JoinMeetingParameters($data['meeting_id'], $data['title'], $data['attendee_password']);
            $joinMeetingParams->setUsername($data['presen_name']);
            $joinMeetingParams->setRedirect(true);
            $url = $bbb->getJoinMeetingURL($joinMeetingParams);
            return $url;
        } catch (\Throwable $e) {
            log_message('error', 'BigBlueButton joinMeeting error: ' . $e->getMessage());
            return false;
        }
    }

    public function isMeetingRunning($meeting_id)
    {
        try {
            $this->putEnv();
            $bbb = new BigBlueButton();
            $params = new IsMeetingRunningParameters($meeting_id);
            $response = $bbb->isMeetingRunning($params);
            if ($response->getReturnCode() == 'SUCCESS') {
                return $response->isRunning();
            }
            return false;
        } catch (\Throwable $e) {
            log_message('error', 'BigBlueButton isMeetingRunning error: ' . $e->getMessage());
            return false;
        }
    }

    public function endMeeting($meeting_id, $moderator_password)
    {
        try {
            $this->putEnv();
            $bbb = new BigBlueButton();
            $params = new EndMeetingParameters($meeting_id, $moderator_password);
            $response = $bbb->endMeeting($params);
            return ($response->getReturnCode() == 'SUCCESS');
        } catch (\Throwable $e) {
            log_message('error', 'BigBlueButton endMeeting error: ' . $e->getMessage());
            return false;
        }
    }

    public function getMeetingInfo($meeting_id, $moderator_password)
    {
        try {
            $this->putEnv();
            $bbb = new BigBlueButton();
            $params = new GetMeetingInfoParameters($meeting_id, $moderator_password);
            $response = $bbb->getMeetingInfo($params);
            if ($response->getReturnCode() == 'SUCCESS') {
                return $response->getRawXml();
            }
            return null;
        } catch (\Throwable $e) {
            log_message('error', 'BigBlueButton getMeetingInfo error: ' . $e->getMessage());
            return null;
        }
    }

    public function getRecordings($meeting_id = null)
    {
        try {
            $this->putEnv();
            $bbb = new BigBlueButton();
            $params = new GetRecordingsParameters();
            if (!empty($meeting_id)) {
                $params->setMeetingId($meeting_id);
            }
            $response = $bbb->getRecordings($params);
            if ($response->getReturnCode() == 'SUCCESS') {
                return $response->getRawXml();
            }
            return array();
        } catch (\Throwable $e) {
            log_message('error', 'BigBlueButton getRecordings error: ' . $e->getMessage());
            return array();
        }
    }

    public function deleteRecordings($recording_id)
    {
        try {
            $this->putEnv();
            $bbb = new BigBlueButton();
            $params = new DeleteRecordingsParameters($recording_id);
            $response = $bbb->deleteRecordings($params);
            return ($response->getReturnCode() == 'SUCCESS');
        } catch (\Throwable $e) {
            log_message('error', 'BigBlueButton deleteRecordings error: ' . $e->getMessage());
            return false;
        }
    }
}

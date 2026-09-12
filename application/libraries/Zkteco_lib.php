<?php
defined('BASEPATH') or exit('No direct script access allowed');

/**
 * ZKTeco Biometric Device Communication Library (PHP UDP Protocol)
 * Compatible with all ZKTeco standalone fingerprint and facial recognition terminals
 */
class Zkteco_lib
{
    const CMD_CONNECT = 1000;
    const CMD_EXIT = 1001;
    const CMD_ENABLEDEVICE = 1002;
    const CMD_DISABLEDEVICE = 1003;
    const CMD_RESTART = 1004;
    const CMD_POWEROFF = 1005;
    const CMD_ACK_OK = 2000;
    const CMD_ACK_ERROR = 2001;
    const CMD_ACK_DATA = 2002;
    const CMD_PREPARE_DATA = 1500;
    const CMD_DATA = 1501;
    const CMD_USERTEMP_RRQ = 9;
    const CMD_ATTLOG_RRQ = 13;
    const CMD_CLEAR_DATA = 14;
    const CMD_CLEAR_ATTLOG = 15;
    const CMD_GET_TIME = 201;
    const CMD_SET_TIME = 202;
    const CMD_VERSION = 1100;
    const CMD_DEVICE = 11;

    public $ip = '';
    public $port = 4370;
    public $socket = null;
    public $session_id = 0;
    public $protocol = 'UDP';
    public $error = '';

    public function __construct($params = [])
    {
        if (!empty($params['ip'])) {
            $this->ip = $params['ip'];
        }
        if (!empty($params['port'])) {
            $this->port = (int)$params['port'];
        }
        if (!empty($params['protocol'])) {
            $this->protocol = strtoupper($params['protocol']);
        }
    }

    public function init($ip, $port = 4370, $protocol = 'UDP')
    {
        $this->ip = trim($ip);
        $this->port = (int)$port;
        $this->protocol = strtoupper($protocol);
        $this->session_id = 0;
        $this->error = '';
        if ($this->socket) {
            @socket_close($this->socket);
            $this->socket = null;
        }
    }

    public function connect()
    {
        $this->socket = @socket_create(AF_INET, SOCK_DGRAM, SOL_UDP);
        if (!$this->socket) {
            $this->error = "Cannot create socket: " . socket_strerror(socket_last_error());
            return false;
        }

        socket_set_option($this->socket, SOL_SOCKET, SO_RCVTIMEO, ['sec' => 3, 'usec' => 500000]);
        socket_set_option($this->socket, SOL_SOCKET, SO_SNDTIMEO, ['sec' => 3, 'usec' => 500000]);

        $command = self::CMD_CONNECT;
        $command_string = '';
        $chksum = 0;
        $session_id = 0;
        $reply_id = -1 + 1;

        $buf = $this->createHeader($command, $chksum, $session_id, $reply_id, $command_string);
        @socket_sendto($this->socket, $buf, strlen($buf), 0, $this->ip, $this->port);

        $response = '';
        $from = '';
        $port = 0;
        $bytes = @socket_recvfrom($this->socket, $response, 1024, 0, $from, $port);

        if ($bytes > 0 && strlen($response) >= 8) {
            $u = unpack('H2h1/H2h2/H2h3/H2h4/H2h5/H2h6/H2h7/H2h8', substr($response, 0, 8));
            $this->session_id = hexdec($u['h6'] . $u['h5']);
            return true;
        }

        $this->error = "Connection timeout or device unreachable at {$this->ip}:{$this->port}";
        return false;
    }

    public function disconnect()
    {
        if ($this->socket && $this->session_id) {
            $command = self::CMD_EXIT;
            $command_string = '';
            $chksum = 0;
            $session_id = $this->session_id;
            $reply_id = -1 + 1;

            $buf = $this->createHeader($command, $chksum, $session_id, $reply_id, $command_string);
            @socket_sendto($this->socket, $buf, strlen($buf), 0, $this->ip, $this->port);
        }
        if ($this->socket) {
            @socket_close($this->socket);
            $this->socket = null;
        }
        $this->session_id = 0;
        return true;
    }

    public function version()
    {
        $command = self::CMD_VERSION;
        return trim($this->execCommand($command, ''));
    }

    public function serialNumber()
    {
        $command = self::CMD_DEVICE;
        $res = $this->execCommand($command, '~SerialNumber');
        $parts = explode('=', $res);
        return isset($parts[1]) ? trim($parts[1]) : trim($res);
    }

    public function deviceName()
    {
        $command = self::CMD_DEVICE;
        $res = $this->execCommand($command, '~DeviceName');
        $parts = explode('=', $res);
        return isset($parts[1]) ? trim($parts[1]) : trim($res);
    }

    public function getDeviceInfo()
    {
        if (!$this->session_id) {
            if (!$this->connect()) {
                return false;
            }
        }
        return [
            'name' => $this->deviceName() ?: 'ZKTeco Device',
            'version' => $this->version(),
            'serial' => $this->serialNumber(),
            'status' => 'Online',
            'ip' => $this->ip,
            'port' => $this->port,
        ];
    }

    public function getAttendance()
    {
        if (!$this->session_id) {
            if (!$this->connect()) {
                return [];
            }
        }

        $command = self::CMD_ATTLOG_RRQ;
        $data = $this->execCommandData($command, '');
        $records = [];

        if (!empty($data)) {
            // Check record size (typically 40 bytes on newer devices, or 14 bytes on older)
            $len = strlen($data);
            if ($len % 40 == 0) {
                $record_len = 40;
            } elseif ($len % 14 == 0) {
                $record_len = 14;
            } else {
                $record_len = 40;
            }

            for ($offset = 0; $offset < $len; $offset += $record_len) {
                $record = substr($data, $offset, $record_len);
                if (strlen($record) == 40) {
                    $u = unpack('vuid/a9id/Cstate/a19timestamp/Cvtype', substr($record, 0, 36));
                    $uid = $u['uid'];
                    $id = trim($u['id']);
                    $state = $u['state'];
                    $timestamp = trim($u['timestamp']);
                    if (!empty($id) && !empty($timestamp)) {
                        $records[] = [
                            'uid' => $uid,
                            'id' => $id,
                            'state' => $state,
                            'timestamp' => $timestamp,
                            'type' => $u['vtype'] ?? 0
                        ];
                    }
                } elseif (strlen($record) == 14) {
                    $u = unpack('vuid/Cstate/Vtimestamp/Cvtype', $record);
                    $records[] = [
                        'uid' => $u['uid'],
                        'id' => (string)$u['uid'],
                        'state' => $u['state'],
                        'timestamp' => $this->decodeTime($u['timestamp']),
                        'type' => $u['vtype'] ?? 0
                    ];
                }
            }
        }

        return $records;
    }

    public function clearAttendance()
    {
        if (!$this->session_id) {
            if (!$this->connect()) {
                return false;
            }
        }
        $res = $this->execCommand(self::CMD_CLEAR_ATTLOG, '');
        return $res !== false;
    }

    public function getUser()
    {
        if (!$this->session_id) {
            if (!$this->connect()) {
                return [];
            }
        }
        $command = self::CMD_USERTEMP_RRQ;
        $data = $this->execCommandData($command, chr(5));
        $users = [];

        if (!empty($data)) {
            $len = strlen($data);
            $record_len = 72;
            if ($len % $record_len == 0) {
                for ($offset = 0; $offset < $len; $offset += $record_len) {
                    $record = substr($data, $offset, $record_len);
                    $u = unpack('vuid/Crole/a8password/a24name/Ccardno/Cplaceholder/a9userid', substr($record, 0, 50));
                    $users[] = [
                        'uid' => $u['uid'],
                        'role' => $u['role'],
                        'name' => trim($u['name']),
                        'userid' => trim($u['userid'] ?: (string)$u['uid']),
                    ];
                }
            }
        }
        return $users;
    }

    private function createHeader($command, $chksum, $session_id, $reply_id, $command_string)
    {
        $buf = pack('ssss', $command, $chksum, $session_id, $reply_id) . $command_string;
        $len = strlen($buf);
        $chksum = $this->checksum($buf);
        return pack('ssss', $command, $chksum, $session_id, $reply_id) . $command_string;
    }

    private function checksum($p)
    {
        $l = strlen($p);
        $chksum = 0;
        $i = 0;
        while ($l > 1) {
            $u = unpack('v', substr($p, $i, 2));
            $chksum += $u[1];
            $i += 2;
            $l -= 2;
        }
        if ($l) {
            $chksum += ord($p[$i]);
        }
        while ($chksum >> 16) {
            $chksum = ($chksum & 0xFFFF) + ($chksum >> 16);
        }
        return ~$chksum & 0xFFFF;
    }

    private function execCommand($command, $command_string = '')
    {
        if (!$this->socket || !$this->session_id) {
            return false;
        }

        $chksum = 0;
        $session_id = $this->session_id;
        $reply_id = 0;

        $buf = $this->createHeader($command, $chksum, $session_id, $reply_id, $command_string);
        @socket_sendto($this->socket, $buf, strlen($buf), 0, $this->ip, $this->port);

        $response = '';
        $from = '';
        $port = 0;
        $bytes = @socket_recvfrom($this->socket, $response, 1024, 0, $from, $port);

        if ($bytes > 8) {
            return substr($response, 8);
        }
        return false;
    }

    private function execCommandData($command, $command_string = '')
    {
        if (!$this->socket || !$this->session_id) {
            return '';
        }

        $chksum = 0;
        $session_id = $this->session_id;
        $reply_id = 0;

        $buf = $this->createHeader($command, $chksum, $session_id, $reply_id, $command_string);
        @socket_sendto($this->socket, $buf, strlen($buf), 0, $this->ip, $this->port);

        $response = '';
        $from = '';
        $port = 0;
        $bytes = @socket_recvfrom($this->socket, $response, 1024, 0, $from, $port);

        if ($bytes < 8) {
            return '';
        }

        $u = unpack('vcmd/vchksum/vsession/vreply', substr($response, 0, 8));
        if ($u['cmd'] == self::CMD_PREPARE_DATA) {
            $bytes_expected = unpack('Vsize', substr($response, 8, 4))[1];
            $bytes_received = 0;
            $data = '';

            while ($bytes_received < $bytes_expected) {
                $chunk = '';
                $chunk_bytes = @socket_recvfrom($this->socket, $chunk, 65535, 0, $from, $port);
                if ($chunk_bytes > 8) {
                    $chunk_data = substr($chunk, 8);
                    $data .= $chunk_data;
                    $bytes_received += strlen($chunk_data);
                } else {
                    break;
                }
            }
            return $data;
        } elseif ($u['cmd'] == self::CMD_ACK_OK) {
            return substr($response, 8);
        }

        return '';
    }

    private function decodeTime($t)
    {
        $second = $t % 60;
        $t = intval($t / 60);
        $minute = $t % 60;
        $t = intval($t / 60);
        $hour = $t % 24;
        $t = intval($t / 24);
        $day = $t % 31 + 1;
        $t = intval($t / 31);
        $month = $t % 12 + 1;
        $t = intval($t / 12);
        $year = $t + 2000;
        return sprintf('%04d-%02d-%02d %02d:%02d:%02d', $year, $month, $day, $hour, $minute, $second);
    }
}

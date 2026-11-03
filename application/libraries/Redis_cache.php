<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Redis Cache Class (Radius Cache)
 * 
 * Provides high-performance Redis caching with:
 * 1. Native phpredis extension support
 * 2. Pure-PHP RESP socket client support (works even without php_redis extension)
 * 3. Graceful automated file-based fallback if Redis server is unreachable
 */
class Redis_cache
{
    /**
     * @var object|resource|null
     */
    protected $client = null;

    /**
     * @var string Driver in use ('phpredis', 'socket', 'file_fallback')
     */
    protected $driver = 'file_fallback';

    /**
     * @var bool Connection status
     */
    protected $connected = false;

    /**
     * @var array Configuration
     */
    protected $config = [];

    /**
     * @var string Key prefix
     */
    protected $prefix = 'dvs_';

    /**
     * @var string Fallback directory
     */
    protected $fallback_dir = '';

    public function __construct($config = [])
    {
        // Load redis config
        if (empty($config) && function_exists('get_instance')) {
            $CI =& get_instance();
            if ($CI && isset($CI->config) && $CI->config->load('redis', TRUE, TRUE)) {
                $config = $CI->config->item('redis');
            }
        }

        $this->config = array_merge([
            'host'     => '127.0.0.1',
            'port'     => 6379,
            'password' => null,
            'timeout'  => 1.5,
            'prefix'   => 'dvs_',
            'database' => 0,
        ], $config ?: []);

        $this->prefix = $this->config['prefix'];
        $this->fallback_dir = APPPATH . 'cache/redis_fallback/';

        $this->initialize();
    }

    /**
     * Attempt Redis connection (native or socket)
     */
    protected function initialize()
    {
        // 1. Try phpredis C-extension
        if (extension_loaded('redis') && class_exists('Redis')) {
            try {
                $redis = new \Redis();
                $ok = @$redis->connect($this->config['host'], (int)$this->config['port'], (float)$this->config['timeout']);
                if ($ok) {
                    if (!empty($this->config['password'])) {
                        $redis->auth($this->config['password']);
                    }
                    if (!empty($this->config['database'])) {
                        $redis->select((int)$this->config['database']);
                    }
                    $this->client = $redis;
                    $this->driver = 'phpredis';
                    $this->connected = true;
                    return;
                }
            } catch (\Exception $e) {
                // fall through to socket or file
            }
        }

        // 2. Try pure-PHP socket connection (RESP protocol)
        try {
            $errno = 0;
            $errstr = '';
            $socket = @stream_socket_client(
                "tcp://{$this->config['host']}:{$this->config['port']}",
                $errno,
                $errstr,
                (float)$this->config['timeout'],
                STREAM_CLIENT_CONNECT
            );

            if ($socket) {
                stream_set_timeout($socket, (int)$this->config['timeout']);

                // If password exists, send AUTH
                if (!empty($this->config['password'])) {
                    $authCmd = "*2\r\n$4\r\nAUTH\r\n$" . strlen($this->config['password']) . "\r\n" . $this->config['password'] . "\r\n";
                    fwrite($socket, $authCmd);
                    $resp = fgets($socket);
                    if (strpos($resp, '+OK') === false) {
                        fclose($socket);
                        $socket = null;
                    }
                }

                if ($socket) {
                    // Test with PING
                    fwrite($socket, "*1\r\n$4\r\nPING\r\n");
                    $pingResp = fgets($socket);
                    if (strpos($pingResp, '+PONG') !== false) {
                        if (!empty($this->config['database'])) {
                            $db = (int)$this->config['database'];
                            fwrite($socket, "*2\r\n$6\r\nSELECT\r\n$" . strlen((string)$db) . "\r\n{$db}\r\n");
                            fgets($socket);
                        }
                        $this->client = $socket;
                        $this->driver = 'socket';
                        $this->connected = true;
                        return;
                    }
                    fclose($socket);
                }
            }
        } catch (\Exception $e) {
            // fall through to file
        }

        // 3. Graceful File-based Fallback
        $this->driver = 'file_fallback';
        $this->connected = false;
        if (!is_dir($this->fallback_dir)) {
            @mkdir($this->fallback_dir, 0755, true);
        }
    }

    /**
     * Get value from cache
     *
     * @param string $key
     * @param mixed $default
     * @return mixed
     */
    public function get($key, $default = null)
    {
        $realKey = $this->prefix . $key;

        // phpredis
        if ($this->driver === 'phpredis' && $this->client) {
            try {
                $val = $this->client->get($realKey);
                if ($val === false) {
                    return $default;
                }
                return @unserialize($val) ?: $val;
            } catch (\Exception $e) {
                return $default;
            }
        }

        // socket
        if ($this->driver === 'socket' && $this->client) {
            try {
                $cmd = "*2\r\n$3\r\nGET\r\n$" . strlen($realKey) . "\r\n" . $realKey . "\r\n";
                fwrite($this->client, $cmd);
                $line = fgets($this->client);
                if ($line && $line[0] === '$') {
                    $len = (int)substr($line, 1);
                    if ($len === -1) {
                        return $default;
                    }
                    $data = stream_get_contents($this->client, $len);
                    fgets($this->client); // consume trailing CRLF
                    return @unserialize($data) ?: $data;
                }
                return $default;
            } catch (\Exception $e) {
                return $default;
            }
        }

        // file fallback
        $file = $this->fallback_dir . md5($realKey) . '.cache';
        if (file_exists($file)) {
            $content = @file_get_contents($file);
            if ($content !== false) {
                $data = @unserialize($content);
                if (is_array($data) && isset($data['expire'], $data['value'])) {
                    if ($data['expire'] === 0 || $data['expire'] >= time()) {
                        return $data['value'];
                    }
                    @unlink($file);
                }
            }
        }

        return $default;
    }

    /**
     * Set value in cache
     *
     * @param string $key
     * @param mixed $value
     * @param int $ttl Time to live in seconds (default: 3600)
     * @return bool
     */
    public function set($key, $value, $ttl = 3600)
    {
        $realKey = $this->prefix . $key;
        $serialized = is_scalar($value) && !is_bool($value) ? (string)$value : serialize($value);
        $ttl = (int)$ttl;

        // phpredis
        if ($this->driver === 'phpredis' && $this->client) {
            try {
                if ($ttl > 0) {
                    return $this->client->setex($realKey, $ttl, $serialized);
                }
                return $this->client->set($realKey, $serialized);
            } catch (\Exception $e) {
                // fall through
            }
        }

        // socket
        if ($this->driver === 'socket' && $this->client) {
            try {
                if ($ttl > 0) {
                    $cmd = "*5\r\n$3\r\nSET\r\n$" . strlen($realKey) . "\r\n" . $realKey . "\r\n$" . strlen($serialized) . "\r\n" . $serialized . "\r\n$2\r\nEX\r\n$" . strlen((string)$ttl) . "\r\n" . $ttl . "\r\n";
                } else {
                    $cmd = "*3\r\n$3\r\nSET\r\n$" . strlen($realKey) . "\r\n" . $realKey . "\r\n$" . strlen($serialized) . "\r\n" . $serialized . "\r\n";
                }
                fwrite($this->client, $cmd);
                $resp = fgets($this->client);
                return strpos($resp, '+OK') !== false;
            } catch (\Exception $e) {
                // fall through
            }
        }

        // file fallback
        if (!is_dir($this->fallback_dir)) {
            @mkdir($this->fallback_dir, 0755, true);
        }
        $file = $this->fallback_dir . md5($realKey) . '.cache';
        $data = [
            'expire' => $ttl > 0 ? (time() + $ttl) : 0,
            'value'  => $value,
        ];
        return @file_put_contents($file, serialize($data)) !== false;
    }

    /**
     * Delete key from cache
     *
     * @param string $key
     * @return bool
     */
    public function delete($key)
    {
        $realKey = $this->prefix . $key;

        if ($this->driver === 'phpredis' && $this->client) {
            try {
                return $this->client->del($realKey) > 0;
            } catch (\Exception $e) {
                return false;
            }
        }

        if ($this->driver === 'socket' && $this->client) {
            try {
                $cmd = "*2\r\n$3\r\nDEL\r\n$" . strlen($realKey) . "\r\n" . $realKey . "\r\n";
                fwrite($this->client, $cmd);
                $resp = fgets($this->client);
                return (int)substr(trim($resp), 1) > 0;
            } catch (\Exception $e) {
                return false;
            }
        }

        $file = $this->fallback_dir . md5($realKey) . '.cache';
        if (file_exists($file)) {
            return @unlink($file);
        }

        return false;
    }

    /**
     * Check if key exists
     *
     * @param string $key
     * @return bool
     */
    public function has($key)
    {
        return $this->get($key) !== null;
    }

    /**
     * Remember / Get or Store
     *
     * @param string $key
     * @param int $ttl
     * @param callable $callback
     * @return mixed
     */
    public function remember($key, $ttl, $callback)
    {
        $cached = $this->get($key);
        if ($cached !== null) {
            return $cached;
        }

        $value = call_user_func($callback);
        $this->set($key, $value, $ttl);
        return $value;
    }

    /**
     * Clear all cache
     *
     * @return bool
     */
    public function flush()
    {
        if ($this->driver === 'phpredis' && $this->client) {
            try {
                return $this->client->flushDB();
            } catch (\Exception $e) {
                return false;
            }
        }

        if ($this->driver === 'socket' && $this->client) {
            try {
                fwrite($this->client, "*1\r\n$7\r\nFLUSHDB\r\n");
                $resp = fgets($this->client);
                return strpos($resp, '+OK') !== false;
            } catch (\Exception $e) {
                return false;
            }
        }

        if (is_dir($this->fallback_dir)) {
            $files = glob($this->fallback_dir . '*.cache');
            if ($files) {
                foreach ($files as $f) {
                    @unlink($f);
                }
            }
        }

        return true;
    }

    /**
     * Status info
     *
     * @return array
     */
    public function status()
    {
        return [
            'connected' => $this->connected,
            'driver'    => $this->driver,
            'host'      => $this->config['host'],
            'port'      => $this->config['port'],
            'prefix'    => $this->prefix,
        ];
    }

    /**
     * Check if connected to real Redis server
     *
     * @return bool
     */
    public function is_connected()
    {
        return $this->connected;
    }

    /**
     * Get driver name
     *
     * @return string
     */
    public function get_driver()
    {
        return $this->driver;
    }
}

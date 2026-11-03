<?php
/**
 * Dotenv Environment Configuration Loader
 * 
 * Lightweight, zero-dependency .env loader for CodeIgniter 3
 */
defined('BASEPATH') OR defined('FCPATH') OR define('DOTENV_DIRECT', true);

class Dotenv
{
    /**
     * Loaded variables cache
     *
     * @var array
     */
    protected static $loaded = [];

    /**
     * Load environment file
     *
     * @param string $path
     * @param bool $override
     * @return bool
     */
    public static function load($path, $override = false)
    {
        if (!file_exists($path) || !is_readable($path)) {
            return false;
        }

        $lines = file($path, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
        if ($lines === false) {
            return false;
        }

        foreach ($lines as $line) {
            $line = trim($line);

            // Skip empty lines and comments
            if ($line === '' || $line[0] === '#' || strpos($line, '=') === false) {
                continue;
            }

            list($name, $value) = explode('=', $line, 2);
            $name = trim($name);
            $value = trim($value);

            if ($name === '') {
                continue;
            }

            // Remove surrounding quotes if present
            $len = strlen($value);
            if ($len >= 2) {
                $first = $value[0];
                $last = $value[$len - 1];
                if (($first === '"' && $last === '"') || ($first === "'" && $last === "'")) {
                    $value = substr($value, 1, -1);
                }
            }

            // Handle inline escaped quotes
            $value = str_replace(['\\"', "\\'"], ['"', "'"], $value);

            // Only set if not already present or if override is true
            if ($override || (!array_key_exists($name, $_SERVER) && !array_key_exists($name, $_ENV))) {
                putenv("{$name}={$value}");
                $_ENV[$name] = $value;
                $_SERVER[$name] = $value;
                self::$loaded[$name] = $value;
            }
        }

        return true;
    }

    /**
     * Get all loaded variables
     *
     * @return array
     */
    public static function all()
    {
        return self::$loaded;
    }
}

if (!function_exists('env')) {
    /**
     * Get environment variable with type casting and default value
     *
     * @param string $key
     * @param mixed $default
     * @return mixed
     */
    function env($key, $default = null)
    {
        $value = getenv($key);

        if ($value === false) {
            if (isset($_ENV[$key])) {
                $value = $_ENV[$key];
            } elseif (isset($_SERVER[$key])) {
                $value = $_SERVER[$key];
            } else {
                return $default;
            }
        }

        if ($value === null || $value === '') {
            return ($default !== null && $value === '') ? '' : $default;
        }

        // Parse boolean / null types
        $lower = strtolower(trim($value));
        switch ($lower) {
            case 'true':
            case '(true)':
                return true;
            case 'false':
            case '(false)':
                return false;
            case 'empty':
            case '(empty)':
                return '';
            case 'null':
            case '(null)':
                return null;
        }

        return $value;
    }
}

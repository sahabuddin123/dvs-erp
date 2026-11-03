<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/*
|--------------------------------------------------------------------------
| Redis Configuration (radius cache)
|--------------------------------------------------------------------------
| Configuration loaded from .env with fallback defaults
*/

if (!function_exists('env')) {
	if (file_exists(__DIR__ . '/../libraries/Dotenv.php')) {
		require_once __DIR__ . '/../libraries/Dotenv.php';
		if (file_exists(__DIR__ . '/../../.env')) {
			Dotenv::load(__DIR__ . '/../../.env');
		}
	}
}

$config['socket_type'] = function_exists('env') ? env('REDIS_SOCKET_TYPE', 'tcp') : 'tcp';
$config['host']        = function_exists('env') ? env('REDIS_HOST', '127.0.0.1') : '127.0.0.1';
$config['password']    = function_exists('env') ? env('REDIS_PASSWORD', NULL) : NULL;
$config['port']        = function_exists('env') ? (int)env('REDIS_PORT', 6379) : 6379;
$config['timeout']     = function_exists('env') ? (float)env('REDIS_TIMEOUT', 1.5) : 1.5;
$config['prefix']      = function_exists('env') ? env('REDIS_PREFIX', 'dvs_') : 'dvs_';
$config['database']    = function_exists('env') ? (int)env('REDIS_DATABASE', 0) : 0;


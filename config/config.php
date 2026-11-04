<?php
/**
 * JayArt E-Commerce Platform
 * Global Configuration Settings (cPanel Ready)
 */

declare(strict_types=1);

// Set default timezone (adjust as needed in admin settings)
date_default_timezone_set('Asia/Kolkata');

// Directory Separator & Root Paths
if (!defined('ROOT_PATH')) define('ROOT_PATH', dirname(__DIR__));
if (!defined('CONFIG_PATH')) define('CONFIG_PATH', ROOT_PATH . DIRECTORY_SEPARATOR . 'config');
if (!defined('CORE_PATH')) define('CORE_PATH', ROOT_PATH . DIRECTORY_SEPARATOR . 'core');
if (!defined('APP_PATH')) define('APP_PATH', ROOT_PATH . DIRECTORY_SEPARATOR . 'app');
if (!defined('ADMIN_PATH')) define('ADMIN_PATH', ROOT_PATH . DIRECTORY_SEPARATOR . 'admin');
if (!defined('UPLOADS_PATH')) define('UPLOADS_PATH', ROOT_PATH . DIRECTORY_SEPARATOR . 'uploads');
if (!defined('ASSETS_PATH')) define('ASSETS_PATH', ROOT_PATH . DIRECTORY_SEPARATOR . 'assets');
if (!defined('STORAGE_PATH')) define('STORAGE_PATH', ROOT_PATH . DIRECTORY_SEPARATOR . 'storage');
if (!defined('CACHE_PATH')) define('CACHE_PATH', STORAGE_PATH . DIRECTORY_SEPARATOR . 'cache');

// Load .env support if not already initialized
if (!class_exists('Env') && file_exists(CORE_PATH . '/Env.php')) {
    require_once CORE_PATH . '/Env.php';
    if (file_exists(ROOT_PATH . '/.env')) {
        Env::load(ROOT_PATH . '/.env');
    }
}

// Error reporting: Show errors in development, hide on live production
if (!defined('APP_ENV')) {
    define('APP_ENV', env('APP_ENV', 'production'));
}

$appDebug = (bool)env('APP_DEBUG', APP_ENV === 'development');

if ($appDebug) {
    ini_set('display_errors', '1');
    ini_set('display_startup_errors', '1');
    error_reporting(E_ALL);
} else {
    ini_set('display_errors', '0');
    error_reporting(0);
}

// Detect Site Base URL (Supports .env APP_URL override, localhost, subfolders, and cPanel domains)
$envAppUrl = env('APP_URL');
if (!empty($envAppUrl)) {
    define('SITE_URL', rtrim((string)$envAppUrl, '/'));
} else {
    $protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') || (isset($_SERVER['SERVER_PORT']) && $_SERVER['SERVER_PORT'] == 443) ? 'https://' : 'http://';
    $host = $_SERVER['HTTP_HOST'] ?? 'localhost';
    $rawScript = $_SERVER['SCRIPT_NAME'] ?? '';
    $scriptDir = !empty($rawScript) ? dirname($rawScript) : '';
    $basePath = str_replace(['/admin', '\\admin'], '', $scriptDir);
    $basePath = rtrim(str_replace('\\', '/', $basePath), '/');
    if ($basePath === '.' || $basePath === '/' || $basePath === '\\') {
        $basePath = '';
    }
    define('SITE_URL', rtrim($protocol . $host . $basePath, '/'));
}
define('ADMIN_URL', SITE_URL . '/admin');
define('ASSETS_URL', SITE_URL . '/assets');
define('UPLOADS_URL', SITE_URL . '/uploads');

// Store Branding & General Defaults
define('APP_NAME', 'JayArt');
define('APP_TAGLINE', 'Original Paintings & Fine Art Gallery');
define('APP_VERSION', '1.0.0');

// Currency Defaults
define('CURRENCY_SYMBOL', '₹');
define('CURRENCY_CODE', 'INR');

// Session Settings
if (session_status() === PHP_SESSION_NONE) {
    session_start([
        'cookie_httponly' => true,
        'cookie_samesite' => 'Lax',
        'use_strict_mode' => true,
    ]);
}


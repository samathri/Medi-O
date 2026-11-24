<?php
/**
 * Medi-O â€” global configuration.
 * Secrets live in config.local.php (never committed). Copy
 * config.local.example.php to config.local.php and fill in what you have;
 * every integration degrades gracefully when left blank.
 */

date_default_timezone_set('Asia/Colombo');

$defaults = [
    'DB_HOST' => '127.0.0.1',
    'DB_PORT' => 3306,
    // Hosted MySQL (e.g. Aiven) requires an encrypted connection: set DB_SSL to true.
    // Optionally paste the provider's CA certificate (PEM text) into DB_SSL_CA to verify the server.
    'DB_SSL' => false,
    'DB_SSL_CA' => '',
    'DB_NAME' => 'medio',
    'DB_USER' => 'root',
    'DB_PASS' => '',

    // Gmail SMTP (App Password) â€” leave blank to log emails instead of sending
    'SMTP_HOST' => 'smtp.gmail.com',
    'SMTP_PORT' => 587,
    'SMTP_USER' => '',
    'SMTP_PASS' => '',
    'MAIL_FROM' => 'no-reply@medi-o.local',
    'MAIL_FROM_NAME' => 'Medi-O Pharmacy',

    // Google Sign-In (https://console.cloud.google.com â†’ OAuth client ID, type Web)
    'GOOGLE_CLIENT_ID' => '',

    // PayHere sandbox (free: https://sandbox.payhere.lk) â€” blank = built-in demo gateway
    'PAYHERE_MERCHANT_ID' => '',
    'PAYHERE_MERCHANT_SECRET' => '',
    'PAYHERE_SANDBOX' => true,
    // Google Gemini API key (free: https://aistudio.google.com/apikey)
    // Powers the AI prescription reader. Blank = pharmacist enters items manually.
    'GEMINI_API_KEY' => '',
    'GEMINI_MODEL' => 'gemini-flash-latest',

    'APP_DEBUG' => false, // set true in config.local.php only while developing
    // Testing only: lets "Pay online" use a pretend gateway when PayHere keys are missing.
    // NEVER enable on a live site — orders would be marked paid without any money.
    'ALLOW_DEMO_PAYMENTS' => false,
    // Public app URL used by CLI reminder links; set for non-default folders/ports.
    'APP_URL' => 'http://localhost/medio',

    // Read-only hosts (Vercel): keep uploads and log-in sessions in MySQL instead of on disk.
    'STORAGE_DRIVER' => 'disk',   // 'disk' or 'db'
    'SESSION_DRIVER' => 'files',  // 'files' or 'db'
    // Secret for the web-triggered reminder job (cron-run.php). Blank = disabled.
    'CRON_SECRET' => '',
];

$local = [];
if (is_file(__DIR__ . '/config.local.php')) {
    $local = require __DIR__ . '/config.local.php';
}
foreach ($defaults as $k => $v) {
    if (!defined($k)) {
        // Order: config.local.php → environment variable (Vercel, Docker …) → default
        $val = $local[$k] ?? null;
        if ($val === null || $val === '') {
            $env = getenv($k);
            if ($env !== false && $env !== '') {
                $val = is_bool($v) ? in_array(strtolower($env), ['1', 'true', 'yes', 'on'], true)
                     : (is_int($v) ? (int)$env : $env);
            }
        }
        define($k, $val === null || $val === '' ? $v : $val); // blank values fall back to defaults
    }
}

// Auto-detect base URL so the app works from any folder (e.g. /medio/)
$scheme = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? 'https' : 'http';
if (($_SERVER['HTTP_X_FORWARDED_PROTO'] ?? '') === 'https') $scheme = 'https'; // behind a proxy (Vercel)
if (!empty($_SERVER['HTTP_X_FORWARDED_HOST'])) $_SERVER['HTTP_HOST'] = explode(',', $_SERVER['HTTP_X_FORWARDED_HOST'])[0];
$host   = $_SERVER['HTTP_HOST'] ?? 'localhost';
$base   = rtrim(str_replace('\\', '/', dirname($_SERVER['SCRIPT_NAME'] ?? '/')), '/');
// Normalise when running from /api, /auth, /staff, /admin subfolders
foreach (['/api', '/auth', '/staff', '/admin', '/cron'] as $sub) {
    if (str_ends_with($base, $sub)) {
        $base = substr($base, 0, -strlen($sub));
    }
}
define('BASE_URL', PHP_SAPI === 'cli' ? rtrim(APP_URL, '/') : $scheme . '://' . $host . $base);
define('ROOT_PATH', __DIR__);

if (APP_DEBUG) {
    error_reporting(E_ALL);
    ini_set('display_errors', '1');
} else {
    error_reporting(E_ALL & ~E_DEPRECATED);
    ini_set('display_errors', '0');
    ini_set('log_errors', '1');
    if (is_dir(__DIR__ . '/logs') && is_writable(__DIR__ . '/logs')) ini_set('error_log', __DIR__ . '/logs/php-error.log');
}

require_once __DIR__ . '/vendor/autoload.php';
require_once __DIR__ . '/includes/db.php';
require_once __DIR__ . '/includes/helpers.php';
require_once __DIR__ . '/includes/auth.php';

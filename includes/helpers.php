<?php
/** Shared helpers: escaping, CSRF, flash messages, formatting, notifications. */

if (session_status() === PHP_SESSION_NONE && PHP_SAPI !== 'cli') {
    session_set_cookie_params(['httponly' => true, 'samesite' => 'Lax']);
    session_start();
}

function e(?string $s): string
{
    return htmlspecialchars($s ?? '', ENT_QUOTES, 'UTF-8');
}

function url(string $path = ''): string
{
    return BASE_URL . '/' . ltrim($path, '/');
}

function redirect(string $path): never
{
    if (preg_match('#^https?://#', $path)) {
        $target = $path;
    } elseif (str_starts_with($path, '/')) {
        // Absolute path (e.g. a stored REQUEST_URI) — already includes the app folder
        $origin = parse_url(BASE_URL, PHP_URL_SCHEME) . '://' . parse_url(BASE_URL, PHP_URL_HOST);
        if (($port = parse_url(BASE_URL, PHP_URL_PORT))) $origin .= ':' . $port;
        $target = $origin . $path;
    } else {
        $target = url($path);
    }
    header('Location: ' . $target);
    exit;
}

/* ---------- CSRF ---------- */
function csrf_token(): string
{
    if (empty($_SESSION['csrf'])) {
        $_SESSION['csrf'] = bin2hex(random_bytes(20));
    }
    return $_SESSION['csrf'];
}

function csrf_field(): string
{
    return '<input type="hidden" name="csrf" value="' . csrf_token() . '">';
}

function csrf_check(): void
{
    $sent = $_POST['csrf'] ?? $_SERVER['HTTP_X_CSRF'] ?? '';
    if (!hash_equals(csrf_token(), $sent)) {
        http_response_code(419);
        exit('Invalid or expired form token. Please go back and try again.');
    }
}

/* ---------- Flash messages ---------- */
function flash(string $type, string $message): void
{
    $_SESSION['flash'][] = ['type' => $type, 'message' => $message];
}

function flash_pull(): array
{
    $f = $_SESSION['flash'] ?? [];
    unset($_SESSION['flash']);
    return $f;
}

/* ---------- Formatting ---------- */
function money(float $amount): string
{
    return 'Rs. ' . number_format($amount, 2);
}

function time_ago(string $datetime): string
{
    $diff = time() - strtotime($datetime);
    if ($diff < 60) return 'just now';
    if ($diff < 3600) return floor($diff / 60) . ' min ago';
    if ($diff < 86400) return floor($diff / 3600) . ' h ago';
    if ($diff < 604800) return floor($diff / 86400) . ' d ago';
    return date('j M Y', strtotime($datetime));
}

function product_image(?string $path): string
{
    if ($path && is_file(ROOT_PATH . '/' . $path)) {
        return url($path);
    }
    return url('assets/img/placeholder-product.svg');
}

/* ---------- Settings ---------- */
function setting(string $name, string $default = ''): string
{
    static $cache = null;
    if ($cache === null) {
        $cache = [];
        foreach (q_all('SELECT name, value FROM settings') as $row) {
            $cache[$row['name']] = $row['value'];
        }
    }
    return $cache[$name] ?? $default;
}

/* ---------- In-app notifications ---------- */
function notify(int $userId, string $type, string $title, string $body = '', string $link = ''): void
{
    q('INSERT INTO notifications (user_id, type, title, body, link) VALUES (?,?,?,?,?)',
        [$userId, $type, $title, $body, $link]);
}

/* ---------- Tokens & order numbers ---------- */
function new_token(): string
{
    return bin2hex(random_bytes(20)); // 40 chars
}

function new_order_no(): string
{
    return 'MO' . date('ymd') . strtoupper(substr(bin2hex(random_bytes(3)), 0, 5));
}

/* ---------- JSON API responses ---------- */
function json_out(array $data, int $code = 200): never
{
    http_response_code($code);
    header('Content-Type: application/json');
    echo json_encode($data);
    exit;
}

/* ---------- Phone normalisation (Sri Lanka) ---------- */
function normalize_phone(string $phone): ?string
{
    $digits = preg_replace('/\D+/', '', $phone);
    if (preg_match('/^0(7\d{8})$/', $digits, $m)) return '94' . $m[1];   // 07XXXXXXXX
    if (preg_match('/^94(7\d{8})$/', $digits)) return $digits;           // 947XXXXXXXX
    if (preg_match('/^7\d{8}$/', $digits)) return '94' . $digits;        // 7XXXXXXXX
    if (preg_match('/^0\d{9}$/', $digits)) return '94' . substr($digits, 1); // landline
    return null;
}

/** Display format: 94771234567 → 077 123 4567 */
function pretty_phone(?string $phone): string
{
    if (!$phone) return '';
    if (preg_match('/^94(\d{2})(\d{3})(\d{4})$/', $phone, $m)) {
        return '0' . $m[1] . ' ' . $m[2] . ' ' . $m[3];
    }
    return $phone;
}

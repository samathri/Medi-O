<?php
/** Shared helpers: escaping, CSRF, flash messages, formatting, notifications. */

if (session_status() === PHP_SESSION_NONE && PHP_SAPI !== 'cli') {
    // Isolate this installation from other PHP apps sharing localhost cookies.
    session_name('MEDIOSESSID' . substr(hash('sha256', ROOT_PATH . BASE_URL), 0, 12));
    ini_set('session.use_strict_mode', '1');
    if (SESSION_DRIVER === 'db') {
        require_once __DIR__ . '/session-db.php';
        session_set_save_handler(new MedioDbSessionHandler(), true);
        ini_set('session.gc_probability', '1'); ini_set('session.gc_divisor', '200');
    }
    session_set_cookie_params([
        'path' => (parse_url(BASE_URL, PHP_URL_PATH) ?: '') . '/',
        'secure' => str_starts_with(BASE_URL, 'https://'),
        'httponly' => true, 'samesite' => 'Lax',
    ]);
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
        http_response_code(403);
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
    // Photos uploaded on a read-only host live in the database and are served by file.php
    if ($path && STORAGE_DRIVER === 'db' && str_starts_with($path, 'uploads/products/p_')) {
        return url('file.php?p=' . rawurlencode(basename($path)));
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

/** Customer-friendly wording for order / prescription statuses. */
function status_label(string $status): string
{
    return [
        'placed' => 'Order received', 'processing' => 'Being prepared', 'ready' => 'Ready to collect',
        'collected' => 'Collected', 'completed' => 'Completed', 'cancelled' => 'Cancelled',
        'pending' => 'Being checked', 'approved' => 'Approved', 'rejected' => 'Not approved',
        'paid' => 'Paid', 'failed' => 'Payment failed',
    ][$status] ?? ucfirst($status);
}

/**
 * Tell a user something important: on-site notification (web + app) and an email.
 * This is the only alert channel — no SMS/WhatsApp.
 */
function alert_user(int $userId, string $type, string $title, string $body, string $link = '', string $button = 'Open Medi-O'): void
{
    notify($userId, $type, $title, $body, $link);
    $email = q_val('SELECT email FROM users WHERE id=?', [$userId]);
    if ($email) {
        require_once __DIR__ . '/mailer.php';
        send_mail($email, $title, mail_template($title, '<p>' . nl2br(e($body)) . '</p>', $link !== '' ? $button : '', $link));
    }
}

/** True inside the Medi-O Android app (its web view adds "MediOApp" to the user agent). */
function in_app(): bool
{
    return str_contains($_SERVER['HTTP_USER_AGENT'] ?? '', 'MediOApp');
}

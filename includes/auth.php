<?php
/** Session-based authentication and role guards. */

function current_user(): ?array
{
    static $user = false;
    if ($user === false) {
        $user = null;
        if (!empty($_SESSION['user_id'])) {
            $user = q_one('SELECT * FROM users WHERE id = ?', [$_SESSION['user_id']]);
        }
    }
    return $user;
}

function is_logged_in(): bool
{
    return current_user() !== null;
}

function is_staff(): bool
{
    $u = current_user();
    return $u && in_array($u['role'], ['pharmacist', 'admin'], true);
}

function is_admin(): bool
{
    $u = current_user();
    return $u && $u['role'] === 'admin';
}

function login_user(array $user): void
{
    session_regenerate_id(true);
    $_SESSION['user_id'] = (int)$user['id'];
}

function logout_user(): void
{
    $_SESSION = [];
    session_destroy();
}

function require_login(): array
{
    $u = current_user();
    if (!$u) {
        $_SESSION['after_login'] = $_SERVER['REQUEST_URI'] ?? '';
        flash('info', 'Please log in to continue.');
        redirect('login.php');
    }
    return $u;
}

function require_staff(): array
{
    $u = require_login();
    if (!in_array($u['role'], ['pharmacist', 'admin'], true)) {
        http_response_code(403);
        exit('Access denied — staff only.');
    }
    return $u;
}

function require_admin(): array
{
    $u = require_login();
    if ($u['role'] !== 'admin') {
        http_response_code(403);
        exit('Access denied — admin only.');
    }
    return $u;
}

/** Post-login landing page by role. */
function role_home(array $user): string
{
    return match ($user['role']) {
        'admin' => 'admin/index.php',
        'pharmacist' => 'staff/index.php',
        default => 'profile.php',
    };
}

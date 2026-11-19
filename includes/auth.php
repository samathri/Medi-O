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

    // A guest tapped "Add to cart" before logging in — finish that for them.
    $pending = (int)($_SESSION['pending_add'] ?? 0);
    unset($_SESSION['pending_add']);
    if ($pending && $user['role'] === 'customer') {
        $p = q_one('SELECT id, name FROM products WHERE id=? AND is_active=1 AND stock > 0', [$pending]);
        if ($p && !q_one('SELECT id FROM cart_items WHERE user_id=? AND product_id=?', [$user['id'], $p['id']])) {
            q('INSERT INTO cart_items (user_id, product_id, quantity) VALUES (?,?,1)', [$user['id'], $p['id']]);
            flash('success', $p['name'] . ' was added to your cart.');
        }
    }
}

/** Remember what a guest was trying to do (?add=ID, ?next=/path) so we can continue after login/sign-up. */
function remember_intent(): void
{
    if (!empty($_GET['add'])) $_SESSION['pending_add'] = (int)$_GET['add'];
    $next = (string)($_GET['next'] ?? '');
    if ($next !== '' && str_starts_with($next, '/') && !str_starts_with($next, '//')) $_SESSION['after_login'] = $next;
}

/** Where to send someone after login/sign-up. */
function take_after_login(string $fallback): string
{
    $after = $_SESSION['after_login'] ?? '';
    unset($_SESSION['after_login']);
    return $after !== '' ? $after : $fallback;
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

<?php
/** In-app notification feed for the header bell. */
require_once __DIR__ . '/../config.php';

$u = current_user();
if (!$u) json_out(['ok' => false, 'error' => 'Login required.'], 401);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    csrf_check();
    $in = json_decode(file_get_contents('php://input'), true) ?: [];
    if (($in['action'] ?? '') === 'read_all') {
        q('UPDATE notifications SET is_read=1 WHERE user_id=?', [$u['id']]);
        json_out(['ok' => true]);
    }
    json_out(['ok' => false, 'error' => 'Unknown action.'], 400);
}

// Live polling: ?since=<last id> returns only newer alerts + the unread count (web + app badges)
if (isset($_GET['since'])) {
    $new = q_all('SELECT id, title, body, link FROM notifications WHERE user_id=? AND id > ? ORDER BY id LIMIT 10', [$u['id'], (int)$_GET['since']]);
    json_out(['ok' => true,
        'unread' => (int)q_val('SELECT COUNT(*) FROM notifications WHERE user_id=? AND is_read=0', [$u['id']]),
        'last' => (int)q_val('SELECT COALESCE(MAX(id),0) FROM notifications WHERE user_id=?', [$u['id']]),
        'items' => array_map(fn($n) => ['id' => (int)$n['id'], 'title' => $n['title'], 'body' => (string)$n['body'], 'link' => $n['link']], $new)]);
}

$items = q_all('SELECT id, title, body, link, is_read, created_at FROM notifications
                WHERE user_id=? ORDER BY id DESC LIMIT 15', [$u['id']]);
json_out(['ok' => true, 'items' => array_map(fn($n) => [
    'title' => e($n['title']),
    'body' => e($n['body'] ?? ''),
    'link' => $n['link'],
    'is_read' => (int)$n['is_read'],
    'ago' => time_ago($n['created_at']),
], $items)]);

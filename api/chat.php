<?php
/**
 * Chat API (AJAX polling).
 *  GET  ?after=<id>[&conversation=<id>]   → new messages
 *  POST {action:'send', body, [conversation_id]}
 * Customers always talk in their own conversation; staff pass conversation_id.
 */
require_once __DIR__ . '/../config.php';

$u = current_user();
if (!$u) json_out(['ok' => false, 'error' => 'Login required.'], 401);
$staffMode = is_staff();

/** Find (or create) the conversation this request refers to. */
function chat_conversation(array $u, bool $staffMode, int $requested = 0): ?array
{
    if ($staffMode) {
        return $requested ? q_one('SELECT * FROM chat_conversations WHERE id=?', [$requested]) : null;
    }
    $conv = q_one('SELECT * FROM chat_conversations WHERE user_id=?', [$u['id']]);
    if (!$conv) {
        q('INSERT INTO chat_conversations (user_id) VALUES (?)', [$u['id']]);
        $conv = q_one('SELECT * FROM chat_conversations WHERE user_id=?', [$u['id']]);
    }
    return $conv;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    csrf_check();
    $in = json_decode(file_get_contents('php://input'), true) ?: [];
    if (($in['action'] ?? '') !== 'send') json_out(['ok' => false, 'error' => 'Unknown action.'], 400);

    $body = trim($in['body'] ?? '');
    if ($body === '' || mb_strlen($body) > 2000) json_out(['ok' => false, 'error' => 'Message must be 1–2000 characters.']);

    $conv = chat_conversation($u, $staffMode, (int)($in['conversation_id'] ?? 0));
    if (!$conv) json_out(['ok' => false, 'error' => 'Conversation not found.'], 404);

    q('INSERT INTO chat_messages (conversation_id, sender_id, body) VALUES (?,?,?)', [$conv['id'], $u['id'], $body]);
    $msgId = (int)db()->lastInsertId();

    if ($staffMode) {
        q('UPDATE chat_conversations SET last_message_at=NOW(), user_unread=user_unread+1 WHERE id=?', [$conv['id']]);
        notify((int)$conv['user_id'], 'chat', 'New message from the pharmacy', mb_substr($body, 0, 80), url('chat.php'));
    } else {
        q('UPDATE chat_conversations SET last_message_at=NOW(), staff_unread=staff_unread+1 WHERE id=?', [$conv['id']]);
    }
    json_out(['ok' => true, 'id' => $msgId]);
}

/* ---- GET: poll for messages ---- */
$conv = chat_conversation($u, $staffMode, (int)($_GET['conversation'] ?? 0));
if (!$conv) json_out(['ok' => true, 'messages' => []]);

$after = (int)($_GET['after'] ?? 0);
$messages = q_all('SELECT m.id, m.sender_id, m.body, m.created_at, s.name AS sender_name, s.role AS sender_role
                   FROM chat_messages m JOIN users s ON s.id = m.sender_id
                   WHERE m.conversation_id=? AND m.id > ? ORDER BY m.id LIMIT 100', [$conv['id'], $after]);

// Mark as read for whoever is polling
if ($staffMode) {
    q('UPDATE chat_conversations SET staff_unread=0 WHERE id=?', [$conv['id']]);
} else {
    q('UPDATE chat_conversations SET user_unread=0 WHERE id=?', [$conv['id']]);
}

json_out(['ok' => true, 'conversation_id' => (int)$conv['id'], 'messages' => array_map(fn($m) => [
    'id' => (int)$m['id'],
    'mine' => $m['sender_id'] == $u['id'],
    'staff' => in_array($m['sender_role'], ['pharmacist', 'admin'], true),
    'name' => $m['sender_name'],
    'body' => $m['body'],
    'time' => date('g:ia', strtotime($m['created_at'])),
], $messages)]);

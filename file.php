<?php
/**
 * Serves stored files.
 *   file.php?rx=ID   private prescription file → its owner or staff only
 *   file.php?p=NAME  product photo uploaded by an admin (public, database storage only)
 */
require_once __DIR__ . '/config.php';
require_once __DIR__ . '/includes/storage.php';

function send_stored(?array $file, string $downloadName, bool $public): never
{
    if (!$file) {
        http_response_code(404);
        exit('File not found.');
    }
    header('Content-Type: ' . $file['mime']);
    header('Content-Length: ' . strlen($file['data']));
    header('Content-Disposition: inline; filename="' . rawurlencode($downloadName) . '"');
    header('X-Content-Type-Options: nosniff');
    header('Cache-Control: ' . ($public ? 'public, max-age=604800' : 'private, no-store'));
    echo $file['data'];
    exit;
}

if (isset($_GET['p'])) {
    $name = basename((string)$_GET['p']);
    if (!preg_match('/^p_[a-f0-9]{16}\.(jpg|png|webp)$/', $name)) {
        http_response_code(404);
        exit('File not found.');
    }
    send_stored(storage_read('uploads/products/' . $name), $name, true);
}

$u = require_login();
$rx = q_one('SELECT * FROM prescriptions WHERE id = ?', [(int)($_GET['rx'] ?? 0)]);
if (!$rx || ($rx['user_id'] != $u['id'] && !is_staff())) {
    http_response_code(404);
    exit('File not found.');
}
send_stored(storage_read($rx['file_path']), (string)$rx['original_name'], false);

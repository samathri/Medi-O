<?php
/** Streams a private prescription file to its owner or to staff. */
require_once __DIR__ . '/config.php';
$u = require_login();

$rx = q_one('SELECT * FROM prescriptions WHERE id = ?', [(int)($_GET['rx'] ?? 0)]);
if (!$rx || ($rx['user_id'] != $u['id'] && !is_staff())) {
    http_response_code(404);
    exit('File not found.');
}

$path = ROOT_PATH . '/' . $rx['file_path'];
if (!is_file($path)) {
    http_response_code(404);
    exit('File missing on disk.');
}

$mime = mime_content_type($path) ?: 'application/octet-stream';
header('Content-Type: ' . $mime);
header('Content-Length: ' . filesize($path));
header('Content-Disposition: inline; filename="' . rawurlencode($rx['original_name']) . '"');
header('X-Content-Type-Options: nosniff');
readfile($path);

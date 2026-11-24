<?php
/**
 * Vercel entry point. Vercel runs PHP only from /api, so every page request
 * is routed here (see vercel.json) and this file hands over to the real page.
 * Not used on XAMPP / normal PHP hosting.
 */
$path = ltrim((string)($_GET['__path'] ?? ''), '/');
if ($path === '') $path = 'index.php';
unset($_GET['__path'], $_REQUEST['__path']);

// Only real pages may be run: root pages and the api/auth/staff/admin folders.
$ok = (bool)preg_match('#^(?:(?:api|auth|staff|admin)/)?[a-z0-9][a-z0-9\-]*\.php$#', $path)
    && !in_array($path, ['api/index.php', 'config.php', 'config.local.php', 'config.local.example.php'], true);
$file = dirname(__DIR__) . '/' . $path;
if (!$ok || !is_file($file)) {
    http_response_code(404);
    header('Content-Type: text/plain; charset=utf-8');
    exit('Page not found.');
}

// Make the page believe it was requested directly.
$query = http_build_query($_GET);
$_SERVER['QUERY_STRING'] = $query;
$_SERVER['SCRIPT_NAME'] = '/' . $path;
$_SERVER['PHP_SELF'] = '/' . $path;
$_SERVER['SCRIPT_FILENAME'] = $file;
$_SERVER['REQUEST_URI'] = '/' . $path . ($query !== '' ? '?' . $query : '');

chdir(dirname($file));
require $file;

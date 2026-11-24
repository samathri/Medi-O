<?php
/**
 * Web trigger for the dose-reminder job, for hosts without a real cron
 * (Vercel Cron, cron-job.org …). Requires the secret from CRON_SECRET:
 *   GET /cron-run.php            with header  Authorization: Bearer <secret>   (Vercel Cron does this)
 *   GET /cron-run.php?key=<secret>                                             (other schedulers)
 */
require_once __DIR__ . '/config.php';

$given = $_GET['key'] ?? '';
if (preg_match('/^Bearer\s+(.+)$/i', $_SERVER['HTTP_AUTHORIZATION'] ?? '', $m)) $given = $m[1];
if (CRON_SECRET === '' || !hash_equals(CRON_SECRET, (string)$given)) {
    http_response_code(403);
    exit('Forbidden.');
}
define('MEDIO_CRON_AUTHORISED', true);
header('Content-Type: text/plain; charset=utf-8');
require __DIR__ . '/cron/send-reminders.php';

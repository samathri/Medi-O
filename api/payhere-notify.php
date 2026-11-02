<?php
/** PayHere server callback. Requires a publicly reachable URL for live delivery. */
require_once __DIR__ . '/../config.php';
require_once __DIR__ . '/../includes/payhere.php';
require_once __DIR__ . '/../includes/payhere-ipn.php';
if (!payhere_configured() || $_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(400);
    exit;
}
try {
    [$status, $message] = process_payhere_ipn($_POST);
    http_response_code($status);
    echo $message;
} catch (Throwable $ex) {
    error_log('PayHere callback failed: ' . $ex->getMessage());
    http_response_code(500);
    echo 'Unable to process payment. Please retry.';
}

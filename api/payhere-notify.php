<?php
/**
 * PayHere IPN (server-to-server notification).
 * status_code: 2 = success, 0 = pending, -1 = cancelled, -2 = failed, -3 = chargeback
 * NOTE: PayHere cannot reach http://localhost — use ngrok or a public URL to
 * test real IPNs; the return_url flow still works locally.
 */
require_once __DIR__ . '/../config.php';
require_once __DIR__ . '/../includes/payhere.php';

if (!payhere_configured() || $_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(400);
    exit;
}

if (!payhere_verify_ipn($_POST)) {
    error_log('PayHere IPN: bad signature for order ' . ($_POST['order_id'] ?? '?'));
    http_response_code(403);
    exit;
}

$order = q_one('SELECT * FROM orders WHERE order_no = ?', [$_POST['order_id'] ?? '']);
if (!$order) {
    http_response_code(404);
    exit;
}

$statusCode = (int)($_POST['status_code'] ?? 0);
$paid = $statusCode === 2;

q('INSERT INTO payments (order_id, method, amount, currency, status, gateway_ref, payload) VALUES (?,?,?,?,?,?,?)', [
    $order['id'], 'payhere',
    (float)($_POST['payhere_amount'] ?? $order['total']),
    $_POST['payhere_currency'] ?? 'LKR',
    $paid ? 'paid' : 'failed',
    $_POST['payment_id'] ?? null,
    json_encode($_POST),
]);

if ($paid && $order['payment_status'] !== 'paid') {
    q("UPDATE orders SET payment_status='paid', status='processing' WHERE id=?", [$order['id']]);
    notify((int)$order['user_id'], 'payment', 'Payment received for #' . $order['order_no'],
        'Your payment was confirmed. We\'re preparing your order.', url('order.php?id=' . $order['id']));
}

echo 'OK';

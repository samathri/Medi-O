<?php
/** Process authenticated gateway events atomically, once per gateway payment. */
function process_payhere_ipn(array $post): array
{
    foreach (['merchant_id','order_id','payment_id','payhere_amount','payhere_currency','status_code','md5sig'] as $key) {
        if (!isset($post[$key]) || !is_string($post[$key]) || $post[$key] === '') return [400, 'Missing payment fields'];
    }
    if (!hash_equals(PAYHERE_MERCHANT_ID, $post['merchant_id']) || !payhere_verify_ipn($post)) return [403, 'Invalid payment signature'];
    if ($post['payhere_currency'] !== 'LKR' || !preg_match('/^\d{1,8}(?:\.\d{1,2})?$/D', $post['payhere_amount'])
        || strlen($post['payment_id']) > 100 || !in_array($post['status_code'], ['2','0','-1','-2','-3'], true)) return [400, 'Invalid payment fields'];
    $amountParts = explode('.', $post['payhere_amount'], 2);
    $amount = (string)(int)$amountParts[0] . '.' . str_pad($amountParts[1] ?? '', 2, '0');
    $status = ['2'=>'paid', '0'=>'pending', '-1'=>'cancelled', '-2'=>'failed', '-3'=>'chargeback'][$post['status_code']];
    // Serializes the same gateway ID even when callbacks reference different orders.
    $lock = 'medio-ipn-' . substr(hash('sha256', DB_NAME . ':' . $post['payment_id']), 0, 48);
    if ((int)q_val('SELECT GET_LOCK(?, 10)', [$lock]) !== 1) return [503, 'Please retry'];
    $pdo = db();
    try {
        $pdo->beginTransaction();
        $order = q_one('SELECT * FROM orders WHERE order_no=? FOR UPDATE', [$post['order_id']]);
        if (!$order) return [404, 'Order not found'];
        if ($order['payment_method'] !== 'payhere' || $amount !== $order['total']) return [409, 'Payment does not match order'];
        $payment = q_one("SELECT * FROM payments WHERE method='payhere' AND gateway_ref=? ORDER BY id LIMIT 1 FOR UPDATE", [$post['payment_id']]);
        if ($payment && (int)$payment['order_id'] !== (int)$order['id']) return [409, 'Payment already belongs to another order'];
        // Acknowledge replays and stale events without changing state or notifications.
        if ($payment && ($payment['status'] === $status || $payment['status'] === 'chargeback'
            || ($payment['status'] === 'paid' && $status !== 'chargeback')
            || (in_array($payment['status'], ['failed','cancelled'], true) && $status === 'pending'))) {
            $pdo->commit();
            return [200, 'OK'];
        }
        if ($status === 'paid' && ($order['status'] === 'cancelled' || in_array($order['payment_status'], ['paid','refunded'], true))) return [409, 'Order requires payment reconciliation'];
        if ($status === 'chargeback' && (!$payment || $payment['status'] !== 'paid')) return [409, 'No matching paid transaction'];
        $payload = json_encode($post, JSON_THROW_ON_ERROR);
        if ($payment) {
            q('UPDATE payments SET status=?, payload=? WHERE id=?', [$status, $payload, $payment['id']]);
        } else {
            q('INSERT INTO payments(order_id,method,amount,currency,status,gateway_ref,payload) VALUES(?,?,?,?,?,?,?)',
                [$order['id'], 'payhere', $amount, 'LKR', $status, $post['payment_id'], $payload]);
        }
        if ($status === 'paid') {
            q("UPDATE orders SET payment_status='paid', status=IF(status='placed','processing',status) WHERE id=?", [$order['id']]);
            notify((int)$order['user_id'], 'payment', 'Payment received for #' . $order['order_no'],
                'Your payment was confirmed.', url('order.php?id=' . $order['id']));
        } elseif ($status === 'chargeback') {
            q("UPDATE orders SET payment_status='failed' WHERE id=?", [$order['id']]);
            notify((int)$order['user_id'], 'payment', 'Payment reversed for #' . $order['order_no'],
                'Please contact the pharmacy about this payment.', url('order.php?id=' . $order['id']));
        } elseif (in_array($status, ['failed','cancelled'], true) && $order['payment_status'] === 'pending') {
            q("UPDATE orders SET payment_status='failed' WHERE id=?", [$order['id']]);
        }
        $pdo->commit();
        return [200, 'OK'];
    } finally {
        if ($pdo->inTransaction()) $pdo->rollBack();
        q_val('SELECT RELEASE_LOCK(?)', [$lock]);
    }
}

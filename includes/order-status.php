<?php
/** Apply an order transition once, atomically with inventory and collection audit. */

/** Transitions each status may move to ('ready' may step back to 'processing' to undo a mis-click). */
function order_allowed_transitions(string $status): array
{
    return [
        'placed' => ['processing', 'ready', 'collected', 'completed', 'cancelled'],
        'processing' => ['ready', 'collected', 'completed', 'cancelled'],
        'ready' => ['processing', 'collected', 'completed', 'cancelled'],
        'collected' => ['completed'],
        'completed' => [], 'cancelled' => [],
    ][$status] ?? [];
}

function transition_order(int $orderId, string $next, int $staffId): bool
{
    $pdo = db();
    $pdo->beginTransaction();
    try {
        $order = q_one('SELECT * FROM orders WHERE id=? FOR UPDATE', [$orderId]);
        if (!$order || !in_array($next, order_allowed_transitions($order['status']), true)) {
            $pdo->rollBack();
            return false;
        }
        q('UPDATE orders SET status=?, payment_status=IF(?="collected" AND payment_method="cash","paid",payment_status) WHERE id=?',
            [$next, $next, $orderId]);
        if ($next === 'cancelled') {
            foreach (q_all('SELECT product_id, quantity FROM order_items WHERE order_id=? AND product_id IS NOT NULL ORDER BY product_id', [$orderId]) as $item) {
                q('UPDATE products SET stock=stock+? WHERE id=?', [$item['quantity'], $item['product_id']]);
            }
        }
        if ($next === 'collected') {
            q('INSERT INTO collections (user_id, staff_id, order_id, note) VALUES (?,?,?,?)',
                [$order['user_id'], $staffId, $orderId, 'Order marked collected']);
        }
        $pdo->commit();
        return true;
    } catch (Throwable $ex) {
        if ($pdo->inTransaction()) $pdo->rollBack();
        throw $ex;
    }
}

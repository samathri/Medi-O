<?php
/** Cart operations (JSON, login + CSRF required). */
require_once __DIR__ . '/../config.php';

$u = current_user();
if (!$u) json_out(['ok' => false, 'error' => 'Please log in first.'], 401);
csrf_check();

$in = json_decode(file_get_contents('php://input'), true) ?: [];
$action = $in['action'] ?? '';

function cart_summary(int $userId): array
{
    $count = (int)q_val('SELECT COALESCE(SUM(quantity),0) FROM cart_items WHERE user_id=?', [$userId]);
    $subtotal = (float)q_val('SELECT COALESCE(SUM(ci.quantity * p.price),0) FROM cart_items ci
                              JOIN products p ON p.id = ci.product_id WHERE ci.user_id=?', [$userId]);
    return ['count' => $count, 'subtotal' => $subtotal, 'subtotal_text' => money($subtotal)];
}

switch ($action) {
    case 'add': {
        $pid = (int)($in['product_id'] ?? 0);
        $qty = max(1, min(99, (int)($in['qty'] ?? 1)));
        $p = q_one('SELECT * FROM products WHERE id = ? AND is_active = 1', [$pid]);
        if (!$p) json_out(['ok' => false, 'error' => 'Product not found.'], 404);
        if ((int)$p['stock'] < 1) json_out(['ok' => false, 'error' => 'This product is out of stock.']);

        $existing = q_one('SELECT * FROM cart_items WHERE user_id=? AND product_id=?', [$u['id'], $pid]);
        $newQty = min((int)$p['stock'], ($existing['quantity'] ?? 0) + $qty);
        if ($existing) {
            q('UPDATE cart_items SET quantity=? WHERE id=?', [$newQty, $existing['id']]);
        } else {
            q('INSERT INTO cart_items (user_id, product_id, quantity) VALUES (?,?,?)', [$u['id'], $pid, $newQty]);
        }
        json_out(['ok' => true, 'message' => 'Added to cart', ...cart_summary((int)$u['id'])]);
    }

    case 'update': {
        $itemId = (int)($in['item_id'] ?? 0);
        $qty = max(0, min(99, (int)($in['qty'] ?? 1)));
        $item = q_one('SELECT ci.*, p.stock, p.price FROM cart_items ci JOIN products p ON p.id=ci.product_id
                       WHERE ci.id=? AND ci.user_id=?', [$itemId, $u['id']]);
        if (!$item) json_out(['ok' => false, 'error' => 'Item not found.'], 404);
        if ($qty === 0) {
            q('DELETE FROM cart_items WHERE id=?', [$itemId]);
        } else {
            $qty = min($qty, (int)$item['stock']);
            q('UPDATE cart_items SET quantity=? WHERE id=?', [$qty, $itemId]);
        }
        $line = $qty * (float)$item['price'];
        json_out(['ok' => true, 'qty' => $qty, 'line_text' => money($line), ...cart_summary((int)$u['id'])]);
    }

    case 'remove': {
        $itemId = (int)($in['item_id'] ?? 0);
        q('DELETE FROM cart_items WHERE id=? AND user_id=?', [$itemId, $u['id']]);
        json_out(['ok' => true, 'message' => 'Removed from cart', ...cart_summary((int)$u['id'])]);
    }

    default:
        json_out(['ok' => false, 'error' => 'Unknown action.'], 400);
}

<?php
$pageTitle = 'Orders';
require_once __DIR__ . '/../config.php';
$staff = require_staff();
require_once dirname(__DIR__) . '/includes/order-status.php';
require_once dirname(__DIR__) . '/includes/whatsapp.php';
require_once dirname(__DIR__) . '/includes/mailer.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    csrf_check();
    $order = q_one('SELECT o.*, u.name AS customer, u.email, u.phone, u.whatsapp_opt_in
                    FROM orders o JOIN users u ON u.id=o.user_id WHERE o.id=?', [(int)$_POST['order_id']]);
    $newStatus = $_POST['status'] ?? '';
    $allowed = ['processing', 'ready', 'collected', 'completed', 'cancelled'];

    if ($order && in_array($newStatus, $allowed, true)) {
        if (!transition_order((int)$order['id'], $newStatus, (int)$staff['id'])) {
            flash('info', 'Order unchanged: that transition is already applied or is not allowed.');
            redirect('staff/orders.php');
        }
        $texts = [
            'processing' => 'Your order is being prepared.',
            'ready' => $order['delivery_method'] === 'pickup'
                ? 'Your order is READY for pickup! Show your QR card at the counter. 🎉'
                : 'Your order is out for delivery!',
            'collected' => 'Your order was collected. Thank you for shopping with Medi-O!',
            'completed' => 'Your order is complete. Get well soon!',
            'cancelled' => 'Your order was cancelled. Contact us if this is unexpected.',
        ];
        $msg = 'Order #' . $order['order_no'] . ': ' . $texts[$newStatus];
        notify((int)$order['user_id'], 'order', 'Order #' . $order['order_no'] . ' — ' . ucfirst($newStatus), $texts[$newStatus], url('order.php?id=' . $order['id']));
        if ($order['email']) {
            send_mail($order['email'], 'Order #' . $order['order_no'] . ' update', mail_template('Order update', '<p>' . e($msg) . '</p>', 'Track order', url('order.php?id=' . $order['id'])));
        }
        if ($order['phone'] && $order['whatsapp_opt_in']) {
            wa_send((int)$order['user_id'], $order['phone'], 'Medi-O: ' . $msg);
        }
        flash('success', 'Order updated to "' . $newStatus . '" — customer notified.');
    }
    redirect('staff/orders.php' . (isset($_POST['back_filter']) ? '?f=' . urlencode($_POST['back_filter']) : ''));
}

require_once __DIR__ . '/../components/staff-header.php';

$filter = $_GET['f'] ?? 'open';
$map = [
    'open' => "o.status IN ('placed','processing')",
    'ready' => "o.status = 'ready'",
    'done' => "o.status IN ('collected','completed')",
    'cancelled' => "o.status = 'cancelled'",
    'all' => '1',
];
if (!isset($map[$filter])) $filter = 'open';

$orders = q_all("SELECT o.*, u.name AS customer, u.phone,
                 (SELECT COUNT(*) FROM order_items oi WHERE oi.order_id=o.id) AS items
                 FROM orders o JOIN users u ON u.id=o.user_id
                 WHERE {$map[$filter]} ORDER BY o.id DESC LIMIT 150");
?>
<h1 class="h3 fw-bold mb-4">Orders</h1>

<ul class="nav nav-pills gap-2 mb-4 flex-wrap">
  <?php foreach (['open' => 'To prepare', 'ready' => 'Ready', 'done' => 'Completed', 'cancelled' => 'Cancelled', 'all' => 'All'] as $k => $label): ?>
    <li class="nav-item"><a class="nav-link <?= $filter === $k ? 'active' : '' ?>" href="<?= url('staff/orders.php?f=' . $k) ?>"><?= $label ?></a></li>
  <?php endforeach; ?>
</ul>

<?php if (!$orders): ?>
  <div class="mo-card mo-empty"><i class="bi bi-box-seam"></i>No orders in this view</div>
<?php else: ?>
  <div class="mo-card p-0 overflow-hidden">
    <div class="table-responsive">
      <table class="table mo-table table-hover align-middle mb-0">
        <thead><tr><th>Order</th><th>Customer</th><th>Items</th><th>Total / payment</th><th>Method</th><th>Status</th><th style="width:220px">Update</th></tr></thead>
        <tbody>
          <?php foreach ($orders as $o): ?>
            <tr>
              <td>
                <div class="fw-bold">#<?= e($o['order_no']) ?></div>
                <small class="text-muted"><?= date('j M, g:ia', strtotime($o['created_at'])) ?></small>
              </td>
              <td>
                <div class="fw-semibold"><?= e($o['customer']) ?></div>
                <?php if ($o['phone']): ?>
                  <a class="small text-decoration-none text-success" target="_blank" rel="noopener" href="<?= wa_link($o['phone']) ?>">
                    <i class="bi bi-whatsapp"></i> <?= e(pretty_phone($o['phone'])) ?></a>
                <?php endif; ?>
              </td>
              <td>
                <button class="btn btn-sm btn-link text-decoration-none p-0" data-bs-toggle="collapse" data-bs-target="#items<?= $o['id'] ?>">
                  <?= (int)$o['items'] ?> item<?= $o['items'] == 1 ? '' : 's' ?> <i class="bi bi-chevron-down small"></i></button>
              </td>
              <td>
                <div class="fw-semibold"><?= money((float)$o['total']) ?></div>
                <span class="mo-status mo-status-<?= e($o['payment_status']) ?>"><?= e($o['payment_method']) ?> · <?= e($o['payment_status']) ?></span>
              </td>
              <td><span class="badge text-bg-light border"><i class="bi <?= $o['delivery_method'] === 'pickup' ? 'bi-qr-code-scan' : 'bi-truck' ?> me-1"></i><?= ucfirst(e($o['delivery_method'])) ?></span></td>
              <td><span class="mo-status mo-status-<?= e($o['status']) ?>"><?= ucfirst(e($o['status'])) ?></span></td>
              <td>
                <?php if (!in_array($o['status'], ['collected', 'completed', 'cancelled'], true)): ?>
                  <form method="post" class="d-flex gap-1">
                    <?= csrf_field() ?>
                    <input type="hidden" name="order_id" value="<?= $o['id'] ?>">
                    <input type="hidden" name="back_filter" value="<?= e($filter) ?>">
                    <select name="status" class="form-select form-select-sm">
                      <?php foreach (['processing' => 'Preparing', 'ready' => $o['delivery_method'] === 'pickup' ? 'Ready for pickup' : 'Out for delivery', 'collected' => 'Collected', 'completed' => 'Completed', 'cancelled' => 'Cancel'] as $s => $label): ?>
                        <option value="<?= $s ?>" <?= $s === 'processing' && $o['status'] === 'placed' ? 'selected' : '' ?>><?= $label ?></option>
                      <?php endforeach; ?>
                    </select>
                    <button class="btn btn-sm mo-btn">Go</button>
                  </form>
                <?php endif; ?>
              </td>
            </tr>
            <tr class="collapse" id="items<?= $o['id'] ?>">
              <td colspan="7" class="bg-light small">
                <?php foreach (q_all('SELECT * FROM order_items WHERE order_id=?', [$o['id']]) as $it): ?>
                  <span class="me-3">• <?= e($it['product_name']) ?> × <?= (int)$it['quantity'] ?></span>
                <?php endforeach; ?>
                <?php if ($o['note']): ?><div class="mt-1"><i class="bi bi-chat-left-text me-1"></i>Note: <?= e($o['note']) ?></div><?php endif; ?>
                <?php if ($o['address']): ?><div class="mt-1"><i class="bi bi-geo-alt me-1"></i><?= e($o['address']) ?></div><?php endif; ?>
              </td>
            </tr>
          <?php endforeach; ?>
        </tbody>
      </table>
    </div>
  </div>
<?php endif; ?>
<?php require_once __DIR__ . '/../components/staff-footer.php'; ?>

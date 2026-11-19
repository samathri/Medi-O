<?php
$pageTitle = 'Orders';
require_once __DIR__ . '/../config.php';
$staff = require_staff();
require_once dirname(__DIR__) . '/includes/order-status.php';
require_once dirname(__DIR__) . '/includes/mailer.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    csrf_check();
    $order = q_one('SELECT o.*, u.name AS customer, u.email, u.phone
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
        alert_user((int)$order['user_id'], 'order', 'Order #' . $order['order_no'] . ' — ' . status_label($newStatus), $texts[$newStatus], url('order.php?id=' . $order['id']), 'Track my order');
        flash('success', 'Order #' . $order['order_no'] . ' is now "' . status_label($newStatus) . '" — the customer has been told.');
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
                  <a class="small text-decoration-none" href="tel:<?= e($o['phone']) ?>"><i class="bi bi-telephone"></i> <?= e(pretty_phone($o['phone'])) ?></a>
                <?php endif; ?>
              </td>
              <td>
                <ul class="list-unstyled small mb-0">
                  <?php foreach (q_all('SELECT product_name, quantity FROM order_items WHERE order_id=?', [$o['id']]) as $it): ?>
                    <li><b><?= (int)$it['quantity'] ?>×</b> <?= e($it['product_name']) ?></li>
                  <?php endforeach; ?>
                </ul>
                <?php if ($o['note']): ?><div class="small text-muted mt-1"><i class="bi bi-chat-left-text me-1"></i><?= e($o['note']) ?></div><?php endif; ?>
                <?php if ($o['address']): ?><div class="small text-muted mt-1"><i class="bi bi-geo-alt me-1"></i><?= e($o['address']) ?></div><?php endif; ?>
              </td>
              <td>
                <div class="fw-semibold"><?= money((float)$o['total']) ?></div>
                <span class="mo-status mo-status-<?= e($o['payment_status']) ?>"><?= $o['payment_method'] === 'cash' ? 'Cash' : 'Online' ?> · <?= $o['payment_status'] === 'paid' ? 'paid' : 'not paid yet' ?></span>
              </td>
              <td><span class="badge text-bg-light border"><i class="bi <?= $o['delivery_method'] === 'pickup' ? 'bi-qr-code-scan' : 'bi-truck' ?> me-1"></i><?= ucfirst(e($o['delivery_method'])) ?></span></td>
              <td><span class="mo-status mo-status-<?= e($o['status']) ?>"><?= e(status_label($o['status'])) ?></span></td>
              <td>
                <?php if (!in_array($o['status'], ['collected', 'completed', 'cancelled'], true)): ?>
                  <?php
                  $pickup = $o['delivery_method'] === 'pickup';
                  $label = ['processing' => $o['status'] === 'ready' ? 'Back to preparing' : 'Start preparing',
                            'ready' => $pickup ? 'Mark ready for pickup' : 'Send out for delivery',
                            'collected' => 'Mark collected', 'completed' => $pickup ? 'Complete' : 'Mark delivered', 'cancelled' => 'Cancel order'];
                  $next = ['placed' => 'processing', 'processing' => 'ready', 'ready' => $pickup ? 'collected' : 'completed'][$o['status']] ?? null;
                  $others = array_diff(order_allowed_transitions($o['status']), [$next]);
                  ?>
                  <form method="post" class="d-flex gap-1 align-items-center">
                    <?= csrf_field() ?>
                    <input type="hidden" name="order_id" value="<?= $o['id'] ?>">
                    <input type="hidden" name="back_filter" value="<?= e($filter) ?>">
                    <?php if ($next): ?>
                      <button name="status" value="<?= $next ?>" class="btn btn-sm mo-btn text-nowrap"><?= $label[$next] ?></button>
                    <?php endif; ?>
                    <?php if ($others): ?>
                      <div class="dropdown">
                        <button type="button" class="btn btn-sm btn-light border" data-bs-toggle="dropdown" aria-label="More actions"><i class="bi bi-three-dots"></i></button>
                        <ul class="dropdown-menu dropdown-menu-end">
                          <?php foreach ($others as $s): ?>
                            <li><button name="status" value="<?= $s ?>" class="dropdown-item <?= $s === 'cancelled' ? 'text-danger' : '' ?>"
                              <?= $s === 'cancelled' ? "onclick=\"return confirm('Cancel this order? The customer will be told.')\"" : '' ?>><?= $label[$s] ?></button></li>
                          <?php endforeach; ?>
                        </ul>
                      </div>
                    <?php endif; ?>
                  </form>
                <?php endif; ?>
              </td>
            </tr>
          <?php endforeach; ?>
        </tbody>
      </table>
    </div>
  </div>
<?php endif; ?>
<?php require_once __DIR__ . '/../components/staff-footer.php'; ?>

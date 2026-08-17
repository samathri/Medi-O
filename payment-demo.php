<?php
/**
 * Built-in demo payment gateway — used when PayHere credentials are not set,
 * so online payment stays fully testable for free. Looks and behaves like a
 * real gateway, but no money moves and no card data is real or stored.
 */
require_once __DIR__ . '/config.php';
$u = require_login();

$orderId = (int)($_GET['order'] ?? $_POST['order'] ?? 0);
$order = q_one('SELECT * FROM orders WHERE id=? AND user_id=?', [$orderId, $u['id']]);
if (!$order) {
    flash('error', 'Order not found.');
    redirect('orders.php');
}
if ($order['payment_status'] === 'paid') {
    redirect('order-success.php?id=' . $order['id']);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    csrf_check();
    $outcome = $_POST['outcome'] ?? 'success';

    if ($outcome === 'success') {
        q("UPDATE orders SET payment_status='paid', status='processing' WHERE id=?", [$order['id']]);
        q('INSERT INTO payments (order_id, method, amount, status, gateway_ref) VALUES (?,?,?,?,?)',
            [$order['id'], 'demo-gateway', $order['total'], 'paid', 'DEMO-' . strtoupper(bin2hex(random_bytes(4)))]);
        notify((int)$u['id'], 'payment', 'Payment received for #' . $order['order_no'],
            money((float)$order['total']) . ' paid successfully.', url('order.php?id=' . $order['id']));
        redirect('order-success.php?id=' . $order['id']);
    }

    q('INSERT INTO payments (order_id, method, amount, status) VALUES (?,?,?,?)',
        [$order['id'], 'demo-gateway', $order['total'], 'failed']);
    flash('error', 'Payment failed (demo). You can retry from your order page.');
    redirect('order.php?id=' . $order['id']);
}

$pageTitle = 'Demo payment';
include __DIR__ . '/components/header.php';
?>
<div class="container py-5">
  <div class="mo-card mo-auth-card p-4 p-md-5">
    <div class="d-flex align-items-center justify-content-between mb-4">
      <h1 class="h4 fw-bold mb-0"><i class="bi bi-credit-card me-2" style="color:var(--mo-primary)"></i>Demo Gateway</h1>
      <span class="badge text-bg-warning">TEST MODE</span>
    </div>
    <div class="alert alert-info small">
      <i class="bi bi-info-circle me-1"></i>PayHere is not configured, so this <strong>simulated gateway</strong>
      is standing in. No real payment happens. To use the real PayHere sandbox, add your merchant keys in
      <code>config.local.php</code>.
    </div>

    <div class="d-flex justify-content-between border rounded-3 p-3 mb-4 bg-light">
      <span class="text-muted">Order #<?= e($order['order_no']) ?></span>
      <span class="fw-bold"><?= money((float)$order['total']) ?></span>
    </div>

    <form method="post">
      <?= csrf_field() ?>
      <input type="hidden" name="order" value="<?= $order['id'] ?>">
      <div class="mb-3">
        <label class="form-label fw-semibold">Card number</label>
        <input class="form-control" value="4242 4242 4242 4242" readonly>
      </div>
      <div class="row g-3 mb-4">
        <div class="col-6">
          <label class="form-label fw-semibold">Expiry</label>
          <input class="form-control" value="12/28" readonly>
        </div>
        <div class="col-6">
          <label class="form-label fw-semibold">CVC</label>
          <input class="form-control" value="123" readonly>
        </div>
      </div>
      <div class="d-grid gap-2">
        <button name="outcome" value="success" class="btn mo-btn btn-lg"><i class="bi bi-check-circle me-2"></i>Simulate successful payment</button>
        <button name="outcome" value="fail" class="btn btn-outline-danger">Simulate failed payment</button>
      </div>
    </form>
  </div>
</div>
<?php include __DIR__ . '/components/footer.php'; ?>

<?php
require_once __DIR__ . '/config.php';
require_once __DIR__ . '/includes/qr.php';
$u = require_login();

$orderId = (int)($_GET['id'] ?? 0);
$order = q_one('SELECT * FROM orders WHERE id=? AND user_id=?', [$orderId, $u['id']]);
if (!$order) {
    flash('error', 'Order not found.');
    redirect('orders.php');
}
$items = q_all('SELECT * FROM order_items WHERE order_id=?', [$order['id']]);
if (isset($_GET['cancelled'])) {
    flash('info', 'Payment was cancelled. You can retry below.');
}

$steps = $order['delivery_method'] === 'pickup'
    ? [['placed', 'Order placed'], ['processing', 'Being prepared'], ['ready', 'Ready for pickup'], ['collected', 'Collected']]
    : [['placed', 'Order placed'], ['processing', 'Being prepared'], ['ready', 'Out for delivery'], ['completed', 'Delivered']];
$statusOrder = array_column($steps, 0);
$currentIdx = array_search($order['status'], $statusOrder, true);
if ($order['status'] === 'completed' && $order['delivery_method'] === 'pickup') $currentIdx = 3;
$cancelled = $order['status'] === 'cancelled';

$qr = qr_data_uri(url('staff/verify.php?t=o:' . $order['qr_token']));
$pageTitle = 'Order #' . $order['order_no'];
include __DIR__ . '/components/header.php';
?>
<div class="container py-4 py-md-5">
  <nav class="small mb-2"><a href="<?= url('orders.php') ?>" class="text-decoration-none"><i class="bi bi-arrow-left me-1"></i>All orders</a></nav>
  <div class="page-head pt-0 d-flex flex-wrap justify-content-between align-items-center gap-2">
    <h1 class="h2 mb-0">Order #<?= e($order['order_no']) ?></h1>
    <span class="mo-status mo-status-<?= e($order['status']) ?> fs-6"><?= ucfirst(e($order['status'])) ?></span>
  </div>

  <div class="row g-4">
    <div class="col-lg-8">
      <div class="mo-card p-4 mb-4" data-reveal>
        <h2 class="h6 fw-bold text-uppercase text-muted mb-4">Tracking</h2>
        <?php if ($cancelled): ?>
          <div class="alert alert-danger mb-0"><i class="bi bi-x-circle me-2"></i>This order was cancelled.</div>
        <?php else: ?>
          <ul class="mo-timeline">
            <?php foreach ($steps as $idx => [$key, $label]): ?>
              <li class="<?= $currentIdx !== false && $idx < $currentIdx ? 'done' : '' ?> <?= $idx === $currentIdx ? 'done current' : '' ?>">
                <span class="dot"><i class="bi bi-check"></i></span>
                <div class="fw-semibold"><?= $label ?></div>
                <?php if ($idx === $currentIdx): ?>
                  <div class="small text-muted">Updated <?= time_ago($order['updated_at']) ?></div>
                <?php endif; ?>
              </li>
            <?php endforeach; ?>
          </ul>
        <?php endif; ?>
      </div>

      <div class="mo-card p-4" data-reveal>
        <h2 class="h6 fw-bold text-uppercase text-muted mb-3">Items</h2>
        <?php foreach ($items as $i): ?>
          <div class="d-flex justify-content-between py-2 border-bottom small">
            <span><?= e($i['product_name']) ?> × <?= (int)$i['quantity'] ?></span>
            <span class="fw-semibold"><?= money($i['price'] * $i['quantity']) ?></span>
          </div>
        <?php endforeach; ?>
        <div class="d-flex justify-content-between pt-3 fw-bold"><span>Total</span><span><?= money((float)$order['total']) ?></span></div>
        <div class="small text-muted mt-2">
          Payment: <?= $order['payment_method'] === 'payhere' ? 'Online (PayHere)' : 'Cash' ?> ·
          <span class="mo-status mo-status-<?= e($order['payment_status']) ?>"><?= ucfirst(e($order['payment_status'])) ?></span>
          <?php if ($order['payment_status'] !== 'paid' && $order['payment_method'] === 'payhere' && !$cancelled): ?>
            <a class="btn btn-sm mo-btn-soft ms-2" href="<?= url('payment.php?order=' . $order['id']) ?>">Pay now</a>
          <?php endif; ?>
        </div>
      </div>
    </div>

    <div class="col-lg-4" data-reveal>
      <?php if (!$cancelled && $order['delivery_method'] === 'pickup'): ?>
        <div class="mo-card p-4 text-center mb-4">
          <h2 class="h6 fw-bold text-uppercase text-muted">Collection QR</h2>
          <img src="<?= $qr ?>" alt="Order QR" class="img-fluid rounded-3 my-2" style="max-width:200px">
          <p class="small text-muted mb-0">Show this (or your personal QR card) at the counter.</p>
        </div>
      <?php endif; ?>
      <div class="mo-card p-4">
        <h2 class="h6 fw-bold text-uppercase text-muted mb-3">Need help?</h2>
        <div class="d-grid gap-2">
          <a class="btn mo-btn-soft" href="<?= url('chat.php') ?>"><i class="bi bi-chat-dots me-2"></i>Chat with pharmacy</a>
          <a class="btn btn-outline-success" target="_blank" rel="noopener"
             href="https://wa.me/<?= e(setting('pharmacy_whatsapp')) ?>?text=<?= rawurlencode('Hi! I have a question about my order #' . $order['order_no']) ?>">
            <i class="bi bi-whatsapp me-2"></i>WhatsApp us</a>
        </div>
      </div>
    </div>
  </div>
</div>
<?php include __DIR__ . '/components/footer.php'; ?>

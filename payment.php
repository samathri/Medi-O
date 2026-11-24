<?php
require_once __DIR__ . '/config.php';
require_once __DIR__ . '/includes/payhere.php';
$u = require_login();

$orderId = (int)($_GET['order'] ?? 0);
$order = q_one('SELECT * FROM orders WHERE id=? AND user_id=?', [$orderId, $u['id']]);
if (!$order) {
    flash('error', 'Order not found.');
    redirect('orders.php');
}
if ($order['payment_status'] === 'paid') {
    redirect('order-success.php?id=' . $order['id']);
}

// No PayHere credentials → built-in demo gateway keeps checkout testable
if (!payhere_configured()) {
    if (ALLOW_DEMO_PAYMENTS === true) redirect('payment-demo.php?order=' . $order['id']);
    flash('info', 'Online payment is not available right now — you can pay when you collect or receive your order.');
    redirect('order.php?id=' . $order['id']);
}

[$first, $last] = array_pad(explode(' ', $u['name'], 2), 2, '-');
$hash = payhere_hash($order['order_no'], (float)$order['total']);

$pageTitle = 'Redirecting to PayHere…';
include __DIR__ . '/components/header.php';
?>
<div class="container py-5 text-center">
  <div class="mo-card mo-auth-card p-5">
    <div class="spinner-border text-success mb-3" role="status"></div>
    <h1 class="h4 fw-bold">Taking you to PayHere…</h1>
    <p class="text-muted">Secure <?= PAYHERE_SANDBOX ? 'sandbox' : '' ?> payment for order #<?= e($order['order_no']) ?></p>

    <form id="payhereForm" method="post" action="<?= payhere_checkout_url() ?>">
      <input type="hidden" name="merchant_id" value="<?= e(PAYHERE_MERCHANT_ID) ?>">
      <input type="hidden" name="return_url" value="<?= url('order-success.php?id=' . $order['id']) ?>">
      <input type="hidden" name="cancel_url" value="<?= url('order.php?id=' . $order['id'] . '&cancelled=1') ?>">
      <input type="hidden" name="notify_url" value="<?= url('api/payhere-notify.php') ?>">
      <input type="hidden" name="order_id" value="<?= e($order['order_no']) ?>">
      <input type="hidden" name="items" value="Medi-O order <?= e($order['order_no']) ?>">
      <input type="hidden" name="currency" value="LKR">
      <input type="hidden" name="amount" value="<?= number_format((float)$order['total'], 2, '.', '') ?>">
      <input type="hidden" name="hash" value="<?= e($hash) ?>">
      <input type="hidden" name="first_name" value="<?= e($first) ?>">
      <input type="hidden" name="last_name" value="<?= e($last) ?>">
      <input type="hidden" name="email" value="<?= e($u['email'] ?: 'customer@medi-o.local') ?>">
      <input type="hidden" name="phone" value="<?= e($u['phone'] ?: '0770000000') ?>">
      <input type="hidden" name="address" value="<?= e($order['address'] ?: setting('pharmacy_address')) ?>">
      <input type="hidden" name="city" value="Colombo">
      <input type="hidden" name="country" value="Sri Lanka">
      <button class="btn mo-btn mt-2">Continue to PayHere</button>
    </form>
  </div>
</div>
<script>document.getElementById('payhereForm').submit();</script>
<?php include __DIR__ . '/components/footer.php'; ?>

<?php
require_once __DIR__ . '/config.php';
require_once __DIR__ . '/includes/qr.php';
require_once __DIR__ . '/includes/mailer.php';
$u = require_login();

$orderId = (int)($_GET['id'] ?? 0);
$order = q_one('SELECT * FROM orders WHERE id=? AND user_id=?', [$orderId, $u['id']]);
if (!$order) {
    flash('error', 'Order not found.');
    redirect('orders.php');
}
$items = q_all('SELECT * FROM order_items WHERE order_id=?', [$order['id']]);

// Send confirmation email once (guard with a session flag)
$flagKey = 'order_mail_' . $order['id'];
if (empty($_SESSION[$flagKey]) && $u['email']) {
    $_SESSION[$flagKey] = true;
    $rows = '';
    foreach ($items as $i) {
        $rows .= '<tr><td style="padding:4px 8px">' . e($i['product_name']) . ' × ' . (int)$i['quantity'] . '</td>
                  <td style="padding:4px 8px;text-align:right">' . money($i['price'] * $i['quantity']) . '</td></tr>';
    }
    send_mail($u['email'], 'Order confirmation #' . $order['order_no'], mail_template(
        'Thanks for your order!',
        '<p>Order <strong>#' . e($order['order_no']) . '</strong> — ' . money((float)$order['total']) . '</p>
         <table style="width:100%;border-collapse:collapse">' . $rows . '</table>
         <p style="margin-top:12px">' . ($order['delivery_method'] === 'pickup'
            ? 'Show the QR code on your order page (or your personal QR card) when collecting.'
            : 'We\'ll deliver to: ' . e($order['address'] ?? '')) . '</p>',
        'View my order', url('order.php?id=' . $order['id'])
    ));
}

$qr = qr_data_uri(url('staff/verify.php?t=o:' . $order['qr_token']));
$pageTitle = 'Order placed';
include __DIR__ . '/components/header.php';
?>
<div class="container py-5">
  <div class="text-center mb-4" data-reveal>
    <div class="d-inline-flex align-items-center justify-content-center rounded-circle mb-3"
         style="width:84px;height:84px;background:#dcfce7">
      <i class="bi bi-check-lg" style="font-size:2.6rem;color:#16a34a"></i>
    </div>
    <h1 class="h2 fw-bold">Order placed successfully!</h1>
    <p class="text-muted">Order <strong>#<?= e($order['order_no']) ?></strong> ·
      <?= $order['payment_status'] === 'paid'
        ? '<span class="mo-status mo-status-paid">Paid</span>'
        : '<span class="mo-status mo-status-pending">Payment: ' . ($order['payment_method'] === 'cash' ? 'cash on ' . e($order['delivery_method']) : 'pending') . '</span>' ?>
    </p>
  </div>

  <div class="row g-4 justify-content-center">
    <div class="col-md-5 col-lg-4" data-reveal>
      <div class="mo-card p-4 text-center h-100">
        <h2 class="h6 fw-bold text-uppercase text-muted">Collection QR</h2>
        <img src="<?= $qr ?>" alt="Order QR code" class="img-fluid rounded-3 my-2" style="max-width:230px">
        <p class="small text-muted mb-0">Show this at the counter — the pharmacist scans it and hands over
          your order. It's also on your <a href="<?= url('order.php?id=' . $order['id']) ?>">order page</a>.</p>
      </div>
    </div>
    <div class="col-md-7 col-lg-5" data-reveal>
      <div class="mo-card p-4 h-100">
        <h2 class="h6 fw-bold text-uppercase text-muted mb-3">Summary</h2>
        <?php foreach ($items as $i): ?>
          <div class="d-flex justify-content-between small mb-2">
            <span class="me-2"><?= e($i['product_name']) ?> × <?= (int)$i['quantity'] ?></span>
            <span class="fw-semibold text-nowrap"><?= money($i['price'] * $i['quantity']) ?></span>
          </div>
        <?php endforeach; ?>
        <hr>
        <div class="d-flex justify-content-between small"><span class="text-muted">Subtotal</span><span><?= money((float)$order['subtotal']) ?></span></div>
        <div class="d-flex justify-content-between small"><span class="text-muted">Delivery</span><span><?= $order['shipping_fee'] > 0 ? money((float)$order['shipping_fee']) : 'FREE' ?></span></div>
        <div class="d-flex justify-content-between fw-bold fs-5 mt-2"><span>Total</span><span><?= money((float)$order['total']) ?></span></div>
        <div class="d-grid gap-2 mt-4">
          <a href="<?= url('order.php?id=' . $order['id']) ?>" class="btn mo-btn">Track this order</a>
          <a href="<?= url('shop.php') ?>" class="btn mo-btn-outline">Continue shopping</a>
        </div>
      </div>
    </div>
  </div>
</div>
<?php include __DIR__ . '/components/footer.php'; ?>

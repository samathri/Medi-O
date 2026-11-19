<?php
require_once __DIR__ . '/config.php';
$u = require_login();

$orders = q_all('SELECT o.*, (SELECT COUNT(*) FROM order_items oi WHERE oi.order_id = o.id) AS item_count
                 FROM orders o WHERE o.user_id = ? ORDER BY o.id DESC', [$u['id']]);

$pageTitle = 'My orders';
include __DIR__ . '/components/header.php';
?>
<div class="container py-4 py-md-5">
  <div class="page-head pt-0"><h1 class="h2">My orders</h1></div>

  <?php if (!$orders): ?>
    <div class="mo-card mo-empty" data-reveal>
      <i class="bi bi-box-seam"></i>
      <h2 class="h5">No orders yet</h2>
      <p>When you place an order it will show up here with live tracking.</p>
      <a class="btn mo-btn" href="<?= url('shop.php') ?>">Browse the shop</a>
    </div>
  <?php else: ?>
    <div class="d-flex flex-column gap-3">
      <?php foreach ($orders as $o): ?>
        <a href="<?= url('order.php?id=' . $o['id']) ?>" class="mo-card p-3 p-md-4 text-decoration-none text-body d-flex flex-wrap align-items-center gap-3" data-reveal>
          <div class="mo-step-icon m-0" style="width:46px;height:46px;font-size:1.2rem"><i class="bi bi-box-seam"></i></div>
          <div class="flex-grow-1">
            <div class="fw-bold">#<?= e($o['order_no']) ?></div>
            <div class="small text-muted"><?= date('j M Y, g:ia', strtotime($o['created_at'])) ?> · <?= (int)$o['item_count'] ?> item<?= $o['item_count'] == 1 ? '' : 's' ?> · <?= e(ucfirst($o['delivery_method'])) ?></div>
          </div>
          <div class="text-end">
            <div class="fw-bold"><?= money((float)$o['total']) ?></div>
            <span class="mo-status mo-status-<?= e($o['status']) ?>"><?= e(status_label($o['status'])) ?></span>
          </div>
          <i class="bi bi-chevron-right text-muted"></i>
        </a>
      <?php endforeach; ?>
    </div>
  <?php endif; ?>
</div>
<?php include __DIR__ . '/components/footer.php'; ?>

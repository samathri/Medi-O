<?php
$pageTitle = 'Dashboard';
require_once __DIR__ . '/../components/staff-header.php';

$stats = [
    ['bi-file-medical', '#fef3c7;color:#b45309', (int)q_val("SELECT COUNT(*) FROM prescriptions WHERE status='pending'"), 'Prescriptions to review', 'staff/prescriptions.php'],
    ['bi-box-seam', '#dbeafe;color:#1d4ed8', (int)q_val("SELECT COUNT(*) FROM orders WHERE status IN ('placed','processing')"), 'Orders to prepare', 'staff/orders.php'],
    ['bi-bag-check', '#dcfce7;color:#15803d', (int)q_val("SELECT COUNT(*) FROM orders WHERE status='ready'"), 'Ready for pickup', 'staff/orders.php?f=ready'],
    ['bi-chat-dots', '#e2ecf4;color:#28506e', (int)q_val('SELECT COALESCE(SUM(staff_unread),0) FROM chat_conversations'), 'Unread chats', 'staff/chat.php'],
];
$todayCollections = q_all('SELECT c.*, u.name AS customer, s.name AS staff_name FROM collections c
                           JOIN users u ON u.id=c.user_id LEFT JOIN users s ON s.id=c.staff_id
                           WHERE DATE(c.created_at)=CURDATE() ORDER BY c.id DESC LIMIT 8');
$recentRx = q_all("SELECT p.*, u.name AS customer FROM prescriptions p JOIN users u ON u.id=p.user_id
                   WHERE p.status='pending' ORDER BY p.id ASC LIMIT 5");
$todoOrders = q_all("SELECT o.id, o.order_no, o.status, o.delivery_method, o.created_at, u.name AS customer,
                            (SELECT COUNT(*) FROM order_items oi WHERE oi.order_id=o.id) AS items
                     FROM orders o JOIN users u ON u.id=o.user_id WHERE o.status IN ('placed','processing') ORDER BY o.id ASC LIMIT 6");
$todo = [];
foreach ($recentRx as $rx) $todo[] = ['t' => strtotime($rx['created_at']), 'icon' => 'bi-file-medical', 'who' => $rx['customer'],
    'what' => 'Prescription to check', 'when' => $rx['created_at'], 'btn' => 'Review', 'href' => 'staff/prescription-review.php?id=' . $rx['id']];
foreach ($todoOrders as $o) $todo[] = ['t' => strtotime($o['created_at']), 'icon' => 'bi-box-seam', 'who' => $o['customer'],
    'what' => 'Order #' . $o['order_no'] . ' · ' . $o['items'] . ' item' . ($o['items'] == 1 ? '' : 's') . ' · ' . ($o['status'] === 'placed' ? 'new' : 'being prepared'),
    'when' => $o['created_at'], 'btn' => $o['status'] === 'placed' ? 'Prepare' : 'Open', 'href' => 'staff/orders.php'];
usort($todo, fn($a, $b) => $a['t'] <=> $b['t']);
$lowStock = q_all('SELECT id, name, stock FROM products WHERE is_active=1 AND stock <= 5 ORDER BY stock LIMIT 8');
?>
<div class="d-flex flex-wrap justify-content-between align-items-center gap-2 mb-4">
  <div>
    <h1 class="h3 fw-bold mb-0">Good <?= date('G') < 12 ? 'morning' : (date('G') < 17 ? 'afternoon' : 'evening') ?>, <?= e(explode(' ', $staff['name'])[0]) ?> 👋</h1>
    <div class="text-muted small"><?= date('l, j F Y') ?></div>
  </div>
  <a href="<?= url('staff/scan.php') ?>" class="btn mo-btn"><i class="bi bi-qr-code-scan me-2"></i>Scan customer QR</a>
</div>

<div class="row g-3 mb-4">
  <?php foreach ($stats as [$icon, $style, $num, $label, $href]): ?>
    <div class="col-sm-6 col-xl-3">
      <a href="<?= url($href) ?>" class="mo-stat text-decoration-none text-body">
        <span class="ic" style="background:<?= $style ?>"><i class="bi <?= $icon ?>"></i></span>
        <span><span class="num d-block"><?= $num ?></span><span class="lbl"><?= $label ?></span></span>
      </a>
    </div>
  <?php endforeach; ?>
</div>

<div class="row g-4">
  <div class="col-lg-6">
    <div class="mo-card p-4 h-100">
      <div class="d-flex justify-content-between align-items-center mb-3">
        <h2 class="h6 fw-bold text-uppercase text-muted mb-0">To do now <span class="text-body">(oldest first)</span></h2>
      </div>
      <?php if (!$todo): ?>
        <div class="mo-empty py-4"><i class="bi bi-check2-circle"></i>All caught up — nothing waiting 🎉</div>
      <?php else: foreach ($todo as $t): ?>
        <div class="d-flex justify-content-between align-items-center gap-2 py-2 border-bottom">
          <i class="bi <?= $t['icon'] ?> fs-5" style="color:var(--green)"></i>
          <div class="flex-grow-1">
            <div class="fw-semibold small"><?= e($t['who']) ?> — <?= e($t['what']) ?></div>
            <small class="text-muted">Waiting <?= time_ago($t['when']) ?></small>
          </div>
          <a class="btn btn-sm mo-btn" href="<?= url($t['href']) ?>"><?= $t['btn'] ?></a>
        </div>
      <?php endforeach; endif; ?>
    </div>
  </div>

  <div class="col-lg-6">
    <div class="mo-card p-4 h-100">
      <h2 class="h6 fw-bold text-uppercase text-muted mb-3">Today's QR collections</h2>
      <?php if (!$todayCollections): ?>
        <div class="mo-empty py-4"><i class="bi bi-qr-code-scan"></i>No collections yet today</div>
      <?php else: foreach ($todayCollections as $c): ?>
        <div class="d-flex justify-content-between py-2 border-bottom small">
          <span><i class="bi bi-check-circle text-success me-1"></i><?= e($c['customer']) ?> — <?= e($c['note'] ?? 'collection') ?></span>
          <span class="text-muted"><?= date('g:ia', strtotime($c['created_at'])) ?></span>
        </div>
      <?php endforeach; endif; ?>

      <?php if ($lowStock): ?>
        <h2 class="h6 fw-bold text-uppercase text-muted mt-4 mb-2">Low stock alerts</h2>
        <?php foreach ($lowStock as $p): ?>
          <div class="d-flex justify-content-between py-1 small">
            <span class="text-truncate me-2"><?= e($p['name']) ?></span>
            <span class="mo-status <?= $p['stock'] == 0 ? 'mo-status-cancelled' : 'mo-status-pending' ?>"><?= (int)$p['stock'] ?> left</span>
          </div>
        <?php endforeach; ?>
      <?php endif; ?>
    </div>
  </div>
</div>
<?php require_once __DIR__ . '/../components/staff-footer.php'; ?>

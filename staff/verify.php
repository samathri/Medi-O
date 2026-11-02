<?php
/**
 * Landing page for scanned QR tokens.
 *   t=u:<token>  → customer personal QR: show ready orders + approved prescriptions
 *   t=o:<token>  → order QR: show that order for collection
 * Handles the "mark collected" actions with a full audit trail.
 */
$pageTitle = 'QR verification';
require_once __DIR__ . '/../config.php';
$staff = require_staff();
require_once dirname(__DIR__) . '/includes/order-status.php';
require_once dirname(__DIR__) . '/includes/whatsapp.php';

$raw = trim($_GET['t'] ?? $_POST['t'] ?? '');
$kind = null;
$token = '';
if (preg_match('/^([uo]):([a-f0-9]{40})$/', $raw, $m)) {
    [, $kind, $token] = $m;
} elseif (preg_match('/^[a-f0-9]{40}$/', $raw)) {
    // Bare token — try user first, then order
    if (q_one('SELECT id FROM users WHERE qr_token=?', [$raw])) $kind = 'u';
    elseif (q_one('SELECT id FROM orders WHERE qr_token=?', [$raw])) $kind = 'o';
    if (!$kind && q_one('SELECT id FROM prescriptions WHERE verify_token=?', [$raw])) {
        redirect('verify-prescription.php?t=' . urlencode($raw));
    }
    $token = $raw;
}

/* ---------- Collection actions ---------- */
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    csrf_check();
    $action = $_POST['action'] ?? '';

    if ($action === 'collect_order') {
        $order = q_one('SELECT o.*, u.name AS customer, u.phone, u.whatsapp_opt_in, u.id AS uid
                        FROM orders o JOIN users u ON u.id=o.user_id WHERE o.id=?', [(int)$_POST['order_id']]);
        if ($order && transition_order((int)$order['id'], 'collected', (int)$staff['id'])) {
            notify((int)$order['uid'], 'order', 'Order #' . $order['order_no'] . ' collected',
                'Thanks for shopping with Medi-O. Get well soon!', url('order.php?id=' . $order['id']));
            if ($order['phone'] && $order['whatsapp_opt_in']) {
                wa_send((int)$order['uid'], $order['phone'],
                    "Medi-O: Your order #{$order['order_no']} was collected. Thank you! 💊");
            }
            flash('success', 'Order #' . $order['order_no'] . ' marked as collected.');
        } else {
            flash('error', 'Order cannot be collected (already collected or cancelled).');
        }
        redirect('staff/verify.php?t=' . urlencode($raw));
    }

    if ($action === 'collect_rx') {
        $rx = q_one('SELECT p.*, u.name AS customer, u.phone, u.whatsapp_opt_in FROM prescriptions p
                     JOIN users u ON u.id=p.user_id WHERE p.id=?', [(int)$_POST['rx_id']]);
        if ($rx && in_array($rx['status'], ['approved', 'ready'], true)) {
            q("UPDATE prescriptions SET status='collected' WHERE id=?", [$rx['id']]);
            q('INSERT INTO collections (user_id, staff_id, prescription_id, note) VALUES (?,?,?,?)',
                [$rx['user_id'], $staff['id'], $rx['id'], 'Prescription medicines handed over via QR']);
            notify((int)$rx['user_id'], 'prescription', 'Prescription medicines collected',
                'Your prescription was fulfilled at the counter.', url('prescriptions.php'));
            if ($rx['phone'] && $rx['whatsapp_opt_in']) {
                wa_send((int)$rx['user_id'], $rx['phone'],
                    "Medi-O: Your prescription medicines were handed over. Get well soon! 💊");
            }
            flash('success', 'Prescription marked as collected.');
        } else {
            flash('error', 'Prescription is not in a collectable state.');
        }
        redirect('staff/verify.php?t=' . urlencode($raw));
    }
}

require_once __DIR__ . '/../components/staff-header.php';

/* ---------- Lookups ---------- */
$customer = null;
$orders = [];
$prescriptions = [];
$singleOrder = null;

if ($kind === 'u') {
    $customer = q_one('SELECT * FROM users WHERE qr_token=?', [$token]);
    if ($customer) {
        $orders = q_all("SELECT * FROM orders WHERE user_id=? AND status IN ('placed','processing','ready')
                         ORDER BY id DESC", [$customer['id']]);
        $prescriptions = q_all("SELECT * FROM prescriptions WHERE user_id=? AND status IN ('approved','ready')
                                ORDER BY id DESC", [$customer['id']]);
    }
} elseif ($kind === 'o') {
    $singleOrder = q_one('SELECT o.*, u.name AS customer_name, u.phone AS customer_phone
                          FROM orders o JOIN users u ON u.id=o.user_id WHERE o.qr_token=?', [$token]);
}
?>
<div class="d-flex align-items-center gap-2 mb-4">
  <a href="<?= url('staff/scan.php') ?>" class="btn mo-btn-soft btn-sm"><i class="bi bi-arrow-left"></i></a>
  <h1 class="h3 fw-bold mb-0">QR verification</h1>
</div>

<?php if (!$kind || ($kind === 'u' && !$customer) || ($kind === 'o' && !$singleOrder)): ?>
  <div class="mo-card p-5 text-center" style="max-width:520px">
    <i class="bi bi-shield-x text-danger" style="font-size:3rem"></i>
    <h2 class="h5 fw-bold mt-3">Invalid or unknown QR code</h2>
    <p class="text-muted">This code doesn't match any customer or order. Ask the customer to open
      their QR card in their Medi-O account.</p>
    <a class="btn mo-btn" href="<?= url('staff/scan.php') ?>">Scan again</a>
  </div>

<?php elseif ($kind === 'u'): ?>
  <div class="mo-card p-4 mb-4" style="max-width:760px">
    <div class="d-flex flex-wrap align-items-center gap-3">
      <div class="mo-avatar-btn" style="width:64px;height:64px;font-size:1.5rem;pointer-events:none">
        <?php if ($customer['avatar']): ?><img src="<?= e($customer['avatar']) ?>" alt=""><?php else: ?><span><?= e(strtoupper(mb_substr($customer['name'], 0, 1))) ?></span><?php endif; ?>
      </div>
      <div class="flex-grow-1">
        <h2 class="h5 fw-bold mb-0"><i class="bi bi-patch-check-fill text-success me-1"></i><?= e($customer['name']) ?></h2>
        <div class="small text-muted"><?= e($customer['email'] ?? '') ?> · <?= e(pretty_phone($customer['phone'])) ?></div>
      </div>
      <?php if ($customer['phone']): ?>
        <a class="btn btn-outline-success btn-sm" target="_blank" rel="noopener" href="<?= wa_link($customer['phone'], 'Hello from Medi-O Pharmacy!') ?>">
          <i class="bi bi-whatsapp me-1"></i>WhatsApp</a>
      <?php endif; ?>
    </div>
  </div>

  <div class="row g-4" style="max-width:1100px">
    <div class="col-lg-6">
      <h3 class="h6 fw-bold text-uppercase text-muted">Orders to hand over</h3>
      <?php if (!$orders): ?>
        <div class="mo-card mo-empty py-4"><i class="bi bi-box-seam"></i>No pending orders</div>
      <?php else: foreach ($orders as $o): ?>
        <div class="mo-card p-3 mb-3">
          <div class="d-flex justify-content-between align-items-center flex-wrap gap-2">
            <div>
              <div class="fw-bold">#<?= e($o['order_no']) ?> · <?= money((float)$o['total']) ?></div>
              <div class="small text-muted"><?= date('j M', strtotime($o['created_at'])) ?> ·
                <span class="mo-status mo-status-<?= e($o['status']) ?>"><?= ucfirst(e($o['status'])) ?></span>
                <?php if ($o['payment_status'] !== 'paid'): ?>
                  <span class="mo-status mo-status-pending ms-1"><i class="bi bi-cash me-1"></i>Collect <?= money((float)$o['total']) ?> (<?= e($o['payment_method']) ?>)</span>
                <?php else: ?>
                  <span class="mo-status mo-status-paid ms-1">Paid</span>
                <?php endif; ?>
              </div>
            </div>
            <form method="post">
              <?= csrf_field() ?>
              <input type="hidden" name="t" value="<?= e($raw) ?>">
              <input type="hidden" name="action" value="collect_order">
              <input type="hidden" name="order_id" value="<?= $o['id'] ?>">
              <button class="btn mo-btn btn-sm"><i class="bi bi-check2-circle me-1"></i>Hand over</button>
            </form>
          </div>
          <?php foreach (q_all('SELECT * FROM order_items WHERE order_id=?', [$o['id']]) as $it): ?>
            <div class="small text-muted mt-1">• <?= e($it['product_name']) ?> × <?= (int)$it['quantity'] ?></div>
          <?php endforeach; ?>
        </div>
      <?php endforeach; endif; ?>
    </div>

    <div class="col-lg-6">
      <h3 class="h6 fw-bold text-uppercase text-muted">Approved prescriptions</h3>
      <?php if (!$prescriptions): ?>
        <div class="mo-card mo-empty py-4"><i class="bi bi-file-medical"></i>Nothing awaiting collection</div>
      <?php else: foreach ($prescriptions as $rx): ?>
        <div class="mo-card p-3 mb-3 d-flex justify-content-between align-items-center flex-wrap gap-2">
          <div>
            <div class="fw-bold text-truncate" style="max-width:240px"><?= e($rx['original_name']) ?></div>
            <div class="small text-muted">Uploaded <?= time_ago($rx['created_at']) ?> ·
              <span class="mo-status mo-status-<?= e($rx['status']) ?>"><?= ucfirst(e($rx['status'])) ?></span></div>
          </div>
          <div class="d-flex gap-2">
            <a class="btn mo-btn-soft btn-sm" href="<?= url('file.php?rx=' . $rx['id']) ?>" target="_blank"><i class="bi bi-eye"></i></a>
            <form method="post">
              <?= csrf_field() ?>
              <input type="hidden" name="t" value="<?= e($raw) ?>">
              <input type="hidden" name="action" value="collect_rx">
              <input type="hidden" name="rx_id" value="<?= $rx['id'] ?>">
              <button class="btn mo-btn btn-sm"><i class="bi bi-check2-circle me-1"></i>Hand over</button>
            </form>
          </div>
        </div>
      <?php endforeach; endif; ?>
    </div>
  </div>

<?php else: /* single order */ ?>
  <div class="mo-card p-4" style="max-width:640px">
    <div class="d-flex justify-content-between align-items-center flex-wrap gap-2 mb-3">
      <div>
        <h2 class="h5 fw-bold mb-0"><i class="bi bi-patch-check-fill text-success me-1"></i>Order #<?= e($singleOrder['order_no']) ?></h2>
        <div class="small text-muted"><?= e($singleOrder['customer_name']) ?> · <?= e(pretty_phone($singleOrder['customer_phone'])) ?></div>
      </div>
      <span class="mo-status mo-status-<?= e($singleOrder['status']) ?> fs-6"><?= ucfirst(e($singleOrder['status'])) ?></span>
    </div>
    <?php foreach (q_all('SELECT * FROM order_items WHERE order_id=?', [$singleOrder['id']]) as $it): ?>
      <div class="d-flex justify-content-between small py-1 border-bottom">
        <span><?= e($it['product_name']) ?> × <?= (int)$it['quantity'] ?></span>
        <span><?= money($it['price'] * $it['quantity']) ?></span>
      </div>
    <?php endforeach; ?>
    <div class="d-flex justify-content-between fw-bold mt-2 mb-3">
      <span>Total<?= $singleOrder['payment_status'] !== 'paid' ? ' — collect payment' : ' (paid)' ?></span>
      <span><?= money((float)$singleOrder['total']) ?></span>
    </div>
    <?php if (in_array($singleOrder['status'], ['placed', 'processing', 'ready'], true)): ?>
      <form method="post">
        <?= csrf_field() ?>
        <input type="hidden" name="t" value="<?= e($raw) ?>">
        <input type="hidden" name="action" value="collect_order">
        <input type="hidden" name="order_id" value="<?= $singleOrder['id'] ?>">
        <button class="btn mo-btn w-100"><i class="bi bi-check2-circle me-2"></i>Mark as collected</button>
      </form>
    <?php elseif ($singleOrder['status'] === 'collected'): ?>
      <div class="alert alert-success mb-0"><i class="bi bi-check-circle me-2"></i>Already collected.</div>
    <?php else: ?>
      <div class="alert alert-secondary mb-0">Status: <?= ucfirst(e($singleOrder['status'])) ?></div>
    <?php endif; ?>
  </div>
<?php endif; ?>
<?php require_once __DIR__ . '/../components/staff-footer.php'; ?>

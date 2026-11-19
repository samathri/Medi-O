<?php
$pageTitle = 'Hand over';
require_once __DIR__ . '/../components/staff-header.php';
$ready = q_all("SELECT o.order_no, o.qr_token, o.total, o.payment_method, o.payment_status, u.name, u.phone
                FROM orders o JOIN users u ON u.id=o.user_id
                WHERE o.status='ready' AND o.delivery_method='pickup' ORDER BY o.id");
?>
<div class="d-flex flex-wrap justify-content-between align-items-center mb-4">
  <h1 class="h3 fw-bold mb-0">Hand over an order</h1>
</div>

<div class="row g-4">
  <div class="col-lg-6">
    <div class="mo-card p-4">
      <h2 class="h6 fw-bold text-uppercase text-muted mb-2">Scan the customer's QR</h2>
      <p id="scannerStatus" class="small text-muted" role="status" aria-live="polite">Starting the camera… point it at the customer's QR card or receipt.</p>
      <button id="startScanner" type="button" class="btn mo-btn mb-3">Start camera</button>
      <div id="reader" class="rounded-3 overflow-hidden" style="max-width:480px;margin:auto"></div>
    </div>
  </div>
  <div class="col-lg-6">
    <div class="mo-card p-4">
      <h2 class="h6 fw-bold text-uppercase text-muted mb-1">No QR? Find the order here</h2>
      <p class="small text-muted">Orders ready for pickup — ask the customer for their name or phone number.</p>
      <?php if ($ready): ?>
        <input type="search" class="form-control mb-2" placeholder="Type a name, phone or order no…"
               oninput="const q=this.value.toLowerCase();document.querySelectorAll('.ready-row').forEach(r=>r.hidden=!r.dataset.k.includes(q))">
      <?php else: ?>
        <div class="mo-empty py-3"><i class="bi bi-bag-check"></i>No orders waiting for pickup</div>
      <?php endif; ?>
      <?php foreach ($ready as $r): ?>
        <div class="ready-row d-flex align-items-center gap-2 py-2 border-bottom"
             data-k="<?= e(strtolower($r['name'] . ' ' . $r['phone'] . ' ' . pretty_phone((string)$r['phone']) . ' ' . $r['order_no'])) ?>">
          <div class="flex-grow-1">
            <div class="fw-semibold small"><?= e($r['name']) ?> · #<?= e($r['order_no']) ?></div>
            <small class="text-muted"><?= e(pretty_phone((string)$r['phone'])) ?> · <?= money((float)$r['total']) ?>
              <?= $r['payment_status'] === 'paid' ? '· paid' : '· <b class="text-danger">collect cash</b>' ?></small>
          </div>
          <a class="btn btn-sm mo-btn" href="<?= url('staff/verify.php?t=o:' . $r['qr_token']) ?>">Hand over</a>
        </div>
      <?php endforeach; ?>
      <details class="mt-3 small"><summary class="text-muted">Type a code from a QR instead</summary>
        <form id="manualQrForm" class="d-flex gap-2 mt-2">
          <input class="form-control" name="code" placeholder="Paste scanned link or code…">
          <button class="btn mo-btn">Open</button>
        </form>
      </details>
    </div>
  </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/html5-qrcode@2.3.8/html5-qrcode.min.js" defer></script>
<script src="<?= url('assets/js/qr-scanner.js') ?>" defer></script>
<script>window.addEventListener('load', () => document.getElementById('startScanner')?.click());</script>
<?php require_once __DIR__ . '/../components/staff-footer.php'; ?>

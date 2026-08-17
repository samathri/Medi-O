<?php
require_once __DIR__ . '/config.php';
require_once __DIR__ . '/includes/qr.php';
$u = require_login();

$qr = qr_data_uri(url('staff/verify.php?t=u:' . $u['qr_token']), 300);

$pageTitle = 'My QR card';
include __DIR__ . '/components/header.php';
?>
<div class="container py-5">
  <div class="text-center mb-4" data-reveal>
    <h1 class="h2 fw-bold">My QR card</h1>
    <p class="text-muted mx-auto" style="max-width:520px">This is your permanent personal QR code. Show it at
      the pharmacy counter — the pharmacist scans it to instantly see your ready orders and approved
      prescriptions, and hands over your medicines. No queue, no paperwork.</p>
  </div>

  <div class="mo-qr-card" data-reveal>
    <div class="d-flex align-items-center justify-content-center gap-2 mb-3">
      <i class="bi bi-capsule fs-4"></i><span class="fw-bold fs-5">Medi-O</span>
    </div>
    <img src="<?= $qr ?>" alt="Your personal QR code">
    <h2 class="h5 fw-bold mt-3 mb-0"><?= e($u['name']) ?></h2>
    <div class="small opacity-75">Member since <?= date('M Y', strtotime($u['created_at'])) ?></div>
    <div class="small mt-2 opacity-75"><i class="bi bi-shield-check me-1"></i>Personal collection pass</div>
  </div>

  <div class="text-center mt-4 no-print" data-reveal>
    <button class="btn mo-btn" onclick="window.print()"><i class="bi bi-printer me-2"></i>Print / save card</button>
    <div class="small text-muted mt-3"><i class="bi bi-info-circle me-1"></i>Tip: screenshot this page and keep
      it in your phone's gallery for quick access.</div>
  </div>
</div>
<?php include __DIR__ . '/components/footer.php'; ?>

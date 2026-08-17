<?php
/**
 * Public prescription authenticity check (opened by scanning the QR on a
 * prescription). Shows only validity + status — no personal data.
 */
require_once __DIR__ . '/config.php';

$token = $_GET['t'] ?? '';
$rx = preg_match('/^[a-f0-9]{40}$/', $token)
    ? q_one('SELECT status, created_at, reviewed_at FROM prescriptions WHERE verify_token = ?', [$token])
    : null;

$pageTitle = 'Prescription verification';
include __DIR__ . '/components/header.php';
?>
<div class="container py-5">
  <div class="mo-card mo-auth-card p-4 p-md-5 text-center" data-reveal>
    <?php if (!$rx): ?>
      <i class="bi bi-shield-x text-danger" style="font-size:3.4rem"></i>
      <h1 class="h4 fw-bold mt-3">Not a valid Medi-O prescription</h1>
      <p class="text-muted">This QR code doesn't match any prescription in our system. It may be forged
        or the record was removed.</p>
    <?php else: ?>
      <i class="bi bi-shield-check text-success" style="font-size:3.4rem"></i>
      <h1 class="h4 fw-bold mt-3">Genuine Medi-O prescription</h1>
      <p class="text-muted mb-4">This prescription is registered in the Medi-O pharmacy system.</p>
      <div class="d-flex justify-content-center gap-4 small">
        <div><div class="text-muted">Status</div><span class="mo-status mo-status-<?= e($rx['status']) ?>"><?= ucfirst(e($rx['status'])) ?></span></div>
        <div><div class="text-muted">Uploaded</div><strong><?= date('j M Y', strtotime($rx['created_at'])) ?></strong></div>
        <?php if ($rx['reviewed_at']): ?>
          <div><div class="text-muted">Reviewed</div><strong><?= date('j M Y', strtotime($rx['reviewed_at'])) ?></strong></div>
        <?php endif; ?>
      </div>
    <?php endif; ?>
  </div>
</div>
<?php include __DIR__ . '/components/footer.php'; ?>

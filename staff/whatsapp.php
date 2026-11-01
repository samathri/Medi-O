<?php
$pageTitle = 'WhatsApp';
require_once __DIR__ . '/../components/staff-header.php';
require_once dirname(__DIR__) . '/includes/whatsapp.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    csrf_check();
    $action = $_POST['action'] ?? '';

    if ($action === 'send') {
        $userId = (int)($_POST['user_id'] ?? 0);
        $message = trim($_POST['message'] ?? '');
        $customer = q_one('SELECT * FROM users WHERE id=?', [$userId]);
        if (!$customer || !$customer['phone'] || $message === '') {
            flash('error', 'Pick a customer with a phone number and write a message.');
        } else {
            wa_send($userId, $customer['phone'], $message);
            flash('success', wa_configured()
                ? 'Message sent via WhatsApp Cloud API.'
                : 'Message logged to the outbox (demo mode) — use the wa.me button to send it manually.');
        }
        redirect('staff/whatsapp.php');
    }

    if ($action === 'retry') {
        wa_dispatch((int)$_POST['id']);
        flash('info', 'Retried delivery.');
        redirect('staff/whatsapp.php');
    }
}

$customers = q_all("SELECT id, name, phone FROM users WHERE role='customer' AND phone IS NOT NULL ORDER BY name");
$outbox = q_all('SELECT w.*, u.name AS customer FROM whatsapp_outbox w
                 LEFT JOIN users u ON u.id = w.user_id ORDER BY w.id DESC LIMIT 60');
?>
<div class="d-flex flex-wrap justify-content-between align-items-center gap-2 mb-4">
  <h1 class="h3 fw-bold mb-0">WhatsApp messaging</h1>
  <?php [$waOk, $waMsg] = wa_health(); ?>
  <span class="mo-status <?= $waOk ? 'mo-status-sent' : (wa_configured() ? 'mo-status-failed' : 'mo-status-logged') ?>">
    <?= e($waOk ? $waMsg : (wa_configured() ? '⚠ ' . $waMsg : 'Demo mode — messages are logged')) ?></span>
</div>

<?php if (!wa_configured()): ?>
  <div class="alert alert-info small">
    <i class="bi bi-info-circle me-1"></i><strong>Demo mode.</strong> To send real WhatsApp messages for free,
    create a Meta developer app (developers.facebook.com → WhatsApp → API Setup), then put
    <code>WHATSAPP_TOKEN</code> and <code>WHATSAPP_PHONE_ID</code> into <code>config.local.php</code>.
    Until then, messages are stored below and you can deliver them manually with the
    <i class="bi bi-whatsapp text-success"></i> button (opens WhatsApp Web with the text pre-filled).
  </div>
<?php endif; ?>

<div class="row g-4">
  <div class="col-lg-4">
    <div class="mo-card p-4">
      <h2 class="h6 fw-bold text-uppercase text-muted mb-3">Send a message</h2>
      <form method="post">
        <?= csrf_field() ?>
        <input type="hidden" name="action" value="send">
        <div class="mb-3">
          <label class="form-label fw-semibold">Customer</label>
          <select name="user_id" class="form-select" required>
            <option value="">Choose…</option>
            <?php foreach ($customers as $c): ?>
              <option value="<?= $c['id'] ?>"><?= e($c['name']) ?> — <?= e(pretty_phone($c['phone'])) ?></option>
            <?php endforeach; ?>
          </select>
        </div>
        <div class="mb-3">
          <label class="form-label fw-semibold">Message</label>
          <textarea name="message" class="form-control" rows="4" required
            placeholder="e.g. Your order is ready for pickup! Show your QR card at the counter."></textarea>
        </div>
        <button class="btn mo-btn w-100"><i class="bi bi-whatsapp me-2"></i>Send</button>
      </form>
    </div>
  </div>

  <div class="col-lg-8">
    <div class="mo-card p-4">
      <h2 class="h6 fw-bold text-uppercase text-muted mb-3">Outbox / delivery log</h2>
      <?php if (!$outbox): ?>
        <div class="mo-empty py-4"><i class="bi bi-whatsapp"></i>No messages yet</div>
      <?php else: ?>
        <div class="table-responsive">
          <table class="table mo-table table-hover align-middle">
            <thead><tr><th>To</th><th>Message</th><th>Status</th><th>When</th><th></th></tr></thead>
            <tbody>
              <?php foreach ($outbox as $w): ?>
                <tr>
                  <td>
                    <div class="fw-semibold"><?= e($w['customer'] ?? '—') ?></div>
                    <small class="text-muted"><?= e(pretty_phone($w['phone'])) ?></small>
                  </td>
                  <td class="small" style="max-width:320px"><?= e(mb_strimwidth($w['message'], 0, 120, '…')) ?></td>
                  <td><span class="mo-status mo-status-<?= e($w['status']) ?>"><?= ucfirst(e($w['status'])) ?></span></td>
                  <td class="small text-muted"><?= time_ago($w['created_at']) ?></td>
                  <td class="text-nowrap">
                    <a class="btn btn-sm btn-outline-success" target="_blank" rel="noopener"
                       href="<?= wa_link($w['phone'], $w['message']) ?>" title="Open in WhatsApp"><i class="bi bi-whatsapp"></i></a>
                    <?php if (in_array($w['status'], ['failed', 'queued'], true) && wa_configured()): ?>
                      <form method="post" class="d-inline"><?= csrf_field() ?>
                        <input type="hidden" name="action" value="retry"><input type="hidden" name="id" value="<?= $w['id'] ?>">
                        <button class="btn btn-sm mo-btn-soft" title="Retry"><i class="bi bi-arrow-repeat"></i></button></form>
                    <?php endif; ?>
                  </td>
                </tr>
              <?php endforeach; ?>
            </tbody>
          </table>
        </div>
      <?php endif; ?>
    </div>
  </div>
</div>
<?php require_once __DIR__ . '/../components/staff-footer.php'; ?>

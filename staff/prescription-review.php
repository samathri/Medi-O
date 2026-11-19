<?php
$pageTitle = 'Review prescription';
$active = 'prescriptions.php';
require_once __DIR__ . '/../components/staff-header.php';
require_once dirname(__DIR__) . '/includes/ai.php';
require_once dirname(__DIR__) . '/includes/mailer.php';

$rxId = (int)($_GET['id'] ?? $_POST['rx_id'] ?? 0);
$rx = q_one('SELECT p.*, u.name AS customer, u.email, u.phone, u.id AS uid
             FROM prescriptions p JOIN users u ON u.id = p.user_id WHERE p.id = ?', [$rxId]);
if (!$rx) {
    flash('error', 'Prescription not found.');
    redirect('staff/prescriptions.php');
}

/** Save the submitted item rows. Returns number of items saved. */
function save_items(int $rxId): int
{
    q('DELETE FROM prescription_items WHERE prescription_id=?', [$rxId]);
    $names = $_POST['item_name'] ?? [];
    $count = 0;
    foreach ($names as $i => $name) {
        $name = trim((string)$name);
        if ($name === '') continue;
        $pid = (int)($_POST['item_product'][$i] ?? 0);
        $stock = $pid ? (int)q_val('SELECT stock FROM products WHERE id=? AND is_active=1', [$pid]) : 0;
        $times = implode(',', array_filter(
            array_map('trim', explode(',', (string)($_POST['item_times'][$i] ?? ''))),
            fn($t) => preg_match('/^\d{1,2}:\d{2}$/', $t)
        ));
        $days = (int)($_POST['item_days'][$i] ?? 0);
        q('INSERT INTO prescription_items
             (prescription_id, product_id, medicine_name, dosage, times, duration_days, instructions, in_stock)
           VALUES (?,?,?,?,?,?,?,?)', [
            $rxId, $pid ?: null, mb_strimwidth($name, 0, 250),
            mb_strimwidth(trim((string)($_POST['item_dosage'][$i] ?? '')), 0, 115) ?: null,
            $times ?: null,
            $days > 0 ? min(365, $days) : null,
            mb_strimwidth(trim((string)($_POST['item_instr'][$i] ?? '')), 0, 250) ?: null,
            $stock > 0 ? 1 : 0,
        ]);
        $count++;
    }
    return $count;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    csrf_check();
    $action = $_POST['action'] ?? '';

    if ($action === 'analyze') {
        [$ok, $msg] = ai_analyze_prescription($rxId);
        flash($ok ? 'success' : 'error', $msg);
        redirect('staff/prescription-review.php?id=' . $rxId);
    }

    if ($action === 'save') {
        $n = save_items($rxId);
        flash('success', "Draft saved ({$n} item" . ($n === 1 ? '' : 's') . ').');
        redirect('staff/prescription-review.php?id=' . $rxId);
    }

    if (in_array($action, ['approved', 'ready', 'rejected'], true)) {
        $n = save_items($rxId);
        $note = trim($_POST['review_note'] ?? '');
        q('UPDATE prescriptions SET status=?, pharmacist_id=?, review_note=?, reviewed_at=NOW() WHERE id=?',
            [$action, $staff['id'], $note ?: null, $rxId]);

        // Optionally turn the verified plan into medication reminders for the patient
        $scheduled = 0;
        if ($action !== 'rejected' && !empty($_POST['create_schedule'])) {
            foreach (q_all('SELECT * FROM prescription_items WHERE prescription_id=?', [$rxId]) as $it) {
                $times = array_filter(explode(',', (string)$it['times']));
                if (!$times) continue;
                q('INSERT INTO medications (user_id, prescription_id, name, dosage, instructions, start_date, end_date, created_by)
                   VALUES (?,?,?,?,?,CURDATE(),?,?)', [
                    $rx['uid'], $rxId, $it['medicine_name'], $it['dosage'], $it['instructions'],
                    $it['duration_days'] ? date('Y-m-d', strtotime('+' . ((int)$it['duration_days'] - 1) . ' days')) : null,
                    $staff['id'],
                ]);
                $medId = (int)db()->lastInsertId();
                foreach ($times as $t) {
                    q('INSERT INTO medication_times (medication_id, time_of_day) VALUES (?,?)',
                        [$medId, (strlen($t) === 4 ? '0' : '') . $t . ':00']);
                }
                $scheduled++;
            }
        }

        $messages = [
            'approved' => ['Prescription approved ✅', 'Your prescription was verified by our pharmacist.' . ($n ? " Your medication plan ({$n} medicines) is now in your account." : '') . ($note ? ' Note: ' . $note : '')],
            'ready'    => ['Your medicines are ready 💊', 'Your prescription order is packed and ready. Show your QR card at the counter to collect.' . ($note ? ' ' . $note : '')],
            'rejected' => ['Prescription needs attention ❌', $note ?: 'We could not verify this prescription. Please contact us or upload a clearer copy.'],
        ];
        [$title, $body] = $messages[$action];
        alert_user((int)$rx['uid'], 'prescription', $title, $body, url('prescriptions.php'), 'View my prescriptions');

        flash('success', 'Prescription marked ' . $action . ($scheduled ? " — {$scheduled} medication reminder plan(s) created for the customer." : '') . ' Customer notified.');
        redirect('staff/prescriptions.php');
    }
}

$items = q_all('SELECT pi.*, p.name AS product_name, p.stock AS product_stock, p.price
                FROM prescription_items pi LEFT JOIN products p ON p.id = pi.product_id
                WHERE pi.prescription_id = ? ORDER BY pi.id', [$rxId]);
$products = q_all('SELECT id, name, stock FROM products WHERE is_active=1 ORDER BY name');
$isImage = preg_match('/\.(jpe?g|png|webp)$/i', $rx['file_path']);
?>
<div class="d-flex align-items-center gap-2 mb-4 flex-wrap">
  <a href="<?= url('staff/prescriptions.php') ?>" class="btn mo-btn-soft btn-sm"><i class="bi bi-arrow-left"></i></a>
  <h1 class="h3 fw-bold mb-0">Review prescription</h1>
  <span class="mo-status mo-status-<?= e($rx['status']) ?>"><?= ucfirst(e($rx['status'])) ?></span>
</div>

<div class="row g-4">
  <!-- Left: the document -->
  <div class="col-lg-5">
   <div class="rx-sticky">
    <div class="mo-card p-3 mb-3">
      <div class="d-flex justify-content-between align-items-center mb-2 flex-wrap gap-2">
        <div>
          <div class="fw-bold"><?= e($rx['customer']) ?></div>
          <small class="text-muted"><?= e(pretty_phone($rx['phone'])) ?> · uploaded <?= time_ago($rx['created_at']) ?></small>
        </div>
        <a class="btn btn-sm mo-btn-soft" href="<?= url('file.php?rx=' . $rx['id']) ?>" target="_blank"><i class="bi bi-box-arrow-up-right me-1"></i>Open full</a>
      </div>
      <?php if ($rx['notes']): ?>
        <div class="small bg-light rounded-2 p-2 mb-2"><i class="bi bi-chat-left-text me-1"></i>Customer: <?= e($rx['notes']) ?></div>
      <?php endif; ?>
      <?php if ($isImage): ?>
        <img src="<?= url('file.php?rx=' . $rx['id']) ?>" class="w-100 rounded-3 border" alt="Prescription">
      <?php else: ?>
        <iframe src="<?= url('file.php?rx=' . $rx['id']) ?>" class="w-100 rounded-3 border" style="height:560px" title="Prescription PDF"></iframe>
      <?php endif; ?>
    </div>

    <div class="mo-card p-3">
      <div class="d-flex justify-content-between align-items-center mb-2">
        <h2 class="h6 fw-bold text-uppercase text-muted mb-0"><i class="bi bi-stars me-1" style="color:var(--mo-accent)"></i>AI assistant</h2>
        <form method="post" id="aiForm">
          <?= csrf_field() ?>
          <input type="hidden" name="rx_id" value="<?= $rxId ?>">
          <button name="action" value="analyze" class="btn btn-sm mo-btn" <?= ai_configured() ? '' : 'disabled' ?>
                  onclick="this.innerHTML='<span class=\'spinner-border spinner-border-sm me-1\'></span>Reading…'">
            <i class="bi bi-magic me-1"></i><?= $rx['ai_analyzed_at'] ? 'Re-analyze' : 'Read prescription' ?>
          </button>
        </form>
      </div>
      <?php if (!ai_configured()): ?>
        <div class="alert alert-secondary small mb-0">AI reader not configured. Get a free key at
          <strong>aistudio.google.com/apikey</strong> and add <code>GEMINI_API_KEY</code> to
          <code>config.local.php</code>. You can still fill the plan manually →</div>
      <?php elseif ($rx['ai_summary']): ?>
        <div class="small" style="white-space:pre-line"><?= e($rx['ai_summary']) ?></div>
        <div class="small text-muted mt-2"><i class="bi bi-clock me-1"></i>Analyzed <?= time_ago($rx['ai_analyzed_at']) ?> —
          AI output is a draft; verify every line against the document.</div>
      <?php else: ?>
        <div class="small text-muted">Click <strong>Read prescription</strong> — the AI extracts each medicine,
          matches it to stock, and drafts the schedule for you to verify.</div>
      <?php endif; ?>
    </div>
   </div>
  </div>

  <!-- Right: the medication plan -->
  <div class="col-lg-7">
    <form method="post" id="planForm">
      <?= csrf_field() ?>
      <input type="hidden" name="rx_id" value="<?= $rxId ?>">
      <div class="mo-card p-3 p-md-4">
        <h2 class="h6 fw-bold text-uppercase text-muted mb-3">Medication plan
          <span class="fw-normal text-lowercase">— verify &amp; correct each line</span></h2>

        <div id="itemRows" class="d-flex flex-column gap-3">
          <?php foreach ($items as $i => $it): ?>
            <div class="border rounded-3 p-3 item-row">
              <div class="row g-2 align-items-end">
                <div class="col-md-6">
                  <label class="form-label small fw-semibold mb-1">Medicine (as prescribed)</label>
                  <input name="item_name[]" class="form-control form-control-sm" value="<?= e($it['medicine_name']) ?>" required>
                </div>
                <div class="col-md-6">
                  <label class="form-label small fw-semibold mb-1">Match in stock
                    <?php if ($it['product_id']): ?>
                      <span class="mo-status <?= $it['product_stock'] > 0 ? 'mo-status-ready' : 'mo-status-cancelled' ?>">
                        <?= $it['product_stock'] > 0 ? $it['product_stock'] . ' in stock' : 'OUT OF STOCK' ?></span>
                    <?php else: ?>
                      <span class="mo-status mo-status-pending">no match</span>
                    <?php endif; ?>
                  </label>
                  <select name="item_product[]" class="form-select form-select-sm">
                    <option value="">— not in our stock —</option>
                    <?php foreach ($products as $p): ?>
                      <option value="<?= $p['id'] ?>" <?= $it['product_id'] == $p['id'] ? 'selected' : '' ?>>
                        <?= e(mb_strimwidth($p['name'], 0, 60, '…')) ?> (<?= (int)$p['stock'] ?>)</option>
                    <?php endforeach; ?>
                  </select>
                </div>
                <div class="col-6 col-md-3">
                  <label class="form-label small fw-semibold mb-1">Dosage</label>
                  <input name="item_dosage[]" class="form-control form-control-sm" value="<?= e($it['dosage'] ?? '') ?>" placeholder="1 tablet">
                </div>
                <div class="col-6 col-md-3">
                  <label class="form-label small fw-semibold mb-1">Times (HH:MM)</label>
                  <input name="item_times[]" class="form-control form-control-sm" value="<?= e($it['times'] ?? '') ?>" placeholder="08:00,20:00">
                </div>
                <div class="col-6 col-md-2">
                  <label class="form-label small fw-semibold mb-1">Days</label>
                  <input type="number" name="item_days[]" min="1" max="365" class="form-control form-control-sm" value="<?= e($it['duration_days'] ?? '') ?>">
                </div>
                <div class="col-6 col-md-3">
                  <label class="form-label small fw-semibold mb-1">Instructions</label>
                  <input name="item_instr[]" class="form-control form-control-sm" value="<?= e($it['instructions'] ?? '') ?>" placeholder="after meals">
                </div>
                <div class="col-12 col-md-1 text-end">
                  <button type="button" class="btn btn-sm btn-outline-danger remove-row" aria-label="Remove"><i class="bi bi-trash"></i></button>
                </div>
              </div>
            </div>
          <?php endforeach; ?>
        </div>

        <button type="button" class="btn btn-sm mo-btn-soft mt-3" id="addRow"><i class="bi bi-plus me-1"></i>Add medicine</button>
        <?php if (!$items): ?>
          <div class="mo-empty py-3" id="emptyHint"><i class="bi bi-clipboard-plus"></i>
            <div class="mb-3">No medicines added yet.</div>
            <?php if (ai_configured()): ?>
              <button form="aiForm" name="action" value="analyze" class="btn mo-btn"
                      onclick="this.innerHTML='<span class=\'spinner-border spinner-border-sm me-1\'></span>Reading the prescription…'">
                <i class="bi bi-magic me-1"></i>Read it with AI</button>
              <div class="small mt-2">or use <b>Add medicine</b> to type them in.</div>
            <?php else: ?>
              <div class="small">Use <b>Add medicine</b> to type them in.</div>
            <?php endif; ?>
          </div>
        <?php endif; ?>

        <hr>
        <div class="mb-3">
          <label class="form-label fw-semibold small">Note to customer (optional)</label>
          <input name="review_note" class="form-control" value="<?= e($rx['review_note'] ?? '') ?>"
                 placeholder="e.g. Amoxicillin substituted with generic brand">
        </div>
        <div class="form-check form-switch mb-3">
          <input class="form-check-input" type="checkbox" name="create_schedule" id="schedSwitch" value="1" checked>
          <label class="form-check-label small" for="schedSwitch">
            <i class="bi bi-alarm me-1"></i>On approval, create dose reminders (app + email) from this plan
          </label>
        </div>
        <div class="d-flex flex-wrap gap-2 rx-actions">
          <button name="action" value="save" class="btn mo-btn-soft"><i class="bi bi-save me-1"></i>Save draft</button>
          <?php if (in_array($rx['status'], ['pending', 'approved'], true)): ?>
            <button name="action" value="approved" class="btn mo-btn"><i class="bi bi-check-lg me-1"></i>Approve plan</button>
            <?php if ($rx['status'] === 'approved'): ?>
              <button name="action" value="ready" class="btn mo-btn"><i class="bi bi-bag-check me-1"></i>Mark ready for pickup</button>
            <?php endif; ?>
            <button name="action" value="rejected" class="btn btn-outline-danger"
                    onclick="return confirm('Reject this prescription?')">Reject</button>
          <?php endif; ?>
        </div>
      </div>
    </form>
  </div>
</div>

<template id="rowTpl">
  <div class="border rounded-3 p-3 item-row">
    <div class="row g-2 align-items-end">
      <div class="col-md-6">
        <label class="form-label small fw-semibold mb-1">Medicine (as prescribed)</label>
        <input name="item_name[]" class="form-control form-control-sm" required>
      </div>
      <div class="col-md-6">
        <label class="form-label small fw-semibold mb-1">Match in stock</label>
        <select name="item_product[]" class="form-select form-select-sm">
          <option value="">— not in our stock —</option>
          <?php foreach ($products as $p): ?>
            <option value="<?= $p['id'] ?>"><?= e(mb_strimwidth($p['name'], 0, 60, '…')) ?> (<?= (int)$p['stock'] ?>)</option>
          <?php endforeach; ?>
        </select>
      </div>
      <div class="col-6 col-md-3">
        <label class="form-label small fw-semibold mb-1">Dosage</label>
        <input name="item_dosage[]" class="form-control form-control-sm" placeholder="1 tablet">
      </div>
      <div class="col-6 col-md-3">
        <label class="form-label small fw-semibold mb-1">Times (HH:MM)</label>
        <input name="item_times[]" class="form-control form-control-sm" placeholder="08:00,20:00">
      </div>
      <div class="col-6 col-md-2">
        <label class="form-label small fw-semibold mb-1">Days</label>
        <input type="number" name="item_days[]" min="1" max="365" class="form-control form-control-sm">
      </div>
      <div class="col-6 col-md-3">
        <label class="form-label small fw-semibold mb-1">Instructions</label>
        <input name="item_instr[]" class="form-control form-control-sm" placeholder="after meals">
      </div>
      <div class="col-12 col-md-1 text-end">
        <button type="button" class="btn btn-sm btn-outline-danger remove-row" aria-label="Remove"><i class="bi bi-trash"></i></button>
      </div>
    </div>
  </div>
</template>

<script>
document.getElementById('addRow').addEventListener('click', () => {
  document.getElementById('emptyHint')?.remove();
  document.getElementById('itemRows').appendChild(
    document.getElementById('rowTpl').content.cloneNode(true));
});
document.addEventListener('click', e => {
  const btn = e.target.closest('.remove-row');
  if (btn) btn.closest('.item-row').remove();
});
</script>
<?php require_once __DIR__ . '/../components/staff-footer.php'; ?>

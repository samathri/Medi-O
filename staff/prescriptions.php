<?php
$pageTitle = 'Prescriptions';
require_once __DIR__ . '/../components/staff-header.php';
require_once dirname(__DIR__) . '/includes/whatsapp.php';
require_once dirname(__DIR__) . '/includes/mailer.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    csrf_check();
    $rx = q_one('SELECT p.*, u.name AS customer, u.email, u.phone, u.whatsapp_opt_in
                 FROM prescriptions p JOIN users u ON u.id=p.user_id WHERE p.id=?', [(int)$_POST['rx_id']]);
    $action = $_POST['action'] ?? '';
    $note = trim($_POST['note'] ?? '');

    if ($rx && in_array($action, ['approved', 'rejected', 'ready'], true)) {
        q('UPDATE prescriptions SET status=?, pharmacist_id=?, review_note=?, reviewed_at=NOW() WHERE id=?',
            [$action, $staff['id'], $note ?: $rx['review_note'], $rx['id']]);

        $messages = [
            'approved' => ['Prescription approved ✅', 'Your prescription was verified by our pharmacist. ' . ($note ?: 'You can now order the medicines or collect them at the counter.')],
            'rejected' => ['Prescription needs attention ❌', $note ?: 'Unfortunately we could not verify this prescription. Please contact us or upload a clearer copy.'],
            'ready'    => ['Your medicines are ready 💊', 'Your prescription order is packed and ready. Show your QR card at the counter to collect' . ($note ? ' — ' . $note : '.')],
        ];
        [$title, $body] = $messages[$action];

        notify((int)$rx['user_id'], 'prescription', $title, $body, url('prescriptions.php'));
        if ($rx['email']) {
            send_mail($rx['email'], $title, mail_template($title, '<p>' . e($body) . '</p>', 'View my prescriptions', url('prescriptions.php')));
        }
        if ($rx['phone'] && $rx['whatsapp_opt_in']) {
            wa_send((int)$rx['user_id'], $rx['phone'], "Medi-O: {$title}\n{$body}");
        }
        flash('success', 'Prescription marked as ' . $action . ' — customer notified.');
    }
    redirect('staff/prescriptions.php' . (isset($_POST['back_filter']) ? '?f=' . urlencode($_POST['back_filter']) : ''));
}

$filter = $_GET['f'] ?? 'pending';
$valid = ['pending', 'approved', 'ready', 'rejected', 'collected', 'all'];
if (!in_array($filter, $valid, true)) $filter = 'pending';

$where = $filter === 'all' ? '1' : 'p.status = ' . db()->quote($filter);
$rows = q_all("SELECT p.*, u.name AS customer, u.phone, u.email FROM prescriptions p
               JOIN users u ON u.id=p.user_id WHERE $where ORDER BY p.id DESC LIMIT 100");
$counts = [];
foreach (q_all('SELECT status, COUNT(*) AS c FROM prescriptions GROUP BY status') as $r) {
    $counts[$r['status']] = (int)$r['c'];
}
?>
<h1 class="h3 fw-bold mb-4">Prescription queue</h1>

<ul class="nav nav-pills gap-2 mb-4 flex-wrap">
  <?php foreach ($valid as $f): ?>
    <li class="nav-item">
      <a class="nav-link <?= $filter === $f ? 'active' : '' ?>" href="<?= url('staff/prescriptions.php?f=' . $f) ?>">
        <?= ucfirst($f) ?><?= $f !== 'all' && isset($counts[$f]) ? ' (' . $counts[$f] . ')' : '' ?>
      </a>
    </li>
  <?php endforeach; ?>
</ul>

<?php if (!$rows): ?>
  <div class="mo-card mo-empty"><i class="bi bi-file-medical"></i>No <?= e($filter === 'all' ? '' : $filter) ?> prescriptions</div>
<?php else: ?>
  <div class="row g-3">
    <?php foreach ($rows as $rx): ?>
      <div class="col-md-6 col-xl-4">
        <div class="mo-card p-3 h-100 d-flex flex-column">
          <div class="d-flex justify-content-between align-items-start mb-2">
            <div>
              <div class="fw-bold"><?= e($rx['customer']) ?></div>
              <small class="text-muted"><?= e(pretty_phone($rx['phone'])) ?> · <?= time_ago($rx['created_at']) ?></small>
            </div>
            <span class="mo-status mo-status-<?= e($rx['status']) ?>"><?= ucfirst(e($rx['status'])) ?></span>
          </div>
          <?php if ($rx['notes']): ?><div class="small bg-light rounded-2 p-2 mb-2"><i class="bi bi-chat-left-text me-1"></i><?= e($rx['notes']) ?></div><?php endif; ?>
          <?php if ($rx['review_note']): ?><div class="small text-muted mb-2">Note: <?= e($rx['review_note']) ?></div><?php endif; ?>

          <div class="mt-auto">
            <a class="btn btn-sm mo-btn-soft w-100 mb-2" href="<?= url('file.php?rx=' . $rx['id']) ?>" target="_blank">
              <i class="bi bi-eye me-1"></i>View prescription file</a>

            <?php if (in_array($rx['status'], ['pending', 'approved'], true)): ?>
              <form method="post">
                <?= csrf_field() ?>
                <input type="hidden" name="rx_id" value="<?= $rx['id'] ?>">
                <input type="hidden" name="back_filter" value="<?= e($filter) ?>">
                <input name="note" class="form-control form-control-sm mb-2" placeholder="Note to customer (optional)">
                <div class="d-flex gap-1">
                  <?php if ($rx['status'] === 'pending'): ?>
                    <button name="action" value="approved" class="btn btn-sm mo-btn flex-fill"><i class="bi bi-check-lg me-1"></i>Approve</button>
                    <button name="action" value="rejected" class="btn btn-sm btn-outline-danger flex-fill">Reject</button>
                  <?php else: ?>
                    <button name="action" value="ready" class="btn btn-sm mo-btn flex-fill"><i class="bi bi-bag-check me-1"></i>Mark ready for pickup</button>
                  <?php endif; ?>
                </div>
              </form>
            <?php endif; ?>
          </div>
        </div>
      </div>
    <?php endforeach; ?>
  </div>
<?php endif; ?>
<?php require_once __DIR__ . '/../components/staff-footer.php'; ?>

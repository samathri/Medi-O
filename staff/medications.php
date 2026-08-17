<?php
$pageTitle = 'Medications';
require_once __DIR__ . '/../components/staff-header.php';
require_once dirname(__DIR__) . '/includes/whatsapp.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    csrf_check();
    $action = $_POST['action'] ?? '';

    if ($action === 'assign') {
        $userId = (int)($_POST['user_id'] ?? 0);
        $name = trim($_POST['name'] ?? '');
        $dosage = trim($_POST['dosage'] ?? '');
        $instructions = trim($_POST['instructions'] ?? '');
        $days = max(1, min(365, (int)($_POST['days'] ?? 7)));
        $times = array_filter(array_unique($_POST['times'] ?? []), fn($t) => preg_match('/^\d{2}:\d{2}$/', $t));
        $customer = q_one("SELECT * FROM users WHERE id=? AND role='customer'", [$userId]);

        if (!$customer || $name === '' || !$times) {
            flash('error', 'Select a customer, medicine name, and at least one time.');
        } else {
            q('INSERT INTO medications (user_id, name, dosage, instructions, start_date, end_date, created_by)
               VALUES (?,?,?,?,CURDATE(),DATE_ADD(CURDATE(), INTERVAL ? DAY),?)',
                [$userId, $name, $dosage ?: null, $instructions ?: null, $days, $staff['id']]);
            $medId = (int)db()->lastInsertId();
            foreach ($times as $t) {
                q('INSERT INTO medication_times (medication_id, time_of_day) VALUES (?,?)', [$medId, $t . ':00']);
            }
            notify($userId, 'medication', 'New medication plan: ' . $name,
                'Your pharmacist set up reminders for you.', url('medications.php'));
            if ($customer['phone'] && $customer['whatsapp_opt_in']) {
                wa_send($userId, $customer['phone'],
                    "Medi-O: Your pharmacist set up a medication plan — {$name}" .
                    ($dosage ? " ({$dosage})" : '') . " for {$days} days. You'll get reminders at " .
                    implode(', ', array_map(fn($t) => date('g:i a', strtotime($t)), $times)) . ". 💊");
            }
            flash('success', 'Medication plan assigned to ' . $customer['name'] . '.');
        }
        redirect('staff/medications.php');
    }
}

$customers = q_all("SELECT id, name, phone FROM users WHERE role='customer' ORDER BY name");
$plans = q_all("SELECT m.*, u.name AS customer, u.phone, u.whatsapp_opt_in,
                GROUP_CONCAT(TIME_FORMAT(t.time_of_day,'%h:%i %p') ORDER BY t.time_of_day SEPARATOR ', ') AS times,
                (SELECT COUNT(*) FROM medication_logs l WHERE l.medication_id=m.id AND l.status='taken') AS taken,
                (SELECT COUNT(*) FROM medication_logs l WHERE l.medication_id=m.id AND l.status IN ('taken','skipped','sent')) AS total
                FROM medications m
                JOIN users u ON u.id=m.user_id
                LEFT JOIN medication_times t ON t.medication_id=m.id
                GROUP BY m.id ORDER BY m.id DESC LIMIT 100");
?>
<div class="d-flex flex-wrap justify-content-between align-items-center gap-2 mb-4">
  <h1 class="h3 fw-bold mb-0">Medication tracking</h1>
  <a class="btn mo-btn-soft" href="<?= url('cron/send-reminders.php') ?>" target="_blank">
    <i class="bi bi-lightning-charge me-1"></i>Run reminder dispatch now</a>
</div>

<div class="row g-4">
  <div class="col-lg-5">
    <div class="mo-card p-4">
      <h2 class="h6 fw-bold text-uppercase text-muted mb-3">Assign a plan to a customer</h2>
      <form method="post">
        <?= csrf_field() ?>
        <input type="hidden" name="action" value="assign">
        <div class="mb-3">
          <label class="form-label fw-semibold">Customer</label>
          <select name="user_id" class="form-select" required>
            <option value="">Choose…</option>
            <?php foreach ($customers as $c): ?>
              <option value="<?= $c['id'] ?>"><?= e($c['name']) ?><?= $c['phone'] ? ' (' . e(pretty_phone($c['phone'])) . ')' : ' (no phone)' ?></option>
            <?php endforeach; ?>
          </select>
        </div>
        <div class="mb-3">
          <label class="form-label fw-semibold">Medicine</label>
          <input name="name" class="form-control" required placeholder="e.g. Amoxicillin 500mg">
        </div>
        <div class="row g-3 mb-3">
          <div class="col-6">
            <label class="form-label fw-semibold">Dosage</label>
            <input name="dosage" class="form-control" placeholder="1 capsule">
          </div>
          <div class="col-6">
            <label class="form-label fw-semibold">Duration (days)</label>
            <input type="number" name="days" class="form-control" value="7" min="1" max="365">
          </div>
        </div>
        <div class="mb-3">
          <label class="form-label fw-semibold">Times</label>
          <div id="timesBox" class="d-flex flex-column gap-2">
            <input type="time" name="times[]" class="form-control" value="08:00" required>
          </div>
          <button type="button" class="btn btn-sm mo-btn-soft mt-2" id="addTime"><i class="bi bi-plus"></i> Add time</button>
        </div>
        <div class="mb-4">
          <label class="form-label fw-semibold">Instructions</label>
          <input name="instructions" class="form-control" placeholder="e.g. After meals, full course">
        </div>
        <button class="btn mo-btn w-100"><i class="bi bi-send me-2"></i>Assign &amp; notify on WhatsApp</button>
      </form>
    </div>
  </div>

  <div class="col-lg-7">
    <div class="mo-card p-4">
      <h2 class="h6 fw-bold text-uppercase text-muted mb-3">Active plans &amp; adherence</h2>
      <?php if (!$plans): ?>
        <div class="mo-empty py-4"><i class="bi bi-alarm"></i>No medication plans yet</div>
      <?php else: ?>
        <div class="table-responsive">
          <table class="table mo-table table-hover align-middle">
            <thead><tr><th>Customer</th><th>Medicine</th><th>Times</th><th>Adherence</th><th></th></tr></thead>
            <tbody>
              <?php foreach ($plans as $p):
                  $pct = $p['total'] > 0 ? round($p['taken'] / $p['total'] * 100) : null; ?>
                <tr class="<?= $p['active'] ? '' : 'opacity-50' ?>">
                  <td>
                    <div class="fw-semibold"><?= e($p['customer']) ?></div>
                    <small class="text-muted"><?= e(pretty_phone($p['phone'])) ?></small>
                  </td>
                  <td>
                    <div class="fw-semibold"><?= e($p['name']) ?></div>
                    <small class="text-muted"><?= e($p['dosage'] ?? '') ?></small>
                  </td>
                  <td class="small"><?= e($p['times'] ?? '—') ?></td>
                  <td style="min-width:110px">
                    <?php if ($pct === null): ?>
                      <span class="text-muted small">No data yet</span>
                    <?php else: ?>
                      <div class="progress" style="height:8px"><div class="progress-bar <?= $pct >= 70 ? 'bg-success' : ($pct >= 40 ? 'bg-warning' : 'bg-danger') ?>" style="width:<?= $pct ?>%"></div></div>
                      <small class="text-muted"><?= $p['taken'] ?>/<?= $p['total'] ?> doses (<?= $pct ?>%)</small>
                    <?php endif; ?>
                  </td>
                  <td>
                    <?php if ($p['phone']): ?>
                      <a class="btn btn-sm btn-outline-success" target="_blank" rel="noopener"
                         href="<?= wa_link($p['phone'], "Hi {$p['customer']}, this is Medi-O Pharmacy checking in on your {$p['name']} medication. How are you feeling?") ?>">
                        <i class="bi bi-whatsapp"></i></a>
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

<script>
document.getElementById('addTime').addEventListener('click', () => {
  const box = document.getElementById('timesBox');
  if (box.children.length >= 6) return;
  const wrap = document.createElement('div');
  wrap.className = 'd-flex gap-2';
  wrap.innerHTML = '<input type="time" name="times[]" class="form-control" required>' +
                   '<button type="button" class="btn btn-outline-danger btn-sm" onclick="this.parentElement.remove()"><i class="bi bi-x"></i></button>';
  box.appendChild(wrap);
});
</script>
<?php require_once __DIR__ . '/../components/staff-footer.php'; ?>

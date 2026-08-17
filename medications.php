<?php
require_once __DIR__ . '/config.php';
$u = require_login();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    csrf_check();
    $action = $_POST['action'] ?? '';

    if ($action === 'add') {
        $name = trim($_POST['name'] ?? '');
        $dosage = trim($_POST['dosage'] ?? '');
        $instructions = trim($_POST['instructions'] ?? '');
        $start = $_POST['start_date'] ?: date('Y-m-d');
        $end = $_POST['end_date'] ?: null;
        $times = array_filter(array_unique($_POST['times'] ?? []), fn($t) => preg_match('/^\d{2}:\d{2}$/', $t));

        if ($name === '' || !$times) {
            flash('error', 'Please give the medicine a name and at least one reminder time.');
        } else {
            q('INSERT INTO medications (user_id, name, dosage, instructions, start_date, end_date, created_by)
               VALUES (?,?,?,?,?,?,?)',
                [$u['id'], $name, $dosage ?: null, $instructions ?: null, $start, $end, $u['id']]);
            $medId = (int)db()->lastInsertId();
            foreach ($times as $t) {
                q('INSERT INTO medication_times (medication_id, time_of_day) VALUES (?,?)', [$medId, $t . ':00']);
            }
            flash('success', 'Medication added — we\'ll remind you ' .
                ($u['phone'] && $u['whatsapp_opt_in'] ? 'on WhatsApp.' : 'by email. Add your mobile number in Profile for WhatsApp reminders.'));
        }
        redirect('medications.php');
    }

    if ($action === 'toggle') {
        q('UPDATE medications SET active = 1 - active WHERE id=? AND user_id=?', [(int)$_POST['id'], $u['id']]);
        redirect('medications.php');
    }

    if ($action === 'delete') {
        q('DELETE FROM medications WHERE id=? AND user_id=?', [(int)$_POST['id'], $u['id']]);
        flash('info', 'Medication removed.');
        redirect('medications.php');
    }

    if ($action === 'taken' || $action === 'skipped') {
        $log = q_one('SELECT l.* FROM medication_logs l JOIN medications m ON m.id=l.medication_id
                      WHERE l.id=? AND m.user_id=?', [(int)$_POST['log_id'], $u['id']]);
        if ($log) {
            q('UPDATE medication_logs SET status=?, taken_at=IF(?="taken", NOW(), NULL) WHERE id=?',
                [$action, $action, $log['id']]);
        }
        redirect('medications.php');
    }
}

$medications = q_all('SELECT m.*, GROUP_CONCAT(TIME_FORMAT(t.time_of_day, "%h:%i %p") ORDER BY t.time_of_day SEPARATOR ", ") AS times
                      FROM medications m LEFT JOIN medication_times t ON t.medication_id = m.id
                      WHERE m.user_id=? GROUP BY m.id ORDER BY m.active DESC, m.id DESC', [$u['id']]);

// Today's doses (creates today's log rows lazily so the page always shows the day plan)
foreach (q_all('SELECT t.id AS time_id, t.medication_id, t.time_of_day FROM medication_times t
                JOIN medications m ON m.id=t.medication_id
                WHERE m.user_id=? AND m.active=1 AND m.start_date<=CURDATE()
                  AND (m.end_date IS NULL OR m.end_date>=CURDATE())', [$u['id']]) as $slot) {
    q('INSERT IGNORE INTO medication_logs (medication_id, medication_time_id, scheduled_for)
       VALUES (?,?, CONCAT(CURDATE(), " ", ?))', [$slot['medication_id'], $slot['time_id'], $slot['time_of_day']]);
}
$today = q_all('SELECT l.*, m.name, m.dosage FROM medication_logs l
                JOIN medications m ON m.id=l.medication_id
                WHERE m.user_id=? AND DATE(l.scheduled_for)=CURDATE()
                ORDER BY l.scheduled_for', [$u['id']]);

$takenCount = count(array_filter($today, fn($d) => $d['status'] === 'taken'));

$pageTitle = 'Medication tracker';
include __DIR__ . '/components/header.php';
?>
<div class="container py-4 py-md-5">
  <div class="page-head pt-0">
    <h1 class="h2">Medication tracker</h1>
    <p class="text-muted mb-0">Plan your doses and get a WhatsApp nudge when it's time to take your medicine.</p>
  </div>

  <div class="row g-4">
    <!-- Today -->
    <div class="col-lg-7">
      <div class="mo-card p-4 mb-4" data-reveal>
        <div class="d-flex justify-content-between align-items-center mb-3">
          <h2 class="h5 fw-bold mb-0"><i class="bi bi-calendar-check me-2" style="color:var(--mo-primary)"></i>Today, <?= date('j F') ?></h2>
          <?php if ($today): ?>
            <span class="mo-status <?= $takenCount === count($today) ? 'mo-status-taken' : 'mo-status-scheduled' ?>">
              <?= $takenCount ?>/<?= count($today) ?> taken</span>
          <?php endif; ?>
        </div>

        <?php if (!$today): ?>
          <div class="mo-empty py-4"><i class="bi bi-cup-hot"></i>No doses scheduled for today.</div>
        <?php else: ?>
          <?php if ($takenCount === count($today)): ?>
            <div class="alert alert-success py-2 small"><i class="bi bi-trophy me-1"></i>All doses taken today — great job!</div>
          <?php endif; ?>
          <div class="d-flex flex-column gap-2">
            <?php foreach ($today as $d):
                $due = strtotime($d['scheduled_for']) <= time(); ?>
              <div class="d-flex align-items-center gap-3 border rounded-3 p-3 <?= $d['status'] === 'taken' ? 'bg-light' : '' ?>">
                <div class="fs-4 <?= $d['status'] === 'taken' ? 'text-success' : ($due ? 'text-warning' : 'text-muted') ?>">
                  <i class="bi <?= $d['status'] === 'taken' ? 'bi-check-circle-fill' : ($d['status'] === 'skipped' ? 'bi-slash-circle' : 'bi-alarm') ?>"></i>
                </div>
                <div class="flex-grow-1">
                  <div class="fw-semibold <?= $d['status'] === 'taken' ? 'text-decoration-line-through text-muted' : '' ?>">
                    <?= e($d['name']) ?><?= $d['dosage'] ? ' · ' . e($d['dosage']) : '' ?>
                  </div>
                  <div class="small text-muted"><?= date('g:i a', strtotime($d['scheduled_for'])) ?>
                    <?php if ($d['status'] === 'sent'): ?> · <i class="bi bi-whatsapp text-success"></i> reminder sent<?php endif; ?>
                  </div>
                </div>
                <?php if (!in_array($d['status'], ['taken', 'skipped'], true)): ?>
                  <form method="post" class="d-flex gap-1">
                    <?= csrf_field() ?>
                    <input type="hidden" name="log_id" value="<?= $d['id'] ?>">
                    <button name="action" value="taken" class="btn btn-sm mo-btn"><i class="bi bi-check-lg"></i> Taken</button>
                    <button name="action" value="skipped" class="btn btn-sm btn-outline-secondary">Skip</button>
                  </form>
                <?php elseif ($d['status'] === 'skipped'): ?>
                  <span class="mo-status mo-status-scheduled">Skipped</span>
                <?php endif; ?>
              </div>
            <?php endforeach; ?>
          </div>
        <?php endif; ?>
      </div>

      <!-- All medications -->
      <div class="mo-card p-4" data-reveal>
        <h2 class="h5 fw-bold mb-3"><i class="bi bi-capsule me-2" style="color:var(--mo-primary)"></i>My medicines</h2>
        <?php if (!$medications): ?>
          <div class="mo-empty py-4"><i class="bi bi-capsule"></i>Nothing here yet — add your first medicine.</div>
        <?php else: ?>
          <div class="d-flex flex-column gap-3">
            <?php foreach ($medications as $m): ?>
              <div class="border rounded-3 p-3 <?= $m['active'] ? '' : 'opacity-50' ?>">
                <div class="d-flex flex-wrap justify-content-between align-items-start gap-2">
                  <div>
                    <div class="fw-bold"><?= e($m['name']) ?> <?= $m['dosage'] ? '<span class="text-muted fw-normal">· ' . e($m['dosage']) . '</span>' : '' ?></div>
                    <div class="small text-muted"><i class="bi bi-alarm me-1"></i><?= e($m['times'] ?? 'no times') ?></div>
                    <?php if ($m['instructions']): ?><div class="small text-muted"><i class="bi bi-info-circle me-1"></i><?= e($m['instructions']) ?></div><?php endif; ?>
                    <div class="small text-muted">
                      <?= date('j M', strtotime($m['start_date'])) ?><?= $m['end_date'] ? ' – ' . date('j M Y', strtotime($m['end_date'])) : ' → ongoing' ?>
                      <?php if ($m['created_by'] && $m['created_by'] != $u['id']): ?> · <span class="badge text-bg-light border">Set by pharmacy</span><?php endif; ?>
                    </div>
                  </div>
                  <div class="d-flex gap-1">
                    <form method="post"><?= csrf_field() ?><input type="hidden" name="id" value="<?= $m['id'] ?>">
                      <button name="action" value="toggle" class="btn btn-sm mo-btn-soft"><?= $m['active'] ? 'Pause' : 'Resume' ?></button></form>
                    <form method="post" onsubmit="return confirm('Remove this medication and its history?')">
                      <?= csrf_field() ?><input type="hidden" name="id" value="<?= $m['id'] ?>">
                      <button name="action" value="delete" class="btn btn-sm btn-outline-danger"><i class="bi bi-trash"></i></button></form>
                  </div>
                </div>
              </div>
            <?php endforeach; ?>
          </div>
        <?php endif; ?>
      </div>
    </div>

    <!-- Add form -->
    <div class="col-lg-5" data-reveal>
      <div class="mo-card p-4 position-sticky" style="top:90px">
        <h2 class="h5 fw-bold mb-3"><i class="bi bi-plus-circle me-2" style="color:var(--mo-primary)"></i>Add a medicine</h2>
        <?php if (!$u['phone'] || !$u['whatsapp_opt_in']): ?>
          <div class="alert alert-warning small py-2"><i class="bi bi-whatsapp me-1"></i>
            Reminders will go to your email. For WhatsApp reminders,
            <a href="<?= url('profile.php?tab=details') ?>">add your mobile number</a> and enable WhatsApp updates.</div>
        <?php endif; ?>
        <form method="post">
          <?= csrf_field() ?>
          <input type="hidden" name="action" value="add">
          <div class="mb-3">
            <label class="form-label fw-semibold">Medicine name</label>
            <input name="name" class="form-control" required placeholder="e.g. Metformin 500mg">
          </div>
          <div class="mb-3">
            <label class="form-label fw-semibold">Dosage <span class="text-muted fw-normal">(optional)</span></label>
            <input name="dosage" class="form-control" placeholder="e.g. 1 tablet">
          </div>
          <div class="mb-3">
            <label class="form-label fw-semibold">Reminder times</label>
            <div id="timesBox" class="d-flex flex-column gap-2">
              <input type="time" name="times[]" class="form-control" value="08:00" required>
            </div>
            <button type="button" class="btn btn-sm mo-btn-soft mt-2" id="addTime"><i class="bi bi-plus"></i> Add another time</button>
          </div>
          <div class="row g-3 mb-3">
            <div class="col-6">
              <label class="form-label fw-semibold">Start</label>
              <input type="date" name="start_date" class="form-control" value="<?= date('Y-m-d') ?>">
            </div>
            <div class="col-6">
              <label class="form-label fw-semibold">End <span class="text-muted fw-normal">(optional)</span></label>
              <input type="date" name="end_date" class="form-control">
            </div>
          </div>
          <div class="mb-4">
            <label class="form-label fw-semibold">Instructions <span class="text-muted fw-normal">(optional)</span></label>
            <input name="instructions" class="form-control" placeholder="e.g. After meals">
          </div>
          <button class="btn mo-btn w-100"><i class="bi bi-alarm me-2"></i>Start reminders</button>
        </form>
      </div>
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
<?php include __DIR__ . '/components/footer.php'; ?>

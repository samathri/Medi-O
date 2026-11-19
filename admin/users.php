<?php
$pageTitle = 'Customers & staff';
require_once __DIR__ . '/../components/staff-header.php';
if (!is_admin()) { http_response_code(403); exit('Admin only.'); }

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    csrf_check();
    $action = $_POST['action'] ?? '';
    $target = q_one('SELECT * FROM users WHERE id=?', [(int)($_POST['id'] ?? 0)]);

    if ($action === 'add_staff') {
        $name = trim($_POST['name'] ?? '');
        $email = strtolower(trim($_POST['email'] ?? ''));
        $phone = trim($_POST['phone'] ?? '') !== '' ? normalize_phone(trim($_POST['phone'])) : null;
        $pw = (string)($_POST['password'] ?? '');
        $role = ($_POST['role'] ?? '') === 'admin' ? 'admin' : 'pharmacist';
        if ($name === '' || !filter_var($email, FILTER_VALIDATE_EMAIL) || strlen($pw) < 8) {
            flash('error', 'Enter a name, a valid email and a password of at least 8 characters.');
        } elseif (q_one('SELECT id FROM users WHERE email=?', [$email]) || ($phone && q_one('SELECT id FROM users WHERE phone=?', [$phone]))) {
            flash('error', 'Someone already uses that email or phone — find them below and change their role instead.');
        } else {
            q('INSERT INTO users (name, email, phone, password_hash, role, email_verified, qr_token) VALUES (?,?,?,?,?,1,?)',
                [$name, $email, $phone, password_hash($pw, PASSWORD_BCRYPT), $role, new_token()]);
            flash('success', $name . ' can now log in as ' . $role . ' with ' . $email . '.');
        }
        redirect('admin/users.php');
    }

    if ($target && $target['id'] == $staff['id']) {
        flash('error', 'You cannot change your own account here.');
    } elseif ($action === 'role' && $target) {
        $role = in_array($_POST['role'] ?? '', ['customer', 'pharmacist', 'admin'], true) ? $_POST['role'] : 'customer';
        q('UPDATE users SET role=? WHERE id=?', [$role, $target['id']]);
        flash('success', $target['name'] . ' is now a ' . $role . '.');
    } elseif ($action === 'delete' && $target) {
        q('DELETE FROM users WHERE id=?', [$target['id']]);
        flash('info', 'Account "' . $target['name'] . '" and its data were deleted.');
    }
    redirect('admin/users.php');
}

$search = trim($_GET['q'] ?? '');
$where = '1';
$params = [];
if ($search !== '') {
    $where = '(name LIKE ? OR email LIKE ? OR phone LIKE ?)';
    $params = ["%$search%", "%$search%", "%$search%"];
}
$users = q_all("SELECT u.*,
                (SELECT COUNT(*) FROM orders o WHERE o.user_id=u.id) AS orders,
                (SELECT COUNT(*) FROM prescriptions p WHERE p.user_id=u.id) AS rx
                FROM users u WHERE $where ORDER BY u.id DESC LIMIT 200", $params);
?>
<div class="d-flex flex-wrap justify-content-between align-items-center gap-2 mb-4">
  <h1 class="h3 fw-bold mb-0">Customers &amp; staff</h1>
  <div class="d-flex gap-2">
    <form class="d-flex gap-2" method="get">
      <input type="search" name="q" class="form-control" placeholder="Search name, email, phone…" value="<?= e($search) ?>">
      <button class="btn mo-btn-soft"><i class="bi bi-search"></i></button>
    </form>
    <button class="btn mo-btn text-nowrap" data-bs-toggle="collapse" data-bs-target="#addStaff"><i class="bi bi-person-plus me-1"></i>Add pharmacist</button>
  </div>
</div>

<div class="collapse mb-4" id="addStaff">
  <form method="post" class="mo-card p-4">
    <?= csrf_field() ?>
    <input type="hidden" name="action" value="add_staff">
    <h2 class="h5 fw-bold mb-3">Add a staff member</h2>
    <div class="row g-3">
      <div class="col-md-4"><label class="form-label">Full name</label><input name="name" class="form-control" required></div>
      <div class="col-md-4"><label class="form-label">Email (used to log in)</label><input type="email" name="email" class="form-control" required></div>
      <div class="col-md-4"><label class="form-label">Mobile <span class="text-muted fw-normal">(optional)</span></label><input name="phone" class="form-control" placeholder="07X XXX XXXX"></div>
      <div class="col-md-4"><label class="form-label">Starting password</label><input name="password" class="form-control" minlength="8" required placeholder="At least 8 characters"></div>
      <div class="col-md-4"><label class="form-label">Role</label>
        <select name="role" class="form-select"><option value="pharmacist">Pharmacist</option><option value="admin">Admin (full access)</option></select></div>
      <div class="col-md-4 d-flex align-items-end"><button class="btn mo-btn w-100">Create account</button></div>
    </div>
    <div class="small text-muted mt-2">Give them the email and password — they can change the password from their profile.</div>
  </form>
</div>

<div class="mo-card p-0 overflow-hidden">
  <div class="table-responsive">
    <table class="table mo-table table-hover align-middle mb-0">
      <thead><tr><th>User</th><th>Contact</th><th>Activity</th><th>Joined</th><th>Role</th><th></th></tr></thead>
      <tbody>
        <?php foreach ($users as $row): ?>
          <tr>
            <td>
              <div class="d-flex align-items-center gap-2">
                <div class="mo-avatar-btn" style="width:38px;height:38px;font-size:1rem;pointer-events:none">
                  <?php if ($row['avatar']): ?><img src="<?= e($row['avatar']) ?>" alt=""><?php else: ?><span><?= e(strtoupper(mb_substr($row['name'], 0, 1))) ?></span><?php endif; ?>
                </div>
                <div>
                  <div class="fw-semibold small"><?= e($row['name']) ?><?= $row['id'] == $staff['id'] ? ' <span class="badge text-bg-light border">you</span>' : '' ?></div>
                  <?php if ($row['google_id']): ?><small class="text-muted"><i class="bi bi-google"></i> Google</small><?php endif; ?>
                </div>
              </div>
            </td>
            <td class="small">
              <div><?= e($row['email'] ?? '—') ?></div>
              <div class="text-muted"><?= e(pretty_phone($row['phone'])) ?></div>
            </td>
            <td class="small text-muted"><?= (int)$row['orders'] ?> orders · <?= (int)$row['rx'] ?> rx</td>
            <td class="small text-muted"><?= date('j M Y', strtotime($row['created_at'])) ?></td>
            <td>
              <?php if ($row['id'] == $staff['id']): ?>
                <span class="mo-status mo-status-ready"><?= e($row['role']) ?></span>
              <?php else: ?>
                <form method="post" class="d-flex gap-1">
                  <?= csrf_field() ?>
                  <input type="hidden" name="action" value="role">
                  <input type="hidden" name="id" value="<?= $row['id'] ?>">
                  <select name="role" class="form-select form-select-sm w-auto" data-orig="<?= e($row['role']) ?>"
                          onchange="if (confirm('Make <?= e(addslashes($row['name'])) ?> a ' + this.options[this.selectedIndex].text + '?')) this.form.submit(); else this.value = this.dataset.orig;">
                    <?php foreach (['customer', 'pharmacist', 'admin'] as $r): ?>
                      <option value="<?= $r ?>" <?= $row['role'] === $r ? 'selected' : '' ?>><?= ucfirst($r) ?></option>
                    <?php endforeach; ?>
                  </select>
                </form>
              <?php endif; ?>
            </td>
            <td>
              <?php if ($row['id'] != $staff['id']): ?>
                <form method="post" onsubmit="return confirm('Delete <?= e(addslashes($row['name'])) ?> and ALL their orders, prescriptions and chats? This cannot be undone.')">
                  <?= csrf_field() ?>
                  <input type="hidden" name="action" value="delete">
                  <input type="hidden" name="id" value="<?= $row['id'] ?>">
                  <button class="btn btn-sm btn-outline-danger"><i class="bi bi-trash"></i></button>
                </form>
              <?php endif; ?>
            </td>
          </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
  </div>
</div>
<?php require_once __DIR__ . '/../components/staff-footer.php'; ?>

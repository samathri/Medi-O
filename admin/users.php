<?php
$pageTitle = 'Users';
require_once __DIR__ . '/../components/staff-header.php';
if (!is_admin()) { http_response_code(403); exit('Admin only.'); }

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    csrf_check();
    $action = $_POST['action'] ?? '';
    $target = q_one('SELECT * FROM users WHERE id=?', [(int)($_POST['id'] ?? 0)]);

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
  <h1 class="h3 fw-bold mb-0">User management</h1>
  <form class="d-flex gap-2" method="get">
    <input type="search" name="q" class="form-control" placeholder="Search name, email, phone…" value="<?= e($search) ?>">
    <button class="btn mo-btn"><i class="bi bi-search"></i></button>
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
                  <select name="role" class="form-select form-select-sm w-auto" onchange="this.form.submit()">
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

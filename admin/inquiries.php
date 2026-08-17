<?php
$pageTitle = 'Inquiries';
require_once __DIR__ . '/../components/staff-header.php';
if (!is_admin()) { http_response_code(403); exit('Admin only.'); }

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    csrf_check();
    if (($_POST['action'] ?? '') === 'read') {
        q('UPDATE contact_messages SET is_read=1 WHERE id=?', [(int)$_POST['id']]);
    }
    redirect('admin/inquiries.php');
}

$messages = q_all('SELECT * FROM contact_messages ORDER BY is_read, id DESC LIMIT 100');
?>
<h1 class="h3 fw-bold mb-4">Contact inquiries</h1>

<?php if (!$messages): ?>
  <div class="mo-card mo-empty"><i class="bi bi-envelope-open"></i>No inquiries yet</div>
<?php else: ?>
  <div class="row g-3">
    <?php foreach ($messages as $m): ?>
      <div class="col-md-6 col-xl-4">
        <div class="mo-card p-3 h-100 <?= $m['is_read'] ? 'opacity-75' : '' ?>">
          <div class="d-flex justify-content-between align-items-start mb-1">
            <div class="fw-bold"><?= e($m['name']) ?></div>
            <?php if (!$m['is_read']): ?><span class="badge text-bg-warning">New</span><?php endif; ?>
          </div>
          <a class="small d-block text-decoration-none" href="mailto:<?= e($m['email']) ?>"><?= e($m['email']) ?></a>
          <?php if ($m['subject']): ?><div class="small fw-semibold mt-2"><?= e($m['subject']) ?></div><?php endif; ?>
          <p class="small text-muted mt-1 mb-2" style="white-space:pre-line"><?= e($m['message']) ?></p>
          <div class="d-flex justify-content-between align-items-center">
            <small class="text-muted"><?= time_ago($m['created_at']) ?></small>
            <?php if (!$m['is_read']): ?>
              <form method="post"><?= csrf_field() ?>
                <input type="hidden" name="action" value="read"><input type="hidden" name="id" value="<?= $m['id'] ?>">
                <button class="btn btn-sm mo-btn-soft">Mark read</button>
              </form>
            <?php endif; ?>
          </div>
        </div>
      </div>
    <?php endforeach; ?>
  </div>
<?php endif; ?>
<?php require_once __DIR__ . '/../components/staff-footer.php'; ?>

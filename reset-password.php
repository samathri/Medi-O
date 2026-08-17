<?php
require_once __DIR__ . '/config.php';

$token = $_GET['token'] ?? $_POST['token'] ?? '';
$user = $token !== ''
    ? q_one('SELECT * FROM users WHERE reset_token = ? AND reset_expires > NOW()', [hash('sha256', $token)])
    : null;

$error = '';
if ($user && $_SERVER['REQUEST_METHOD'] === 'POST') {
    csrf_check();
    $password = $_POST['password'] ?? '';
    $confirm = $_POST['confirm'] ?? '';
    if (strlen($password) < 8) {
        $error = 'Password must be at least 8 characters.';
    } elseif ($password !== $confirm) {
        $error = 'Passwords do not match.';
    } else {
        q('UPDATE users SET password_hash = ?, reset_token = NULL, reset_expires = NULL WHERE id = ?',
            [password_hash($password, PASSWORD_BCRYPT), $user['id']]);
        flash('success', 'Password updated — you can now log in.');
        redirect('login.php');
    }
}

$pageTitle = 'Reset password';
include __DIR__ . '/components/header.php';
?>
<div class="container py-5">
  <div class="mo-card mo-auth-card p-4 p-md-5" data-reveal>
    <?php if (!$user): ?>
      <div class="text-center">
        <i class="bi bi-link-45deg text-danger" style="font-size:3rem"></i>
        <h1 class="h4 fw-bold mt-2">Link expired or invalid</h1>
        <p class="text-muted">This reset link is no longer valid. Please request a new one.</p>
        <a class="btn mo-btn mt-2" href="<?= url('forgot-password.php') ?>">Request new link</a>
      </div>
    <?php else: ?>
      <div class="text-center mb-4">
        <h1 class="h3 fw-bold">Choose a new password</h1>
        <p class="text-muted">for <?= e($user['email']) ?></p>
      </div>
      <?php if ($error): ?>
        <div class="alert alert-danger py-2"><?= e($error) ?></div>
      <?php endif; ?>
      <form method="post" novalidate>
        <?= csrf_field() ?>
        <input type="hidden" name="token" value="<?= e($token) ?>">
        <div class="mb-3">
          <label class="form-label fw-semibold">New password</label>
          <input type="password" name="password" class="form-control form-control-lg" minlength="8" required placeholder="Min. 8 characters" autofocus>
        </div>
        <div class="mb-4">
          <label class="form-label fw-semibold">Confirm new password</label>
          <input type="password" name="confirm" class="form-control form-control-lg" required placeholder="Repeat password">
        </div>
        <button class="btn mo-btn w-100 btn-lg">Update password</button>
      </form>
    <?php endif; ?>
  </div>
</div>
<?php include __DIR__ . '/components/footer.php'; ?>

<?php
require_once __DIR__ . '/config.php';
require_once __DIR__ . '/includes/mailer.php';

$emailDone = false;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    csrf_check();
    $email = strtolower(trim($_POST['email'] ?? ''));
    if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $user = q_one('SELECT * FROM users WHERE email = ?', [$email]);
        if ($user) {
            $token = bin2hex(random_bytes(32));
            q('UPDATE users SET reset_token = ?, reset_expires = DATE_ADD(NOW(), INTERVAL 1 HOUR) WHERE id = ?',
                [hash('sha256', $token), $user['id']]);
            send_mail($email, 'Reset your Medi-O password', mail_template(
                'Password reset request',
                '<p>We received a request to reset your password. This link is valid for 1 hour.
                 If you didn\'t request this, you can safely ignore this email.</p>',
                'Reset my password', url('reset-password.php?token=' . $token)
            ));
        }
    }
    $emailDone = true; // same response whether or not the account exists
}

$pageTitle = 'Forgot password';
include __DIR__ . '/components/header.php';
?>
<div class="container py-5">
  <div class="mo-card mo-auth-card p-4 p-md-5">
    <?php if ($emailDone): ?>
      <div class="text-center">
        <i class="bi bi-envelope-check text-success" style="font-size:3rem"></i>
        <h1 class="h4 fw-bold mt-3">Check your inbox</h1>
        <p class="text-muted">If an account exists for that email, we've sent a password reset link.
           It expires in 1 hour. Check your spam folder too.</p>
        <?php if (SMTP_USER === ''): ?>
          <div class="alert alert-info small text-start"><i class="bi bi-info-circle me-1"></i>
            Demo mode: email sending is not configured, so the reset link was written to <code>logs/mail.log</code>.</div>
        <?php endif; ?>
        <a class="btn mo-btn mt-2" href="<?= url('login.php') ?>">Back to login</a>
      </div>
    <?php else: ?>
      <div class="text-center mb-4">
        <h1 class="h3 fw-bold">Forgot your password?</h1>
        <p class="text-muted">Enter your account email and we'll send you a link to set a new one.</p>
      </div>
      <form method="post" novalidate>
        <?= csrf_field() ?>
        <div class="mb-3">
          <label class="form-label">Account email</label>
          <input type="email" name="email" class="form-control form-control-lg" required placeholder="you@email.com" autofocus>
        </div>
        <button class="btn mo-btn w-100 btn-lg">Send reset link</button>
      </form>
      <p class="text-center mt-4 mb-0"><a href="<?= url('login.php') ?>" class="text-decoration-none"><i class="bi bi-arrow-left me-1"></i>Back to login</a></p>
    <?php endif; ?>
  </div>
</div>
<?php include __DIR__ . '/components/footer.php'; ?>

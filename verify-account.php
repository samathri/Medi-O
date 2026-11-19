<?php
require_once __DIR__ . '/config.php';
require_once __DIR__ . '/includes/otp.php';
$u = require_login();

if (!$u['email'] || $u['email_verified']) {
    if (!empty($_SESSION['after_verify'])) { $to = $_SESSION['after_verify']; unset($_SESSION['after_verify']); redirect($to); }
    flash('success', 'Your email is already confirmed.');
    redirect('profile.php');
}

$demoEmailCode = $_SESSION['demo_code_email'] ?? null;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    csrf_check();
    $action = $_POST['action'] ?? '';

    if ($action === 'send_email') {
        [$ok, $demo, $err] = otp_send_email($u);
        if ($ok) {
            $_SESSION['demo_code_email'] = $demo;
            flash('success', 'We sent a new code to ' . $u['email'] . ($demo ? ' (demo mode — shown below)' : ''));
        } else {
            flash('error', $err);
        }
        redirect('verify-account.php');
    }

    if ($action === 'verify_email') {
        [$ok, $err] = otp_verify((int)$u['id'], 'email', $_POST['code'] ?? '');
        if ($ok) {
            q('UPDATE users SET email_verified=1 WHERE id=?', [$u['id']]);
            unset($_SESSION['demo_code_email']);
            flash('success', 'Email confirmed ✔');
            if (!empty($_SESSION['after_verify'])) { $to = $_SESSION['after_verify']; unset($_SESSION['after_verify']); redirect($to); }
            redirect('profile.php');
        }
        flash('error', $err);
        redirect('verify-account.php');
    }
}

$hasEmailCode = (bool)q_one("SELECT id FROM verification_codes WHERE user_id=? AND channel='email' AND expires_at > NOW()", [$u['id']]);

$pageTitle = 'Confirm your email';
include __DIR__ . '/components/header.php';
?>
<div class="container py-5">
  <div class="mo-card mo-auth-card p-4 p-md-5">
    <div class="text-center mb-4">
      <i class="bi bi-envelope-check" style="font-size:2.6rem;color:var(--green)"></i>
      <h1 class="h3 fw-bold mt-2">Confirm your email</h1>
      <p class="text-muted mb-0">We sent a 6-digit code to <b><?= e($u['email']) ?></b>. This keeps your medical details safe.</p>
    </div>

    <?php if (!$hasEmailCode): ?>
      <form method="post">
        <?= csrf_field() ?>
        <input type="hidden" name="action" value="send_email">
        <button class="btn mo-btn w-100 btn-lg"><i class="bi bi-send me-2"></i>Send me a code</button>
      </form>
    <?php else: ?>
      <?php if ($demoEmailCode): ?>
        <div class="alert alert-info small py-2"><i class="bi bi-info-circle me-1"></i>
          <strong>Demo mode</strong> (email not configured): your code is
          <span class="fw-bold fs-6 ms-1" style="letter-spacing:3px"><?= e($demoEmailCode) ?></span></div>
      <?php endif; ?>
      <form method="post">
        <?= csrf_field() ?>
        <input type="hidden" name="action" value="verify_email">
        <label class="form-label">6-digit code from your email</label>
        <input name="code" class="form-control form-control-lg text-center fw-bold mb-3" maxlength="6"
               inputmode="numeric" pattern="\d{6}" placeholder="••••••" style="letter-spacing:8px" required autocomplete="one-time-code" autofocus>
        <button class="btn mo-btn w-100 btn-lg">Confirm</button>
      </form>
      <form method="post" class="text-center mt-2">
        <?= csrf_field() ?>
        <input type="hidden" name="action" value="send_email">
        <button class="btn btn-link btn-sm text-decoration-none">Didn't get it? Send a new code</button>
      </form>
      <p class="small text-muted text-center mb-0">Check your spam folder too.</p>
    <?php endif; ?>

    <div class="text-center mt-3" <?= !empty($_SESSION['after_verify']) ? 'hidden' : '' ?>>
      <a href="<?= url('index.php') ?>" class="text-decoration-none small text-muted">Skip for now — I'll do it before checkout</a>
    </div>
  </div>
</div>
<?php include __DIR__ . '/components/footer.php'; ?>

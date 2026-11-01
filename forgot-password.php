<?php
require_once __DIR__ . '/config.php';
require_once __DIR__ . '/includes/mailer.php';
require_once __DIR__ . '/includes/otp.php';

$method = $_GET['method'] ?? $_POST['method'] ?? 'email';
if (!in_array($method, ['email', 'phone'], true)) $method = 'email';

$emailDone = false;          // email link sent screen
$phoneStage = 'request';     // request → code (enter code + new password)
$demoResetCode = $_SESSION['demo_code_reset'] ?? null;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    csrf_check();
    $action = $_POST['action'] ?? '';

    /* ---- Method 1: reset link by email ---- */
    if ($action === 'email_link') {
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

    /* ---- Method 2: code via WhatsApp ---- */
    if ($action === 'phone_request') {
        $method = 'phone';
        $phone = normalize_phone($_POST['phone'] ?? '');
        $user = $phone ? q_one('SELECT * FROM users WHERE phone = ?', [$phone]) : null;
        if (!$user) {
            // don't reveal which numbers exist — show the code screen regardless
            $_SESSION['pw_reset_user'] = 0;
            unset($_SESSION['demo_code_reset']);
            $phoneStage = 'code';
        } else {
            [$ok, $demo, $err] = otp_send_phone($user, 'reset');
            if (!$ok) {
                flash('error', $err);
            } else {
                $_SESSION['pw_reset_user'] = (int)$user['id'];
                $_SESSION['demo_code_reset'] = $demo;
                $demoResetCode = $demo;
                $phoneStage = 'code';
            }
        }
    }

    if ($action === 'phone_verify') {
        $method = 'phone';
        $phoneStage = 'code';
        $userId = (int)($_SESSION['pw_reset_user'] ?? 0);
        $password = $_POST['password'] ?? '';
        $confirm = $_POST['confirm'] ?? '';

        if (strlen($password) < 8) {
            flash('error', 'Password must be at least 8 characters.');
        } elseif ($password !== $confirm) {
            flash('error', 'Passwords do not match.');
        } else {
            [$ok, $err] = $userId ? otp_verify($userId, 'reset', $_POST['code'] ?? '') : [false, 'Incorrect code.'];
            if ($ok) {
                q('UPDATE users SET password_hash=?, reset_token=NULL, reset_expires=NULL WHERE id=?',
                    [password_hash($password, PASSWORD_BCRYPT), $userId]);
                unset($_SESSION['pw_reset_user'], $_SESSION['demo_code_reset']);
                flash('success', 'Password updated — you can now log in.');
                redirect('login.php');
            }
            flash('error', $err);
        }
    }
}

$pageTitle = 'Forgot password';
include __DIR__ . '/components/header.php';
?>
<div class="container py-5">
  <div class="mo-card mo-auth-card p-4 p-md-5" data-reveal>
    <?php if ($emailDone): ?>
      <div class="text-center">
        <i class="bi bi-envelope-check text-success" style="font-size:3rem"></i>
        <h1 class="h4 fw-bold mt-3">Check your inbox</h1>
        <p class="text-muted">If an account exists for that email, we've sent a password reset link.
           It expires in 1 hour.</p>
        <?php if (SMTP_USER === ''): ?>
          <div class="alert alert-info small text-start"><i class="bi bi-info-circle me-1"></i>
            Demo mode: email sending is not configured, so the reset link was written to
            <code>logs/mail.log</code>. Tip: you can also reset via
            <a href="<?= url('forgot-password.php?method=phone') ?>">WhatsApp code</a>.</div>
        <?php endif; ?>
        <a class="btn mo-btn mt-2" href="<?= url('login.php') ?>">Back to login</a>
      </div>

    <?php else: ?>
      <div class="text-center mb-4">
        <h1 class="h3 fw-bold">Forgot your password?</h1>
        <p class="text-muted">Choose how you'd like to reset it.</p>
      </div>

      <ul class="nav nav-pills nav-fill gap-2 mb-4">
        <li class="nav-item"><a class="nav-link <?= $method === 'email' ? 'active' : '' ?>" href="<?= url('forgot-password.php?method=email') ?>"><i class="bi bi-envelope me-1"></i>Email link</a></li>
        <li class="nav-item"><a class="nav-link <?= $method === 'phone' ? 'active' : '' ?>" href="<?= url('forgot-password.php?method=phone') ?>"><i class="bi bi-whatsapp me-1"></i>WhatsApp code</a></li>
      </ul>

      <?php if ($method === 'email'): ?>
        <form method="post" novalidate>
          <?= csrf_field() ?>
          <input type="hidden" name="action" value="email_link">
          <div class="mb-3">
            <label class="form-label fw-semibold">Account email</label>
            <input type="email" name="email" class="form-control form-control-lg" required placeholder="you@email.com" autofocus>
          </div>
          <button class="btn mo-btn w-100 btn-lg">Send reset link</button>
        </form>

      <?php elseif ($phoneStage === 'request'): ?>
        <form method="post" novalidate>
          <?= csrf_field() ?>
          <input type="hidden" name="action" value="phone_request">
          <div class="mb-3">
            <label class="form-label fw-semibold">Account mobile number</label>
            <input type="tel" name="phone" class="form-control form-control-lg" required placeholder="07X XXX XXXX" autofocus>
            <div class="form-text">We'll send a 6-digit code to this number on WhatsApp.</div>
          </div>
          <button class="btn mo-btn w-100 btn-lg"><i class="bi bi-whatsapp me-2"></i>Send code</button>
        </form>

      <?php else: ?>
        <?php if ($demoResetCode): ?>
          <div class="alert alert-info small py-2"><i class="bi bi-info-circle me-1"></i>
            <strong>Demo mode</strong> (WhatsApp API not configured): your code is
            <span class="fw-bold fs-6 ms-1" style="letter-spacing:3px"><?= e($demoResetCode) ?></span></div>
        <?php endif; ?>
        <form method="post" novalidate>
          <?= csrf_field() ?>
          <input type="hidden" name="action" value="phone_verify">
          <div class="mb-3">
            <label class="form-label fw-semibold">6-digit code from WhatsApp</label>
            <input name="code" class="form-control form-control-lg text-center fw-bold" maxlength="6"
                   inputmode="numeric" pattern="\d{6}" placeholder="••••••" style="letter-spacing:8px" required autocomplete="one-time-code">
          </div>
          <div class="mb-3">
            <label class="form-label fw-semibold">New password</label>
            <input type="password" name="password" class="form-control" minlength="8" required placeholder="Min. 8 characters">
          </div>
          <div class="mb-4">
            <label class="form-label fw-semibold">Confirm new password</label>
            <input type="password" name="confirm" class="form-control" required placeholder="Repeat password">
          </div>
          <button class="btn mo-btn w-100 btn-lg">Reset password</button>
        </form>
        <form method="post" class="text-center mt-2">
          <?= csrf_field() ?>
          <input type="hidden" name="action" value="phone_request">
          <input type="hidden" name="phone" value="">
        </form>
        <p class="text-center small mt-2 mb-0"><a href="<?= url('forgot-password.php?method=phone') ?>" class="text-decoration-none">Use a different number / resend</a></p>
      <?php endif; ?>

      <p class="text-center mt-4 mb-0"><a href="<?= url('login.php') ?>" class="text-decoration-none"><i class="bi bi-arrow-left me-1"></i>Back to login</a></p>
    <?php endif; ?>
  </div>
</div>
<?php include __DIR__ . '/components/footer.php'; ?>

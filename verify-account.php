<?php
require_once __DIR__ . '/config.php';
require_once __DIR__ . '/includes/otp.php';
$u = require_login();

// Already fully verified? Nothing to do here.
$needsEmail = $u['email'] && !$u['email_verified'];
$needsPhone = $u['phone'] && !$u['phone_verified'];
if (!$needsEmail && !$needsPhone) {
    flash('success', 'Your account is fully verified.');
    redirect('profile.php');
}

$demoEmailCode = $_SESSION['demo_code_email'] ?? null;
$demoPhoneCode = $_SESSION['demo_code_phone'] ?? null;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    csrf_check();
    $action = $_POST['action'] ?? '';

    if ($action === 'send_email' && $needsEmail) {
        [$ok, $demo, $err] = otp_send_email($u);
        if ($ok) {
            $_SESSION['demo_code_email'] = $demo;
            flash('success', 'Verification code sent to ' . $u['email'] . ($demo ? ' (demo mode — shown below)' : ''));
        } else {
            flash('error', $err);
        }
        redirect('verify-account.php');
    }

    if ($action === 'send_phone' && $needsPhone) {
        [$ok, $demo, $err] = otp_send_phone($u);
        if ($ok) {
            $_SESSION['demo_code_phone'] = $demo;
            flash('success', 'Verification code sent to WhatsApp ' . pretty_phone($u['phone']) . ($demo ? ' (demo mode — shown below)' : ''));
        } else {
            flash('error', $err);
        }
        redirect('verify-account.php');
    }

    if ($action === 'verify_email' && $needsEmail) {
        [$ok, $err] = otp_verify((int)$u['id'], 'email', $_POST['code'] ?? '');
        if ($ok) {
            q('UPDATE users SET email_verified=1 WHERE id=?', [$u['id']]);
            unset($_SESSION['demo_code_email']);
            flash('success', 'Email verified ✔');
        } else {
            flash('error', $err);
        }
        redirect('verify-account.php');
    }

    if ($action === 'verify_phone' && $needsPhone) {
        [$ok, $err] = otp_verify((int)$u['id'], 'phone', $_POST['code'] ?? '');
        if ($ok) {
            q('UPDATE users SET phone_verified=1 WHERE id=?', [$u['id']]);
            unset($_SESSION['demo_code_phone']);
            flash('success', 'Mobile number verified ✔');
        } else {
            flash('error', $err);
        }
        redirect('verify-account.php');
    }
}

$hasEmailCode = (bool)q_one("SELECT id FROM verification_codes WHERE user_id=? AND channel='email' AND expires_at > NOW()", [$u['id']]);
$hasPhoneCode = (bool)q_one("SELECT id FROM verification_codes WHERE user_id=? AND channel='phone' AND expires_at > NOW()", [$u['id']]);

$pageTitle = 'Verify your account';
include __DIR__ . '/components/header.php';
?>
<div class="container py-5">
  <div class="text-center mb-4" data-reveal>
    <h1 class="h2 fw-bold">Verify your account</h1>
    <p class="text-muted mx-auto" style="max-width:520px">Confirming your contact details keeps your
      medical information safe and lets us reach you about orders and prescriptions.
      Verification is required before placing orders.</p>
  </div>

  <div class="row g-4 justify-content-center">
    <?php if ($needsEmail): ?>
      <div class="col-md-6 col-lg-5" data-reveal>
        <div class="mo-card p-4 h-100">
          <div class="d-flex align-items-center gap-3 mb-3">
            <span class="mo-step-icon m-0"><i class="bi bi-envelope"></i></span>
            <div>
              <h2 class="h6 fw-bold mb-0">Email address</h2>
              <div class="small text-muted"><?= e($u['email']) ?></div>
            </div>
          </div>

          <?php if (!$hasEmailCode): ?>
            <form method="post">
              <?= csrf_field() ?>
              <input type="hidden" name="action" value="send_email">
              <button class="btn mo-btn w-100"><i class="bi bi-send me-2"></i>Send code to my email</button>
            </form>
          <?php else: ?>
            <?php if ($demoEmailCode): ?>
              <div class="alert alert-info small py-2"><i class="bi bi-info-circle me-1"></i>
                <strong>Demo mode</strong> (SMTP not configured): your code is
                <span class="fw-bold fs-6 ms-1" style="letter-spacing:3px"><?= e($demoEmailCode) ?></span></div>
            <?php endif; ?>
            <form method="post">
              <?= csrf_field() ?>
              <input type="hidden" name="action" value="verify_email">
              <label class="form-label fw-semibold small">Enter the 6-digit code from your email</label>
              <input name="code" class="form-control form-control-lg text-center fw-bold mb-2" maxlength="6"
                     inputmode="numeric" pattern="\d{6}" placeholder="••••••" style="letter-spacing:8px" required autocomplete="one-time-code">
              <button class="btn mo-btn w-100 mb-2">Verify email</button>
            </form>
            <form method="post" class="text-center">
              <?= csrf_field() ?>
              <input type="hidden" name="action" value="send_email">
              <button class="btn btn-link btn-sm text-decoration-none">Resend code</button>
            </form>
          <?php endif; ?>
        </div>
      </div>
    <?php endif; ?>

    <?php if ($needsPhone): ?>
      <div class="col-md-6 col-lg-5" data-reveal>
        <div class="mo-card p-4 h-100">
          <div class="d-flex align-items-center gap-3 mb-3">
            <span class="mo-step-icon m-0"><i class="bi bi-whatsapp"></i></span>
            <div>
              <h2 class="h6 fw-bold mb-0">Mobile number</h2>
              <div class="small text-muted"><?= e(pretty_phone($u['phone'])) ?> · code arrives on WhatsApp</div>
            </div>
          </div>

          <?php if (!$hasPhoneCode): ?>
            <form method="post">
              <?= csrf_field() ?>
              <input type="hidden" name="action" value="send_phone">
              <button class="btn mo-btn w-100"><i class="bi bi-whatsapp me-2"></i>Send code to WhatsApp</button>
            </form>
          <?php else: ?>
            <?php if ($demoPhoneCode): ?>
              <div class="alert alert-info small py-2"><i class="bi bi-info-circle me-1"></i>
                <strong>Demo mode</strong> (WhatsApp API not configured): your code is
                <span class="fw-bold fs-6 ms-1" style="letter-spacing:3px"><?= e($demoPhoneCode) ?></span></div>
            <?php endif; ?>
            <form method="post">
              <?= csrf_field() ?>
              <input type="hidden" name="action" value="verify_phone">
              <label class="form-label fw-semibold small">Enter the 6-digit code from WhatsApp</label>
              <input name="code" class="form-control form-control-lg text-center fw-bold mb-2" maxlength="6"
                     inputmode="numeric" pattern="\d{6}" placeholder="••••••" style="letter-spacing:8px" required autocomplete="one-time-code">
              <button class="btn mo-btn w-100 mb-2">Verify number</button>
            </form>
            <form method="post" class="text-center">
              <?= csrf_field() ?>
              <input type="hidden" name="action" value="send_phone">
              <button class="btn btn-link btn-sm text-decoration-none">Resend code</button>
            </form>
          <?php endif; ?>
        </div>
      </div>
    <?php endif; ?>
  </div>

  <div class="text-center mt-4" data-reveal>
    <a href="<?= url('index.php') ?>" class="text-decoration-none small text-muted">Skip for now — I'll verify later
      <i class="bi bi-arrow-right ms-1"></i></a>
  </div>
</div>
<?php include __DIR__ . '/components/footer.php'; ?>

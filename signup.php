<?php
require_once __DIR__ . '/config.php';

if (is_logged_in()) {
    redirect(role_home(current_user()));
}

$error = '';
$old = ['name' => '', 'email' => '', 'phone' => ''];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    csrf_check();
    $old['name'] = trim($_POST['name'] ?? '');
    $old['email'] = strtolower(trim($_POST['email'] ?? ''));
    $old['phone'] = trim($_POST['phone'] ?? '');
    $password = $_POST['password'] ?? '';
    $confirm = $_POST['confirm'] ?? '';
    $phone = $old['phone'] !== '' ? normalize_phone($old['phone']) : null;

    if ($old['name'] === '' || $old['email'] === '' || $password === '') {
        $error = 'Name, email and password are required.';
    } elseif (!filter_var($old['email'], FILTER_VALIDATE_EMAIL)) {
        $error = 'Please enter a valid email address.';
    } elseif ($old['phone'] !== '' && !$phone) {
        $error = 'Please enter a valid Sri Lankan mobile number (e.g. 0771234567).';
    } elseif (strlen($password) < 8) {
        $error = 'Password must be at least 8 characters.';
    } elseif ($password !== $confirm) {
        $error = 'Passwords do not match.';
    } elseif (q_one('SELECT id FROM users WHERE email = ?', [$old['email']])) {
        $error = 'An account with this email already exists. Try logging in.';
    } elseif ($phone && q_one('SELECT id FROM users WHERE phone = ?', [$phone])) {
        $error = 'An account with this mobile number already exists.';
    } else {
        q('INSERT INTO users (name, email, phone, password_hash, qr_token) VALUES (?,?,?,?,?)', [
            $old['name'], $old['email'], $phone,
            password_hash($password, PASSWORD_BCRYPT), new_token(),
        ]);
        $user = q_one('SELECT * FROM users WHERE id = ?', [db()->lastInsertId()]);
        login_user($user);

        require_once __DIR__ . '/includes/mailer.php';
        require_once __DIR__ . '/includes/otp.php';
        send_mail($user['email'], 'Welcome to Medi-O 🎉', mail_template(
            'Welcome to Medi-O, ' . $user['name'] . '!',
            '<p>Your account is ready. You can now order medicines, upload prescriptions, chat with our
             pharmacists, and collect orders with your personal QR card.</p>',
            'Open my QR card', url('my-qr.php')
        ));
        notify((int)$user['id'], 'welcome', 'Welcome to Medi-O!', 'Explore the shop or upload your first prescription.', url('shop.php'));

        // Kick off email (and phone) verification straight away
        [, $demoEmail] = otp_send_email($user);
        $_SESSION['demo_code_email'] = $demoEmail;
        if ($user['phone']) {
            [, $demoPhone] = otp_send_phone($user);
            $_SESSION['demo_code_phone'] = $demoPhone;
        }

        flash('success', 'Account created! Now let\'s verify your contact details.');
        redirect('verify-account.php');
    }
}

$pageTitle = 'Create account';
include __DIR__ . '/components/header.php';
?>
<div class="container py-5">
  <div class="mo-card mo-auth-card p-4 p-md-5" data-reveal>
    <div class="text-center mb-4">
      <h1 class="h3 fw-bold">Create your account</h1>
      <p class="text-muted">Order medicines and skip the queue</p>
    </div>

    <?php if ($error): ?>
      <div class="alert alert-danger py-2"><i class="bi bi-exclamation-triangle me-2"></i><?= e($error) ?></div>
    <?php endif; ?>

    <form method="post" novalidate>
      <?= csrf_field() ?>
      <div class="mb-3">
        <label class="form-label fw-semibold">Full name</label>
        <input type="text" name="name" class="form-control" required value="<?= e($old['name']) ?>" placeholder="Your name">
      </div>
      <div class="mb-3">
        <label class="form-label fw-semibold">Email</label>
        <input type="email" name="email" class="form-control" required value="<?= e($old['email']) ?>" placeholder="you@email.com">
      </div>
      <div class="mb-3">
        <label class="form-label fw-semibold">Mobile number <span class="text-muted fw-normal">(for WhatsApp reminders &amp; phone login)</span></label>
        <input type="tel" name="phone" class="form-control" value="<?= e($old['phone']) ?>" placeholder="07X XXX XXXX">
      </div>
      <div class="row g-3 mb-4">
        <div class="col-sm-6">
          <label class="form-label fw-semibold">Password</label>
          <input type="password" name="password" class="form-control" required minlength="8" placeholder="Min. 8 characters">
        </div>
        <div class="col-sm-6">
          <label class="form-label fw-semibold">Confirm password</label>
          <input type="password" name="confirm" class="form-control" required placeholder="Repeat password">
        </div>
      </div>
      <button class="btn mo-btn w-100 btn-lg">Create account</button>
    </form>

    <?php if (GOOGLE_CLIENT_ID !== ''): ?>
      <div class="mo-divider my-4">or sign up with</div>
      <div id="g_id_onload"
           data-client_id="<?= e(GOOGLE_CLIENT_ID) ?>"
           data-login_uri="<?= url('auth/google.php') ?>"
           data-auto_prompt="false"></div>
      <div class="g_id_signin d-flex justify-content-center" data-type="standard" data-size="large"
           data-theme="outline" data-text="signup_with" data-shape="pill" data-width="320"></div>
      <script src="https://accounts.google.com/gsi/client" async defer></script>
    <?php endif; ?>

    <p class="text-center mt-4 mb-0 text-muted">Already have an account?
      <a href="<?= url('login.php') ?>" class="fw-semibold text-decoration-none">Log in</a>
    </p>
  </div>
</div>
<?php include __DIR__ . '/components/footer.php'; ?>

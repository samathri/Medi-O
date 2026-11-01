<?php
require_once __DIR__ . '/config.php';

if (is_logged_in()) {
    redirect(role_home(current_user()));
}

$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    csrf_check();
    $identifier = trim($_POST['identifier'] ?? '');
    $password = $_POST['password'] ?? '';

    if ($identifier === '' || $password === '') {
        $error = 'Please enter your email/phone and password.';
    } else {
        // Accept either an email address or a Sri Lankan mobile number
        if (filter_var($identifier, FILTER_VALIDATE_EMAIL)) {
            $user = q_one('SELECT * FROM users WHERE email = ?', [strtolower($identifier)]);
        } else {
            $phone = normalize_phone($identifier);
            $user = $phone ? q_one('SELECT * FROM users WHERE phone = ?', [$phone]) : null;
        }

        if ($user && $user['password_hash'] && password_verify($password, $user['password_hash'])) {
            login_user($user);
            flash('success', 'Welcome back, ' . $user['name'] . '!');
            if (!$user['email_verified'] && !$user['phone_verified'] && $user['role'] === 'customer') {
                flash('info', 'Your account isn\'t verified yet — verify to place orders.');
                unset($_SESSION['after_login']);
                redirect('verify-account.php');
            }
            $after = $_SESSION['after_login'] ?? '';
            unset($_SESSION['after_login']);
            redirect($after !== '' ? $after : role_home($user));
        }
        $error = $user && !$user['password_hash']
            ? 'This account uses Google Sign-In. Please use the Google button below.'
            : 'Incorrect email/phone or password.';
    }
}

$pageTitle = 'Log in';
include __DIR__ . '/components/header.php';
?>
<div class="container py-5">
  <div class="mo-card mo-auth-card p-4 p-md-5" data-reveal>
    <div class="text-center mb-4">
      <span class="mo-logo mb-3" style="width:56px;height:56px;font-size:1.6rem"><i class="bi bi-capsule"></i></span>
      <h1 class="h3 fw-bold mt-3">Welcome back</h1>
      <p class="text-muted">Log in with your email or mobile number</p>
    </div>

    <?php if ($error): ?>
      <div class="alert alert-danger py-2"><i class="bi bi-exclamation-triangle me-2"></i><?= e($error) ?></div>
    <?php endif; ?>

    <form method="post" novalidate>
      <?= csrf_field() ?>
      <div class="mb-3">
        <label class="form-label fw-semibold">Email or mobile number</label>
        <input type="text" name="identifier" class="form-control form-control-lg" required
               placeholder="you@email.com or 07X XXX XXXX" value="<?= e($_POST['identifier'] ?? '') ?>" autofocus>
      </div>
      <div class="mb-2">
        <label class="form-label fw-semibold">Password</label>
        <div class="position-relative">
          <input type="password" name="password" id="loginPw" class="form-control form-control-lg" required placeholder="••••••••">
          <button type="button" class="btn position-absolute end-0 top-50 translate-middle-y me-1" data-toggle-password="#loginPw"><i class="bi bi-eye"></i></button>
        </div>
      </div>
      <div class="d-flex justify-content-end mb-3">
        <a href="<?= url('forgot-password.php') ?>" class="small text-decoration-none">Forgot password?</a>
      </div>
      <button class="btn mo-btn w-100 btn-lg">Log in <i class="bi bi-arrow-right ms-1"></i></button>
    </form>

    <?php if (GOOGLE_CLIENT_ID !== ''): ?>
      <div class="mo-divider my-4">or continue with</div>
      <div id="g_id_onload"
           data-client_id="<?= e(GOOGLE_CLIENT_ID) ?>"
           data-login_uri="<?= url('auth/google.php') ?>"
           data-auto_prompt="false"></div>
      <div class="g_id_signin d-flex justify-content-center" data-type="standard" data-size="large"
           data-theme="outline" data-text="continue_with" data-shape="pill" data-width="320"></div>
      <script src="https://accounts.google.com/gsi/client" async defer></script>
    <?php endif; ?>

    <p class="text-center mt-4 mb-0 text-muted">New to Medi-O?
      <a href="<?= url('signup.php') ?>" class="fw-semibold text-decoration-none">Create an account</a>
    </p>
  </div>
</div>
<?php include __DIR__ . '/components/footer.php'; ?>

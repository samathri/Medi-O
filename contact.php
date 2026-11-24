<?php
require_once __DIR__ . '/config.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    csrf_check();
    $name = trim($_POST['name'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $subject = trim($_POST['subject'] ?? '');
    $message = trim($_POST['message'] ?? '');

    if ($name === '' || !filter_var($email, FILTER_VALIDATE_EMAIL) || $message === '') {
        flash('error', 'Please fill in your name, a valid email, and a message.');
    } else {
        q('INSERT INTO contact_messages (name, email, subject, message) VALUES (?,?,?,?)',
            [$name, $email, $subject ?: null, $message]);
        flash('success', 'Thanks! Your message has been received — we\'ll get back to you soon.');
    }
    redirect('contact.php');
}

$pageTitle = 'Contact';
include __DIR__ . '/components/header.php';
?>
<div class="container py-4 py-md-5">
  <div class="page-head pt-2 text-center">
    <h1 class="h2">Get in touch</h1>
    <p class="text-muted">Questions about medicines, orders, or prescriptions? We're here to help.</p>
  </div>

  <div class="row g-4 justify-content-center">
    <div class="col-lg-5" data-reveal>
      <div class="mo-card p-4 h-100">
        <h2 class="h6 fw-bold text-uppercase text-muted mb-3">Quick channels</h2>
        <div class="d-grid gap-2 mb-4">
          <a class="btn mo-btn text-start" style="white-space:normal"
             href="<?= url('chat.php') ?>">
            <i class="bi bi-chat-dots me-2"></i>Chat with a pharmacist — fastest reply</a>
          <a class="btn btn-outline-secondary text-start" href="tel:<?= e(setting('pharmacy_phone')) ?>">
            <i class="bi bi-telephone me-2"></i><?= e(setting('pharmacy_phone')) ?></a>
        </div>
        <div class="small text-muted">
          <div class="mb-2"><i class="bi bi-geo-alt me-2"></i><?= e(setting('pharmacy_address')) ?></div>
          <div><i class="bi bi-clock me-2"></i><?= e(setting('pharmacy_hours')) ?></div>
        </div>
      </div>
    </div>
    <div class="col-lg-6" data-reveal>
      <div class="mo-card p-4">
        <h2 class="h6 fw-bold text-uppercase text-muted mb-3">Send a message</h2>
        <form method="post">
          <?= csrf_field() ?>
          <div class="row g-3">
            <div class="col-sm-6"><input name="name" class="form-control" placeholder="Your name" required></div>
            <div class="col-sm-6"><input type="email" name="email" class="form-control" placeholder="Your email" required></div>
            <div class="col-12"><input name="subject" class="form-control" placeholder="Subject (optional)"></div>
            <div class="col-12"><textarea name="message" class="form-control" rows="5" placeholder="How can we help?" required></textarea></div>
          </div>
          <button class="btn mo-btn mt-3"><i class="bi bi-send me-2"></i>Send message</button>
        </form>
      </div>
    </div>
  </div>
</div>
<?php include __DIR__ . '/components/footer.php'; ?>

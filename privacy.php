<?php
require_once __DIR__ . '/config.php';
$pageTitle = 'Privacy policy';
include __DIR__ . '/components/header.php';
?>
<div class="container py-4 py-md-5">
  <div class="row justify-content-center">
    <div class="col-lg-8">
      <div class="page-head pt-2"><h1 class="h2">Privacy policy</h1>
        <p class="text-muted">Last updated: <?= date('j F Y') ?></p></div>
      <div class="mo-card p-4 p-md-5">
        <h2 class="h5 fw-bold">What we collect</h2>
        <p class="text-secondary">Your account details (name, email, phone number), delivery address, order
          history, uploaded prescriptions, medication schedules, and chat messages with our pharmacy team.
          If you sign in with Google we receive your name, email and profile picture from Google.</p>

        <h2 class="h5 fw-bold mt-4">How we use it</h2>
        <ul class="text-secondary">
          <li>To process orders and verify prescriptions with a licensed pharmacist.</li>
          <li>To send order updates and medicine reminders by email and WhatsApp (only if you opt in).</li>
          <li>To operate your personal QR collection card and keep a record of handed-over medicines.</li>
          <li>To answer your questions in chat.</li>
        </ul>

        <h2 class="h5 fw-bold mt-4">How we protect it</h2>
        <ul class="text-secondary">
          <li>Passwords are stored using strong one-way hashing (bcrypt) — we never see them.</li>
          <li>Prescription files are stored outside public access and only viewable by you and pharmacy staff.</li>
          <li>Payments are handled by PayHere — your card details never touch our servers.</li>
          <li>QR codes contain only random tokens, never your personal information.</li>
        </ul>

        <h2 class="h5 fw-bold mt-4">Your rights</h2>
        <p class="text-secondary">You can update your details anytime in your profile, opt out of WhatsApp
          messages, or ask us to delete your account and data entirely by contacting us.</p>

        <h2 class="h5 fw-bold mt-4">Contact</h2>
        <p class="text-secondary mb-0"><?= e(setting('pharmacy_name')) ?>, <?= e(setting('pharmacy_address')) ?> ·
          <?= e(setting('pharmacy_phone')) ?></p>
      </div>
    </div>
  </div>
</div>
<?php include __DIR__ . '/components/footer.php'; ?>

<?php
require_once __DIR__ . '/config.php';
$pageTitle = 'Terms of service';
include __DIR__ . '/components/header.php';
?>
<div class="container py-4 py-md-5">
  <div class="row justify-content-center">
    <div class="col-lg-8">
      <div class="page-head pt-2"><h1 class="h2">Terms of service</h1>
        <p class="text-muted">Last updated: <?= date('j F Y') ?></p></div>
      <div class="mo-card p-4 p-md-5">
        <h2 class="h5 fw-bold">1. Using Medi-O</h2>
        <p class="text-secondary">Medi-O is an online ordering and prescription-management service operated by
          <?= e(setting('pharmacy_name')) ?>. By creating an account you confirm the information you provide is
          accurate and that you are legally allowed to purchase the medicines you order.</p>

        <h2 class="h5 fw-bold mt-4">2. Prescription medicines</h2>
        <p class="text-secondary">Prescription-only medicines are dispensed strictly after a licensed
          pharmacist verifies a valid prescription. We may reject prescriptions that are unclear, expired,
          or altered. Uploading forged prescriptions is illegal and will be reported.</p>

        <h2 class="h5 fw-bold mt-4">3. Orders, payment & pickup</h2>
        <p class="text-secondary">Prices are shown in Sri Lankan Rupees. Online payments are processed by
          PayHere. Orders marked "ready" should be collected within 7 days. Your QR card and order QRs act
          as your claim ticket — keep them private.</p>

        <h2 class="h5 fw-bold mt-4">4. Reminders are not medical advice</h2>
        <p class="text-secondary">The medication tracker and its reminders are convenience tools, not a
          substitute for professional medical advice. Always follow your doctor's instructions. Contact your
          doctor immediately in case of adverse reactions.</p>

        <h2 class="h5 fw-bold mt-4">5. Account & termination</h2>
        <p class="text-secondary">You are responsible for keeping your login credentials safe. We may suspend
          accounts that abuse the service, attempt fraud, or harass our staff.</p>

        <h2 class="h5 fw-bold mt-4">6. Liability</h2>
        <p class="text-secondary mb-0">We take utmost care in dispensing medicines. Our liability is limited
          to the value of the order concerned, to the extent permitted by law.</p>
      </div>
    </div>
  </div>
</div>
<?php include __DIR__ . '/components/footer.php'; ?>

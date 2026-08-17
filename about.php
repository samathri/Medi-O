<?php
require_once __DIR__ . '/config.php';
$pageTitle = 'About us';
include __DIR__ . '/components/header.php';
?>
<section class="mo-hero">
  <div class="container text-center py-3">
    <span class="mo-eyebrow">About Medi-O</span>
    <h1 class="display-5 fw-bold mt-3">Healthcare without the <span class="text-gradient">waiting line</span></h1>
    <p class="lead text-secondary mx-auto mt-3" style="max-width:640px">Medi-O was born from a simple
      observation: nobody should stand in a queue for 30 minutes to buy their monthly medicines.</p>
  </div>
</section>

<section class="container py-5">
  <div class="row g-4 align-items-center">
    <div class="col-lg-6" data-reveal>
      <img src="<?= url('assets/img/About-hero.jpg') ?>" class="img-fluid rounded-4 shadow" alt="Our pharmacy"
           onerror="this.src='<?= url('assets/img/hero.jpg') ?>'">
    </div>
    <div class="col-lg-6" data-reveal>
      <h2 class="mo-section-title">Our story</h2>
      <p class="text-secondary">Watching elderly parents and busy families wait in long pharmacy queues —
        sometimes just to hand over the same monthly prescription — we decided there had to be a better way.
        Medi-O connects you with our licensed pharmacy digitally: order online, get your prescriptions
        verified by a real pharmacist, and collect your medicines with one QR scan.</p>
      <div class="row g-3 mt-2">
        <?php foreach ([
            ['bi-patch-check', 'Licensed pharmacists', 'Every prescription is reviewed by a professional.'],
            ['bi-qr-code-scan', 'QR-based pickup', 'Verified, secure, and takes seconds at the counter.'],
            ['bi-whatsapp', 'WhatsApp care', 'Reminders and updates right where you already chat.'],
            ['bi-shield-lock', 'Private & secure', 'Your medical data is encrypted and access-controlled.'],
        ] as [$icon, $title, $text]): ?>
          <div class="col-sm-6">
            <div class="d-flex gap-3">
              <span class="mo-step-icon flex-shrink-0" style="width:44px;height:44px;font-size:1.1rem"><i class="bi <?= $icon ?>"></i></span>
              <div>
                <div class="fw-bold"><?= $title ?></div>
                <div class="small text-secondary"><?= $text ?></div>
              </div>
            </div>
          </div>
        <?php endforeach; ?>
      </div>
    </div>
  </div>
</section>

<section class="py-5" style="background:#fff">
  <div class="container text-center py-3" data-reveal>
    <h2 class="mo-section-title mb-4">Visit our pharmacy</h2>
    <div class="row g-3 justify-content-center">
      <div class="col-md-4"><div class="mo-step h-100"><i class="bi bi-geo-alt fs-3" style="color:var(--mo-primary)"></i><div class="fw-semibold mt-2"><?= e(setting('pharmacy_address')) ?></div></div></div>
      <div class="col-md-4"><div class="mo-step h-100"><i class="bi bi-clock fs-3" style="color:var(--mo-primary)"></i><div class="fw-semibold mt-2"><?= e(setting('pharmacy_hours')) ?></div></div></div>
      <div class="col-md-4"><div class="mo-step h-100"><i class="bi bi-telephone fs-3" style="color:var(--mo-primary)"></i><div class="fw-semibold mt-2"><?= e(setting('pharmacy_phone')) ?></div></div></div>
    </div>
  </div>
</section>
<?php include __DIR__ . '/components/footer.php'; ?>

<?php
require_once __DIR__ . '/config.php';

$categories = q_all('SELECT c.*, COUNT(p.id) AS cnt FROM categories c
                     LEFT JOIN products p ON p.category_id = c.id AND p.is_active = 1
                     GROUP BY c.id HAVING cnt > 0 ORDER BY cnt DESC LIMIT 8');
$featured = q_all('SELECT * FROM products WHERE is_active = 1 AND stock > 0 ORDER BY rating DESC, id DESC LIMIT 8');
$newest = q_all('SELECT * FROM products WHERE is_active = 1 ORDER BY id DESC LIMIT 4');

$pageTitle = 'Home';
include __DIR__ . '/components/header.php';
?>
<!-- Hero -->
<section class="mo-hero">
  <div class="container">
    <div class="row align-items-center g-5">
      <div class="col-lg-6" data-reveal>
        <span class="mo-eyebrow mb-3"><i class="bi bi-lightning-charge-fill me-1"></i>Skip the queue</span>
        <h1 class="display-4 mt-3">Your pharmacy,<br>in your <span class="text-gradient">pocket</span>.</h1>
        <p class="lead text-secondary mt-3 pe-lg-5">Order medicines online, upload prescriptions for
          pharmacist approval, get WhatsApp reminders — and collect in seconds with your personal QR card.</p>
        <div class="d-flex flex-wrap gap-3 mt-4">
          <a href="<?= url('shop.php') ?>" class="btn mo-btn btn-lg"><i class="bi bi-bag me-2"></i>Shop medicines</a>
          <a href="<?= url('prescriptions.php') ?>" class="btn mo-btn-outline btn-lg"><i class="bi bi-file-medical me-2"></i>Upload prescription</a>
        </div>
        <div class="d-flex flex-wrap gap-4 mt-4 small text-secondary">
          <span><i class="bi bi-patch-check-fill text-success me-1"></i>Pharmacist verified</span>
          <span><i class="bi bi-qr-code text-success me-1"></i>QR pickup</span>
          <span><i class="bi bi-whatsapp text-success me-1"></i>WhatsApp reminders</span>
        </div>
      </div>
      <div class="col-lg-6 position-relative d-none d-lg-block">
        <img src="<?= url('assets/img/hero.jpg') ?>" alt="Pharmacist at work" class="mo-hero-img img-fluid mo-float" style="width:100%;max-height:430px;object-fit:cover">
        <div class="mo-hero-chip" style="top:8%;left:-12px"><i class="bi bi-check-circle-fill text-success fs-5"></i> Prescription approved</div>
        <div class="mo-hero-chip" style="bottom:12%;right:-6px"><i class="bi bi-qr-code-scan text-primary fs-5"></i> Scan &amp; collect</div>
      </div>
    </div>
  </div>
</section>

<!-- Categories -->
<section class="container py-5">
  <div class="d-flex justify-content-between align-items-end mb-4" data-reveal>
    <div>
      <span class="mo-eyebrow">Categories</span>
      <h2 class="mo-section-title mt-2 mb-0">Browse by category</h2>
    </div>
    <a href="<?= url('shop.php') ?>" class="text-decoration-none fw-semibold d-none d-sm-block">View all <i class="bi bi-arrow-right"></i></a>
  </div>
  <div class="row g-3 row-cols-2 row-cols-md-4 row-cols-lg-4" data-reveal>
    <?php foreach ($categories as $c): ?>
      <div class="col">
        <a class="mo-cat" href="<?= url('shop.php?category=' . urlencode($c['name'])) ?>">
          <i class="bi <?= e($c['icon']) ?>"></i>
          <span><?= e($c['name']) ?></span>
          <small class="text-muted"><?= (int)$c['cnt'] ?> items</small>
        </a>
      </div>
    <?php endforeach; ?>
  </div>
</section>

<!-- How it works -->
<section class="py-5" style="background:#fff">
  <div class="container py-2">
    <div class="text-center mb-5" data-reveal>
      <span class="mo-eyebrow">How it works</span>
      <h2 class="mo-section-title mt-2">Medicines in 4 easy steps</h2>
    </div>
    <div class="row g-4">
      <?php
      $steps = [
          ['bi-cart-plus', 'Order or upload', 'Add products to your cart, or upload a photo of your prescription.'],
          ['bi-patch-check', 'Pharmacist verifies', 'Our licensed pharmacist reviews and approves your prescription.'],
          ['bi-credit-card', 'Pay securely', 'Pay online with PayHere or choose cash on pickup.'],
          ['bi-qr-code-scan', 'Scan &amp; collect', 'Show your personal QR card at the counter — no waiting in line.'],
      ];
      foreach ($steps as $i => [$icon, $title, $text]): ?>
        <div class="col-md-6 col-lg-3" data-reveal>
          <div class="mo-step">
            <span class="mo-step-num">0<?= $i + 1 ?></span>
            <span class="mo-step-icon"><i class="bi <?= $icon ?>"></i></span>
            <h3 class="h6 fw-bold"><?= $title ?></h3>
            <p class="small text-secondary mb-0"><?= $text ?></p>
          </div>
        </div>
      <?php endforeach; ?>
    </div>
  </div>
</section>

<!-- Featured products -->
<section class="container py-5">
  <div class="d-flex justify-content-between align-items-end mb-4" data-reveal>
    <div>
      <span class="mo-eyebrow">Top rated</span>
      <h2 class="mo-section-title mt-2 mb-0">Popular right now</h2>
    </div>
    <a href="<?= url('shop.php?sort=rating') ?>" class="text-decoration-none fw-semibold d-none d-sm-block">See more <i class="bi bi-arrow-right"></i></a>
  </div>
  <div class="row g-3 g-md-4 row-cols-2 row-cols-md-3 row-cols-lg-4">
    <?php foreach ($featured as $p): ?>
      <div class="col" data-reveal>
        <?php include __DIR__ . '/components/product-card.php'; ?>
      </div>
    <?php endforeach; ?>
  </div>
</section>

<!-- Services banner -->
<section class="container pb-5">
  <div class="row g-4">
    <div class="col-lg-6" data-reveal>
      <div class="mo-card p-4 p-md-5 h-100" style="background:linear-gradient(135deg,#28506e,#4a7fa5);color:#fff;border:0">
        <i class="bi bi-alarm fs-1"></i>
        <h3 class="fw-bold mt-3">Never miss a dose again</h3>
        <p class="opacity-75">Set up your medication schedule and we'll remind you on WhatsApp when
          it's time to take your medicine.</p>
        <a href="<?= url('medications.php') ?>" class="btn btn-light fw-bold rounded-pill px-4">Start tracking</a>
      </div>
    </div>
    <div class="col-lg-6" data-reveal>
      <div class="mo-card p-4 p-md-5 h-100" style="background:linear-gradient(135deg,#1e293b,#334155);color:#fff;border:0">
        <i class="bi bi-chat-dots fs-1"></i>
        <h3 class="fw-bold mt-3">Talk to a real pharmacist</h3>
        <p class="opacity-75">Questions about your medicines? Chat with our pharmacy team directly
          from your account — or message us on WhatsApp.</p>
        <a href="<?= url('chat.php') ?>" class="btn btn-light fw-bold rounded-pill px-4">Open chat</a>
      </div>
    </div>
  </div>
</section>
<?php include __DIR__ . '/components/footer.php'; ?>

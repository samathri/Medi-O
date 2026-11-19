<?php
require_once __DIR__ . '/config.php';

$categories = q_all('SELECT c.*, COUNT(p.id) AS cnt FROM categories c
                     JOIN products p ON p.category_id = c.id AND p.is_active = 1
                     GROUP BY c.id ORDER BY cnt DESC');
$productCount = (int)q_val('SELECT COUNT(*) FROM products WHERE is_active = 1');
$vitamins = q_all("SELECT p.image_path FROM products p JOIN categories c ON c.id = p.category_id
                   WHERE p.is_active = 1 AND c.name = 'Vitamins & Supplements' ORDER BY p.rating DESC LIMIT 3");
$featured = q_all('SELECT p.*, c.name AS category_name FROM products p LEFT JOIN categories c ON c.id = p.category_id
                   WHERE p.is_active = 1 AND p.stock > 0 AND p.requires_prescription = 0
                   ORDER BY p.rating DESC, p.id DESC LIMIT 10');
$medicines = q_all("SELECT p.*, c.name AS category_name FROM products p JOIN categories c ON c.id = p.category_id
                    WHERE p.is_active = 1 AND c.name IN ('Medicines','Cold, Cough & Allergy','Digestive Health')
                    ORDER BY p.rating DESC LIMIT 5");
$newest = q_all('SELECT p.*, c.name AS category_name FROM products p LEFT JOIN categories c ON c.id = p.category_id
                 WHERE p.is_active = 1 AND p.stock > 0 ORDER BY p.id DESC LIMIT 5');

$pageTitle = 'Online pharmacy';
include __DIR__ . '/components/header.php';

function product_row(array $list, string $title, string $link): void { ?>
  <section class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
      <h2 class="mo-section-title mb-0"><?= e($title) ?></h2>
      <a href="<?= $link ?>" class="fw-bold text-decoration-none">View all <i class="bi bi-chevron-right small"></i></a>
    </div>
    <div class="row g-3 row-cols-2 row-cols-md-3 row-cols-lg-5">
      <?php foreach ($list as $p): ?>
        <div class="col"><?php include __DIR__ . '/components/product-card.php'; ?></div>
      <?php endforeach; ?>
    </div>
  </section>
<?php }
?>
<section class="container gx-hero">
  <div class="row g-3">
    <div class="col-lg-8">
      <div class="gx-banner">
        <span class="gx-off">WELLNESS WEEK</span>
        <h1>Vitamins &amp; supplements for the whole family</h1>
        <p class="mt-2 mb-4">Immunity, energy and everyday health — genuine brands, checked by our pharmacists.</p>
        <a href="<?= url('shop.php?category=' . urlencode('Vitamins & Supplements')) ?>" class="btn mo-btn-yellow btn-lg align-self-start position-relative">Shop vitamins</a>
        <div class="gx-banner-imgs">
          <?php foreach ($vitamins as $v): ?><img src="<?= product_image($v['image_path']) ?>" alt=""><?php endforeach; ?>
        </div>
      </div>
    </div>
    <div class="col-lg-4">
      <div class="gx-rx">
        <i class="bi bi-file-earmark-medical"></i>
        <h3>Upload your prescription</h3>
        <p>Send a photo of your doctor's prescription. Our pharmacist prepares the order — collect it with your QR code.</p>
        <a href="<?= url('prescriptions.php') ?>" class="btn mo-btn mo-btn-signal mt-3">Upload now</a>
      </div>
    </div>
  </div>
  <div class="gx-perks">
    <div><i class="bi bi-patch-check"></i><p class="mb-0"><b>Genuine products</b><span><?= number_format($productCount) ?> items in stock</span></p></div>
    <div><i class="bi bi-person-badge"></i><p class="mb-0"><b>Pharmacist checked</b><span>Every prescription reviewed</span></p></div>
    <div><i class="bi bi-qr-code-scan"></i><p class="mb-0"><b>QR pickup</b><span>Collect in seconds</span></p></div>
    <div><i class="bi bi-bell"></i><p class="mb-0"><b>Dose reminders</b><span>In the app and by email</span></p></div>
  </div>
</section>

<section class="container py-4">
  <div class="d-flex justify-content-between align-items-center mb-3">
    <h2 class="mo-section-title mb-0">Shop by category</h2>
    <a href="<?= url('shop.php') ?>" class="fw-bold text-decoration-none">All products <i class="bi bi-chevron-right small"></i></a>
  </div>
  <div class="gx-cats">
    <?php foreach ($categories as $c): ?>
      <a class="gx-cat" href="<?= url('shop.php?category=' . urlencode($c['name'])) ?>">
        <i class="bi <?= e($c['icon']) ?>"></i><?= e($c['name']) ?><small><?= (int)$c['cnt'] ?> items</small>
      </a>
    <?php endforeach; ?>
  </div>
</section>

<?php product_row($featured, 'Best sellers', url('shop.php?sort=rating')); ?>

<section class="container py-3">
  <div class="row g-3">
    <div class="col-md-4"><a class="gx-promo g" href="<?= url('medications.php') ?>"><i class="bi bi-alarm"></i><h3>Medication reminders</h3><p>Get a reminder in the app and by email when it's time for your dose.</p><span>Set up now →</span></a></div>
    <div class="col-md-4"><a class="gx-promo y" href="<?= url('chat.php') ?>"><i class="bi bi-chat-dots"></i><h3>Ask a pharmacist</h3><p>Questions about a medicine? Chat with our team.</p><span>Start chat →</span></a></div>
    <div class="col-md-4"><a class="gx-promo r" href="<?= url('my-qr.php') ?>"><i class="bi bi-qr-code"></i><h3>Your QR pickup card</h3><p>Show it at the counter and skip the queue.</p><span>View card →</span></a></div>
  </div>
</section>

<?php if ($medicines) product_row($medicines, 'Medicines & remedies', url('shop.php?category=Medicines')); ?>
<?php product_row($newest, 'New arrivals', url('shop.php?sort=newest')); ?>
<div class="pb-4"></div>
<?php include __DIR__ . '/components/footer.php'; ?>

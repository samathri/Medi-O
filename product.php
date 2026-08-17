<?php
require_once __DIR__ . '/config.php';

$id = (int)($_GET['id'] ?? 0);
$p = q_one('SELECT p.*, c.name AS category_name FROM products p
            LEFT JOIN categories c ON c.id = p.category_id
            WHERE p.id = ? AND p.is_active = 1', [$id]);
if (!$p) {
    http_response_code(404);
    flash('error', 'Product not found.');
    redirect('shop.php');
}

$related = q_all('SELECT * FROM products WHERE category_id <=> ? AND id <> ? AND is_active = 1 ORDER BY RAND() LIMIT 4',
    [$p['category_id'], $p['id']]);

$pageTitle = $p['name'];
include __DIR__ . '/components/header.php';
?>
<div class="container py-4 py-md-5">
  <nav aria-label="breadcrumb" class="mb-3">
    <ol class="breadcrumb small">
      <li class="breadcrumb-item"><a href="<?= url('shop.php') ?>" class="text-decoration-none">Shop</a></li>
      <?php if ($p['category_name']): ?>
        <li class="breadcrumb-item"><a href="<?= url('shop.php?category=' . urlencode($p['category_name'])) ?>" class="text-decoration-none"><?= e($p['category_name']) ?></a></li>
      <?php endif; ?>
      <li class="breadcrumb-item active text-truncate" style="max-width:220px"><?= e($p['name']) ?></li>
    </ol>
  </nav>

  <div class="row g-4 g-lg-5">
    <div class="col-md-5" data-reveal>
      <div class="mo-card p-3 position-relative">
        <img src="<?= product_image($p['image_path']) ?>" class="w-100" style="aspect-ratio:1/1;object-fit:contain" alt="<?= e($p['name']) ?>">
        <?php if ($p['requires_prescription']): ?>
          <span class="mo-chip-rx position-absolute top-0 start-0 m-3 fs-6"><i class="bi bi-file-medical me-1"></i>Prescription required</span>
        <?php endif; ?>
      </div>
    </div>
    <div class="col-md-7" data-reveal>
      <?php if ($p['category_name']): ?><span class="mo-eyebrow mb-2"><?= e($p['category_name']) ?></span><?php endif; ?>
      <h1 class="h3 fw-bold mt-2"><?= e($p['name']) ?></h1>
      <div class="mo-rating fs-6 mb-2">
        <?php $r = (float)$p['rating'];
        for ($i = 1; $i <= 5; $i++): ?><i class="bi <?= $r >= $i - .25 ? 'bi-star-fill' : ($r >= $i - .75 ? 'bi-star-half' : 'bi-star') ?>"></i><?php endfor; ?>
        <span class="text-muted ms-1"><?= number_format($r, 1) ?> rating</span>
      </div>
      <div class="display-6 fw-bold" style="color:var(--mo-primary-dark)"><?= money((float)$p['price']) ?></div>

      <div class="my-3">
        <?php if ((int)$p['stock'] > 5): ?>
          <span class="mo-status mo-status-ready"><i class="bi bi-check-circle me-1"></i>In stock</span>
        <?php elseif ((int)$p['stock'] > 0): ?>
          <span class="mo-status mo-status-pending"><i class="bi bi-exclamation-circle me-1"></i>Only <?= (int)$p['stock'] ?> left</span>
        <?php else: ?>
          <span class="mo-status mo-status-cancelled"><i class="bi bi-x-circle me-1"></i>Out of stock</span>
        <?php endif; ?>
      </div>

      <?php if ($p['description']): ?>
        <p class="text-secondary" style="white-space:pre-line"><?= e($p['description']) ?></p>
      <?php endif; ?>

      <?php if ($p['requires_prescription']): ?>
        <div class="alert alert-warning d-flex gap-2 align-items-start">
          <i class="bi bi-file-medical fs-5"></i>
          <div class="small">This medicine needs a valid prescription. Add it to your cart and
            <a href="<?= url('prescriptions.php') ?>">upload your prescription</a> — our pharmacist will verify
            it before the order is prepared.</div>
        </div>
      <?php endif; ?>

      <?php if ((int)$p['stock'] > 0): ?>
        <div class="d-flex flex-wrap align-items-center gap-3 mt-4">
          <div class="mo-qty">
            <button type="button" data-step="-1" aria-label="Decrease">−</button>
            <input type="number" id="qtyInput" value="1" min="1" max="<?= (int)$p['stock'] ?>">
            <button type="button" data-step="1" aria-label="Increase">+</button>
          </div>
          <button class="btn mo-btn btn-lg" data-add-to-cart="<?= $p['id'] ?>"><i class="bi bi-bag-plus me-2"></i>Add to cart</button>
        </div>
      <?php endif; ?>

      <div class="d-flex flex-wrap gap-4 mt-4 pt-3 border-top small text-secondary">
        <span><i class="bi bi-shield-check text-success me-1"></i>Genuine products</span>
        <span><i class="bi bi-qr-code text-success me-1"></i>QR pickup available</span>
        <span><i class="bi bi-chat-dots text-success me-1"></i><a href="<?= url('chat.php') ?>" class="text-decoration-none">Ask a pharmacist</a></span>
      </div>
    </div>
  </div>

  <?php if ($related): ?>
    <section class="mt-5">
      <h2 class="h4 fw-bold mb-4">You may also need</h2>
      <div class="row g-3 g-md-4 row-cols-2 row-cols-md-4">
        <?php foreach ($related as $p): ?>
          <div class="col"><?php include __DIR__ . '/components/product-card.php'; ?></div>
        <?php endforeach; ?>
      </div>
    </section>
  <?php endif; ?>
</div>
<?php include __DIR__ . '/components/footer.php'; ?>

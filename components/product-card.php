<?php /** Expects $p = product row. */ ?>
<div class="mo-product">
  <a href="<?= url('product.php?id=' . $p['id']) ?>" class="position-relative d-block">
    <img class="mo-product-img" src="<?= product_image($p['image_path']) ?>" alt="<?= e($p['name']) ?>" loading="lazy">
    <?php if ($p['requires_prescription']): ?>
      <span class="mo-chip-rx position-absolute top-0 start-0 m-2"><i class="bi bi-file-medical me-1"></i>Rx</span>
    <?php endif; ?>
  </a>
  <div class="mo-product-body">
    <a class="mo-product-name" href="<?= url('product.php?id=' . $p['id']) ?>"><?= e($p['name']) ?></a>
    <div class="mo-rating">
      <?php $r = (float)$p['rating'];
      for ($i = 1; $i <= 5; $i++): ?><i class="bi <?= $r >= $i - .25 ? 'bi-star-fill' : ($r >= $i - .75 ? 'bi-star-half' : 'bi-star') ?>"></i><?php endfor; ?>
      <span class="text-muted ms-1"><?= number_format($r, 1) ?></span>
    </div>
    <div class="d-flex justify-content-between align-items-center mt-auto pt-1">
      <span class="mo-price"><?= money((float)$p['price']) ?></span>
      <?php if ((int)$p['stock'] > 0): ?>
        <button class="mo-btn-soft btn btn-sm" data-add-to-cart="<?= $p['id'] ?>" aria-label="Add to cart"><i class="bi bi-bag-plus"></i></button>
      <?php else: ?>
        <span class="mo-chip-stock text-danger">Out of stock</span>
      <?php endif; ?>
    </div>
  </div>
</div>

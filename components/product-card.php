<?php /** Expects $p = product row (category_name optional). */ ?>
<article class="mo-product">
  <a href="<?= url('product.php?id=' . $p['id']) ?>" class="mo-product-media" tabindex="-1" aria-hidden="true">
    <img class="mo-product-img" src="<?= product_image($p['image_path']) ?>" alt="" loading="lazy">
    <?php if ($p['requires_prescription']): ?>
      <span class="mo-chip-rx position-absolute top-0 start-0 m-2">Rx only</span>
    <?php endif; ?>
  </a>
  <div class="mo-product-body">
    <?php if (!empty($p['category_name'])): ?><span class="mo-product-cat"><?= e($p['category_name']) ?></span><?php endif; ?>
    <a class="mo-product-name" href="<?= url('product.php?id=' . $p['id']) ?>"><?= e($p['name']) ?></a>
    <div class="mo-rating" aria-label="Rated <?= number_format((float)$p['rating'], 1) ?> of 5">
      <?php $r = (float)$p['rating'];
      for ($i = 1; $i <= 5; $i++): ?><i class="bi <?= $r >= $i - .25 ? 'bi-star-fill' : ($r >= $i - .75 ? 'bi-star-half' : 'bi-star') ?>"></i><?php endfor; ?>
      <span class="text-muted ms-1"><?= number_format($r, 1) ?></span>
    </div>
    <div class="mo-product-foot">
      <span class="mo-price"><?= money((float)$p['price']) ?></span>
      <?php if ((int)$p['stock'] > 0): ?>
        <button class="mo-add" data-add-to-cart="<?= $p['id'] ?>" aria-label="Add <?= e($p['name']) ?> to cart"><i class="bi bi-plus-lg"></i>Add</button>
      <?php else: ?>
        <span class="mo-chip-stock" style="color:var(--signal)">Out of stock</span>
      <?php endif; ?>
    </div>
  </div>
</article>

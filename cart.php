<?php
require_once __DIR__ . '/config.php';
$u = require_login();

$items = q_all('SELECT ci.id, ci.quantity, p.id AS product_id, p.name, p.price, p.stock, p.image_path, p.requires_prescription
                FROM cart_items ci JOIN products p ON p.id = ci.product_id
                WHERE ci.user_id = ? ORDER BY ci.id DESC', [$u['id']]);
$subtotal = array_sum(array_map(fn($i) => $i['price'] * $i['quantity'], $items));
$shipping = $subtotal >= (float)setting('free_shipping_over', '5000') ? 0 : (float)setting('shipping_fee', '250');
$needsRx = (bool)array_filter($items, fn($i) => $i['requires_prescription']);

$pageTitle = 'My cart';
include __DIR__ . '/components/header.php';
?>
<div class="container py-4 py-md-5">
  <div class="page-head pt-0"><h1 class="h2">My cart</h1></div>

  <?php if (!$items): ?>
    <div class="mo-card mo-empty" data-reveal>
      <i class="bi bi-bag"></i>
      <h2 class="h5">Your cart is empty</h2>
      <p>Browse our products and add something to your cart.</p>
      <a class="btn mo-btn" href="<?= url('shop.php') ?>">Go to shop</a>
    </div>
  <?php else: ?>
    <div class="row g-4">
      <div class="col-lg-8" data-reveal>
        <div class="mo-card overflow-hidden">
          <?php foreach ($items as $i): ?>
            <div class="d-flex gap-3 p-3 border-bottom cart-row" data-item="<?= $i['id'] ?>">
              <a href="<?= url('product.php?id=' . $i['product_id']) ?>" class="flex-shrink-0">
                <img src="<?= product_image($i['image_path']) ?>" width="86" height="86" style="object-fit:contain" class="rounded border bg-white" alt="">
              </a>
              <div class="flex-grow-1 min-width-0">
                <div class="d-flex justify-content-between gap-2">
                  <a class="fw-semibold text-decoration-none text-body me-2" href="<?= url('product.php?id=' . $i['product_id']) ?>"><?= e($i['name']) ?></a>
                  <button class="btn btn-sm text-danger p-0" data-remove="<?= $i['id'] ?>" aria-label="Remove"><i class="bi bi-trash"></i></button>
                </div>
                <?php if ($i['requires_prescription']): ?><span class="mo-chip-rx">Prescription required</span><?php endif; ?>
                <div class="d-flex flex-wrap justify-content-between align-items-center mt-2 gap-2">
                  <div class="mo-qty">
                    <button type="button" data-step="-1">−</button>
                    <input type="number" value="<?= (int)$i['quantity'] ?>" min="0" max="<?= (int)$i['stock'] ?>" data-qty="<?= $i['id'] ?>">
                    <button type="button" data-step="1">+</button>
                  </div>
                  <div class="fw-bold" data-line="<?= $i['id'] ?>"><?= money($i['price'] * $i['quantity']) ?></div>
                </div>
              </div>
            </div>
          <?php endforeach; ?>
        </div>
      </div>

      <div class="col-lg-4" data-reveal>
        <div class="mo-card p-4 position-sticky" style="top:90px">
          <h2 class="h5 fw-bold mb-3">Order summary</h2>
          <div class="d-flex justify-content-between mb-2"><span class="text-muted">Subtotal</span><span id="sumSubtotal" class="fw-semibold"><?= money($subtotal) ?></span></div>
          <div class="d-flex justify-content-between mb-2"><span class="text-muted">Delivery / handling</span><span><?= $shipping > 0 ? money($shipping) : 'FREE' ?></span></div>
          <div class="small text-success mb-2"><i class="bi bi-info-circle me-1"></i>Free over <?= money((float)setting('free_shipping_over', '5000')) ?> — pickup is always free</div>
          <hr>
          <div class="d-flex justify-content-between fs-5 fw-bold mb-3"><span>Total</span><span id="sumTotal"><?= money($subtotal + $shipping) ?></span></div>
          <?php if ($needsRx): ?>
            <div class="alert alert-warning small py-2"><i class="bi bi-file-medical me-1"></i>Your cart contains
              prescription medicines — you'll attach a prescription at checkout.</div>
          <?php endif; ?>
          <a href="<?= url('checkout.php') ?>" class="btn mo-btn w-100 btn-lg">Checkout <i class="bi bi-arrow-right ms-1"></i></a>
          <a href="<?= url('shop.php') ?>" class="btn btn-link w-100 text-decoration-none mt-1">Continue shopping</a>
        </div>
      </div>
    </div>
  <?php endif; ?>
</div>

<script>
document.addEventListener('change', async e => {
  const input = e.target.closest('[data-qty]');
  if (!input) return;
  const r = await medioApi('cart.php', {action: 'update', item_id: +input.dataset.qty, qty: +input.value});
  if (!r.ok) return MedioToast(r.error || 'Update failed', 'error');
  if (r.qty === 0) { location.reload(); return; }
  input.value = r.qty;
  document.querySelector(`[data-line="${input.dataset.qty}"]`).textContent = r.line_text;
  refreshTotals(r);
});
document.addEventListener('click', async e => {
  const btn = e.target.closest('[data-remove]');
  if (!btn) return;
  const r = await medioApi('cart.php', {action: 'remove', item_id: +btn.dataset.remove});
  if (r.ok) { MedioToast('Item removed', 'info'); location.reload(); }
});
function refreshTotals(r) {
  updateCartBadge(r.count);
  const free = <?= (float)setting('free_shipping_over', '5000') ?>;
  const fee = <?= (float)setting('shipping_fee', '250') ?>;
  const ship = r.subtotal >= free ? 0 : fee;
  document.getElementById('sumSubtotal').textContent = r.subtotal_text;
  document.getElementById('sumTotal').textContent = 'Rs. ' + (r.subtotal + ship).toLocaleString(undefined, {minimumFractionDigits: 2});
}
</script>
<?php include __DIR__ . '/components/footer.php'; ?>

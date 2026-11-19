<?php
require_once __DIR__ . '/config.php';
$u = require_login();

// Ordering requires a verified contact channel (email or phone)
if (!$u['email_verified'] && !$u['phone_verified']) {
    flash('info', 'One quick step: enter the code we sent to your email or mobile — then you go straight back to checkout.');
    $_SESSION['after_verify'] = url('checkout.php');
    redirect('verify-account.php');
}

$items = q_all('SELECT ci.id, ci.quantity, p.id AS product_id, p.name, p.price, p.stock, p.requires_prescription
                FROM cart_items ci JOIN products p ON p.id = ci.product_id
                WHERE ci.user_id = ? ORDER BY ci.id', [$u['id']]);
if (!$items) {
    flash('info', 'Your cart is empty.');
    redirect('shop.php');
}

$subtotal = array_sum(array_map(fn($i) => $i['price'] * $i['quantity'], $items));
$needsRx = (bool)array_filter($items, fn($i) => $i['requires_prescription']);
$myPrescriptions = q_all("SELECT * FROM prescriptions WHERE user_id=? AND status IN ('pending','approved')
                          ORDER BY id DESC LIMIT 10", [$u['id']]);

$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    csrf_check();
    $delivery = ($_POST['delivery'] ?? 'pickup') === 'delivery' ? 'delivery' : 'pickup';
    $payment = ($_POST['payment'] ?? 'cash') === 'payhere' ? 'payhere' : 'cash';
    $address = trim($_POST['address'] ?? '');
    $note = trim($_POST['note'] ?? '');
    $rxId = (int)($_POST['prescription_id'] ?? 0);
    $rxError = null;
    if ($needsRx && !$rxId) {
        // No saved prescription picked — take the photo uploaded right here
        require_once __DIR__ . '/includes/prescription-upload.php';
        [$rxId, $rxError] = save_prescription_upload($u, $_FILES['prescription_file'] ?? null, 'Uploaded at checkout');
        $rxId = (int)$rxId;
    }

    $shipping = $delivery === 'delivery'
        ? ($subtotal >= (float)setting('free_shipping_over', '5000') ? 0 : (float)setting('shipping_fee', '250'))
        : 0;

    if ($delivery === 'delivery' && $address === '') {
        $error = 'Please enter a delivery address.';
    } elseif ($rxError) {
        $error = $rxError;
    } elseif (($needsRx || $rxId) && !q_one("SELECT id FROM prescriptions WHERE id=? AND user_id=? AND status IN ('pending','approved')", [$rxId, $u['id']])) {
        $error = 'Your cart contains prescription medicines — please select or upload a prescription first.';
    } else {

        // Stock re-check
        foreach ($items as $i) {
            if ($i['quantity'] > $i['stock']) {
                $error = e($i['name']) . ' has only ' . $i['stock'] . ' left in stock. Please adjust your cart.';
                break;
            }
        }

        if ($error === '') {
            $pdo = db();
            $pdo->beginTransaction();
            try {
                // Serialize checkout for this customer and reject a changed/consumed cart.
                q_one('SELECT id FROM users WHERE id=? FOR UPDATE', [$u['id']]);
                $lockedCart = q_all('SELECT id, product_id, quantity FROM cart_items WHERE user_id=? ORDER BY id FOR UPDATE', [$u['id']]);
                $expectedCart = array_map(fn($i) => array_intersect_key($i, array_flip(['id', 'product_id', 'quantity'])), $items);
                if ($lockedCart != $expectedCart) {
                    throw new DomainException('Your cart changed. Please reload checkout and try again.');
                }
                if (($needsRx || $rxId) && !q_one("SELECT id FROM prescriptions WHERE id=? AND user_id=? AND status IN ('pending','approved') FOR UPDATE", [$rxId, $u['id']])) {
                    throw new DomainException('Please select a valid pending or approved prescription.');
                }
                // Conditional updates reserve inventory atomically; rollback restores all reservations.
                $stockItems = $items;
                usort($stockItems, fn($a, $b) => $a['product_id'] <=> $b['product_id']);
                foreach ($stockItems as $i) {
                    $reserved = q('UPDATE products SET stock=stock-? WHERE id=? AND stock>=? AND is_active=1 AND price=? AND requires_prescription=?',
                        [$i['quantity'], $i['product_id'], $i['quantity'], $i['price'], $i['requires_prescription']]);
                    if ($reserved->rowCount() !== 1) {
                        throw new DomainException('A product changed or has insufficient stock. Please refresh your cart.');
                    }
                }
                $orderNo = new_order_no();
                q('INSERT INTO orders (order_no, user_id, prescription_id, subtotal, shipping_fee, total,
                    payment_method, payment_status, status, delivery_method, address, note, qr_token)
                   VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?)', [
                    $orderNo, $u['id'], $rxId ?: null, $subtotal, $shipping, $subtotal + $shipping,
                    $payment, 'pending', 'placed', $delivery,
                    $delivery === 'delivery' ? $address : null, $note ?: null, new_token(),
                ]);
                $orderId = (int)$pdo->lastInsertId();

                foreach ($items as $i) {
                    q('INSERT INTO order_items (order_id, product_id, product_name, price, quantity) VALUES (?,?,?,?,?)',
                        [$orderId, $i['product_id'], $i['name'], $i['price'], $i['quantity']]);

                }
                q('DELETE FROM cart_items WHERE user_id = ?', [$u['id']]);
                $pdo->commit();
            } catch (Throwable $ex) {
                $pdo->rollBack();
                if ($ex instanceof DomainException) {
                    flash('error', $ex->getMessage());
                    redirect('cart.php');
                }
                throw $ex;
            }

            if ($payment === 'payhere') {
                redirect('payment.php?order=' . $orderId);
            }

            // Cash on pickup/delivery — order goes straight to the pharmacy queue
            notify((int)$u['id'], 'order', 'Order #' . $orderNo . ' placed',
                'We\'ll notify you when it\'s ready for ' . $delivery . '.', url('order.php?id=' . $orderId));
            flash('success', 'Order placed! Pay when you ' . ($delivery === 'pickup' ? 'collect it.' : 'receive it.'));
            redirect('order-success.php?id=' . $orderId);
        }
    }
}

$pageTitle = 'Checkout';
include __DIR__ . '/components/header.php';
?>
<div class="container py-4 py-md-5">
  <div class="page-head pt-0"><h1 class="h2">Checkout</h1></div>

  <?php if ($error): ?>
    <div class="alert alert-danger"><i class="bi bi-exclamation-triangle me-2"></i><?= $error ?></div>
  <?php endif; ?>

  <form method="post" enctype="multipart/form-data">
    <?= csrf_field() ?>
    <div class="row g-4">
      <div class="col-lg-8">
        <!-- Delivery method -->
        <div class="mo-card p-4 mb-4" data-reveal>
          <h2 class="h5 fw-bold mb-3"><span class="badge rounded-pill text-bg-dark me-2">1</span>How do you want it?</h2>
          <div class="row g-3">
            <div class="col-sm-6">
              <input type="radio" class="btn-check" name="delivery" id="dPickup" value="pickup" checked>
              <label class="mo-card p-3 w-100 h-100 d-block" for="dPickup" style="cursor:pointer">
                <i class="bi bi-qr-code-scan fs-3" style="color:var(--mo-primary)"></i>
                <div class="fw-bold mt-1">Pickup at pharmacy <span class="badge text-bg-success ms-1">FREE</span></div>
                <div class="small text-muted">Collect with your QR card — no queue.</div>
              </label>
            </div>
            <div class="col-sm-6">
              <input type="radio" class="btn-check" name="delivery" id="dDelivery" value="delivery">
              <label class="mo-card p-3 w-100 h-100 d-block" for="dDelivery" style="cursor:pointer">
                <i class="bi bi-truck fs-3" style="color:var(--mo-primary)"></i>
                <div class="fw-bold mt-1">Home delivery</div>
                <div class="small text-muted"><?= money((float)setting('shipping_fee', '250')) ?> — free over <?= money((float)setting('free_shipping_over', '5000')) ?></div>
              </label>
            </div>
          </div>
          <div id="addressBox" class="mt-3 d-none">
            <label class="form-label fw-semibold">Delivery address</label>
            <textarea name="address" class="form-control" rows="2" placeholder="Street, city"><?= e($u['address'] ?? '') ?></textarea>
          </div>
        </div>

        <?php if ($needsRx): ?>
        <!-- Prescription -->
        <div class="mo-card p-4 mb-4" data-reveal>
          <h2 class="h5 fw-bold mb-1"><span class="badge rounded-pill text-bg-dark me-2">2</span>Prescription</h2>
          <p class="small text-muted">Some items need a doctor's prescription. Add a photo of it — our pharmacist checks it before preparing your order.</p>
          <div class="d-flex flex-column gap-2">
            <?php foreach ($myPrescriptions as $k => $rx): ?>
              <label class="mo-card p-3 d-flex align-items-center gap-3" style="cursor:pointer">
                <input type="radio" class="form-check-input m-0" name="prescription_id" value="<?= $rx['id'] ?>" <?= $k === 0 ? 'checked' : '' ?>>
                <i class="bi bi-file-medical fs-4" style="color:var(--mo-primary)"></i>
                <div class="flex-grow-1">
                  <div class="fw-semibold small">Use my prescription from <?= time_ago($rx['created_at']) ?></div>
                  <div class="small text-muted text-truncate"><?= e($rx['original_name']) ?></div>
                </div>
                <span class="mo-status mo-status-<?= e($rx['status']) ?>"><?= e(status_label($rx['status'])) ?></span>
              </label>
            <?php endforeach; ?>
            <label class="mo-card p-3 d-flex align-items-start gap-3" style="cursor:pointer">
              <input type="radio" class="form-check-input m-0 mt-1" name="prescription_id" value="0" <?= $myPrescriptions ? '' : 'checked' ?>>
              <div class="flex-grow-1">
                <div class="fw-semibold small mb-2"><i class="bi bi-camera me-1"></i>Upload a new prescription photo</div>
                <input type="file" name="prescription_file" class="form-control" accept="image/*,application/pdf"
                       onchange="this.closest('label').querySelector('input[type=radio]').checked = true">
                <div class="small text-muted mt-1">Take a photo with your phone or choose a file (JPG, PNG or PDF, up to 5 MB).</div>
              </div>
            </label>
          </div>
        </div>
        <?php endif; ?>

        <!-- Payment -->
        <div class="mo-card p-4" data-reveal>
          <h2 class="h5 fw-bold mb-3"><span class="badge rounded-pill text-bg-dark me-2"><?= $needsRx ? 3 : 2 ?></span>Payment</h2>
          <div class="row g-3">
            <div class="col-sm-6">
              <input type="radio" class="btn-check" name="payment" id="pCard" value="payhere" checked>
              <label class="mo-card p-3 w-100 h-100 d-block" for="pCard" style="cursor:pointer">
                <i class="bi bi-credit-card fs-3" style="color:var(--mo-primary)"></i>
                <div class="fw-bold mt-1">Pay online</div>
                <div class="small text-muted">Card via PayHere <?= payhere_configured_badge() ?></div>
              </label>
            </div>
            <div class="col-sm-6">
              <input type="radio" class="btn-check" name="payment" id="pCash" value="cash">
              <label class="mo-card p-3 w-100 h-100 d-block" for="pCash" style="cursor:pointer">
                <i class="bi bi-cash-coin fs-3" style="color:var(--mo-primary)"></i>
                <div class="fw-bold mt-1">Cash</div>
                <div class="small text-muted">Pay when you collect / receive</div>
              </label>
            </div>
          </div>
          <div class="mt-3">
            <label class="form-label fw-semibold">Note for the pharmacy <span class="text-muted fw-normal">(optional)</span></label>
            <textarea name="note" class="form-control" rows="2" placeholder="Anything we should know?"></textarea>
          </div>
        </div>
      </div>

      <!-- Summary -->
      <div class="col-lg-4" data-reveal>
        <div class="mo-card p-4 position-sticky" style="top:90px">
          <h2 class="h5 fw-bold mb-3">Your order</h2>
          <?php foreach ($items as $i): ?>
            <div class="d-flex justify-content-between small mb-2">
              <span class="text-truncate me-2"><?= e($i['name']) ?> × <?= (int)$i['quantity'] ?></span>
              <span class="fw-semibold text-nowrap"><?= money($i['price'] * $i['quantity']) ?></span>
            </div>
          <?php endforeach; ?>
          <hr>
          <div class="d-flex justify-content-between mb-1"><span class="text-muted">Subtotal</span><span class="fw-semibold"><?= money($subtotal) ?></span></div>
          <div class="d-flex justify-content-between mb-1"><span class="text-muted">Delivery</span><span id="shipLine">FREE (pickup)</span></div>
          <div class="d-flex justify-content-between fs-5 fw-bold mt-2"><span>Total</span><span id="grandTotal"><?= money($subtotal) ?></span></div>
          <button class="btn mo-btn w-100 btn-lg mt-3"><i class="bi bi-lock me-2"></i>Place order</button>
          <div class="text-center small text-muted mt-2"><i class="bi bi-shield-check me-1"></i>Secure checkout</div>
        </div>
      </div>
    </div>
  </form>
</div>

<script>
const SUBTOTAL = <?= json_encode($subtotal) ?>;
const FEE = <?= json_encode((float)setting('shipping_fee', '250')) ?>;
const FREE_OVER = <?= json_encode((float)setting('free_shipping_over', '5000')) ?>;
const fmt = v => 'Rs. ' + v.toLocaleString(undefined, {minimumFractionDigits: 2});
document.querySelectorAll('[name="delivery"]').forEach(r => r.addEventListener('change', () => {
  const delivery = document.querySelector('[name="delivery"]:checked').value === 'delivery';
  document.getElementById('addressBox').classList.toggle('d-none', !delivery);
  const ship = delivery ? (SUBTOTAL >= FREE_OVER ? 0 : FEE) : 0;
  document.getElementById('shipLine').textContent = delivery ? (ship ? fmt(ship) : 'FREE') : 'FREE (pickup)';
  document.getElementById('grandTotal').textContent = fmt(SUBTOTAL + ship);
}));
</script>
<?php
include __DIR__ . '/components/footer.php';

function payhere_configured_badge(): string
{
    require_once __DIR__ . '/includes/payhere.php';
    return payhere_configured()
        ? (PAYHERE_SANDBOX ? '<span class="badge text-bg-warning">Sandbox</span>' : '')
        : '<span class="badge text-bg-secondary">Demo mode</span>';
}

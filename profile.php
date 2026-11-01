<?php
require_once __DIR__ . '/config.php';
$u = require_login();

$tab = $_GET['tab'] ?? 'overview';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    csrf_check();
    $action = $_POST['action'] ?? '';

    if ($action === 'details') {
        $name = trim($_POST['name'] ?? '');
        $phoneRaw = trim($_POST['phone'] ?? '');
        $address = trim($_POST['address'] ?? '');
        $optIn = isset($_POST['whatsapp_opt_in']) ? 1 : 0;
        $phone = $phoneRaw !== '' ? normalize_phone($phoneRaw) : null;

        if ($name === '') {
            flash('error', 'Name cannot be empty.');
        } elseif ($phoneRaw !== '' && !$phone) {
            flash('error', 'That mobile number does not look valid (e.g. 0771234567).');
        } elseif ($phone && q_one('SELECT id FROM users WHERE phone = ? AND id <> ?', [$phone, $u['id']])) {
            flash('error', 'That mobile number is already used by another account.');
        } else {
            // A changed number must be verified again
            $phoneVerified = ($phone === $u['phone']) ? (int)$u['phone_verified'] : 0;
            q('UPDATE users SET name=?, phone=?, address=?, whatsapp_opt_in=?, phone_verified=? WHERE id=?',
                [$name, $phone, $address, $optIn, $phoneVerified, $u['id']]);
            if ($phone && !$phoneVerified) {
                flash('info', 'Profile updated — please verify your new mobile number.');
                redirect('verify-account.php');
            }
            flash('success', 'Profile updated.');
        }
        redirect('profile.php?tab=details');
    }

    if ($action === 'password') {
        $current = $_POST['current'] ?? '';
        $new = $_POST['new'] ?? '';
        $confirm = $_POST['confirm'] ?? '';
        if ($u['password_hash'] && !password_verify($current, $u['password_hash'])) {
            flash('error', 'Current password is incorrect.');
        } elseif (strlen($new) < 8) {
            flash('error', 'New password must be at least 8 characters.');
        } elseif ($new !== $confirm) {
            flash('error', 'New passwords do not match.');
        } else {
            q('UPDATE users SET password_hash=? WHERE id=?', [password_hash($new, PASSWORD_BCRYPT), $u['id']]);
            flash('success', 'Password changed successfully.');
        }
        redirect('profile.php?tab=security');
    }
}

$stats = [
    'orders' => (int)q_val('SELECT COUNT(*) FROM orders WHERE user_id=?', [$u['id']]),
    'prescriptions' => (int)q_val('SELECT COUNT(*) FROM prescriptions WHERE user_id=?', [$u['id']]),
    'medications' => (int)q_val('SELECT COUNT(*) FROM medications WHERE user_id=? AND active=1', [$u['id']]),
];
$recentOrders = q_all('SELECT * FROM orders WHERE user_id=? ORDER BY id DESC LIMIT 5', [$u['id']]);

$pageTitle = 'My profile';
include __DIR__ . '/components/header.php';
?>
<div class="container py-4 py-md-5">
  <div class="row g-4">
    <div class="col-lg-4">
      <div class="mo-card p-4 text-center" data-reveal>
        <div class="mo-avatar-btn mx-auto mb-3" style="width:84px;height:84px;font-size:2rem">
          <?php if (!empty($u['avatar'])): ?>
            <img src="<?= e($u['avatar']) ?>" alt="" referrerpolicy="no-referrer">
          <?php else: ?>
            <span><?= e(strtoupper(mb_substr($u['name'], 0, 1))) ?></span>
          <?php endif; ?>
        </div>
        <h2 class="h5 fw-bold mb-1"><?= e($u['name']) ?></h2>
        <div class="text-muted small"><?= e($u['email'] ?? '') ?>
          <?php if ($u['email']): ?>
            <?= $u['email_verified']
                ? '<i class="bi bi-patch-check-fill text-success" title="Email verified"></i>'
                : '<a href="' . url('verify-account.php') . '" class="badge text-bg-warning text-decoration-none">Verify</a>' ?>
          <?php endif; ?>
        </div>
        <?php if ($u['phone']): ?>
          <div class="text-muted small"><i class="bi bi-whatsapp text-success me-1"></i><?= e(pretty_phone($u['phone'])) ?>
            <?= $u['phone_verified']
                ? '<i class="bi bi-patch-check-fill text-success" title="Number verified"></i>'
                : '<a href="' . url('verify-account.php') . '" class="badge text-bg-warning text-decoration-none">Verify</a>' ?>
          </div>
        <?php endif; ?>
        <?php if ($u['google_id']): ?><span class="badge bg-light text-dark border mt-2"><i class="bi bi-google me-1"></i>Linked with Google</span><?php endif; ?>
        <hr>
        <div class="row text-center">
          <div class="col-4"><div class="fw-bold fs-5"><?= $stats['orders'] ?></div><div class="small text-muted">Orders</div></div>
          <div class="col-4"><div class="fw-bold fs-5"><?= $stats['prescriptions'] ?></div><div class="small text-muted">Rx</div></div>
          <div class="col-4"><div class="fw-bold fs-5"><?= $stats['medications'] ?></div><div class="small text-muted">Meds</div></div>
        </div>
        <a href="<?= url('my-qr.php') ?>" class="btn mo-btn w-100 mt-3"><i class="bi bi-qr-code me-2"></i>My QR Card</a>
      </div>
    </div>

    <div class="col-lg-8">
      <div class="mo-card p-4" data-reveal>
        <ul class="nav nav-pills gap-2 mb-4">
          <?php foreach (['overview' => 'Overview', 'details' => 'Account details', 'security' => 'Security'] as $k => $label): ?>
            <li class="nav-item"><a class="nav-link <?= $tab === $k ? 'active' : '' ?>" href="<?= url('profile.php?tab=' . $k) ?>"><?= $label ?></a></li>
          <?php endforeach; ?>
        </ul>

        <?php if ($tab === 'overview'): ?>
          <h3 class="h6 fw-bold text-uppercase text-muted mb-3">Recent orders</h3>
          <?php if (!$recentOrders): ?>
            <div class="mo-empty"><i class="bi bi-box-seam"></i>No orders yet — <a href="<?= url('shop.php') ?>">browse the shop</a></div>
          <?php else: ?>
            <div class="table-responsive">
              <table class="table mo-table table-hover align-middle">
                <thead><tr><th>Order</th><th>Date</th><th>Total</th><th>Status</th><th></th></tr></thead>
                <tbody>
                <?php foreach ($recentOrders as $o): ?>
                  <tr>
                    <td class="fw-semibold">#<?= e($o['order_no']) ?></td>
                    <td><?= date('j M Y', strtotime($o['created_at'])) ?></td>
                    <td><?= money((float)$o['total']) ?></td>
                    <td><span class="mo-status mo-status-<?= e($o['status']) ?>"><?= ucfirst(e($o['status'])) ?></span></td>
                    <td><a class="btn btn-sm mo-btn-soft" href="<?= url('order.php?id=' . $o['id']) ?>">View</a></td>
                  </tr>
                <?php endforeach; ?>
                </tbody>
              </table>
            </div>
            <a href="<?= url('orders.php') ?>" class="small text-decoration-none">All orders <i class="bi bi-arrow-right"></i></a>
          <?php endif; ?>

        <?php elseif ($tab === 'details'): ?>
          <form method="post">
            <?= csrf_field() ?>
            <input type="hidden" name="action" value="details">
            <div class="mb-3">
              <label class="form-label fw-semibold">Full name</label>
              <input type="text" name="name" class="form-control" value="<?= e($u['name']) ?>" required>
            </div>
            <div class="mb-3">
              <label class="form-label fw-semibold">Email</label>
              <input type="email" class="form-control" value="<?= e($u['email'] ?? '') ?>" disabled>
              <div class="form-text">Email cannot be changed (it identifies your account).</div>
            </div>
            <div class="mb-3">
              <label class="form-label fw-semibold">Mobile number</label>
              <input type="tel" name="phone" class="form-control" value="<?= e(pretty_phone($u['phone'])) ?>" placeholder="07X XXX XXXX">
              <div class="form-text">Used for phone login and WhatsApp medicine reminders.</div>
            </div>
            <div class="mb-3">
              <label class="form-label fw-semibold">Delivery address</label>
              <textarea name="address" class="form-control" rows="2" placeholder="Street, city"><?= e($u['address'] ?? '') ?></textarea>
            </div>
            <div class="form-check form-switch mb-4">
              <input class="form-check-input" type="checkbox" id="waOptIn" name="whatsapp_opt_in" <?= $u['whatsapp_opt_in'] ? 'checked' : '' ?>>
              <label class="form-check-label" for="waOptIn">
                <i class="bi bi-whatsapp text-success me-1"></i>Send me medicine reminders &amp; order updates on WhatsApp
              </label>
            </div>
            <button class="btn mo-btn">Save changes</button>
          </form>

        <?php else: ?>
          <form method="post" class="mo-auth-card ms-0">
            <?= csrf_field() ?>
            <input type="hidden" name="action" value="password">
            <?php if ($u['password_hash']): ?>
              <div class="mb-3">
                <label class="form-label fw-semibold">Current password</label>
                <input type="password" name="current" class="form-control" required>
              </div>
            <?php else: ?>
              <div class="alert alert-info py-2 small">You signed up with Google — set a password below to
                also enable email/phone login.</div>
            <?php endif; ?>
            <div class="mb-3">
              <label class="form-label fw-semibold">New password</label>
              <input type="password" name="new" class="form-control" minlength="8" required>
            </div>
            <div class="mb-4">
              <label class="form-label fw-semibold">Confirm new password</label>
              <input type="password" name="confirm" class="form-control" required>
            </div>
            <button class="btn mo-btn">Change password</button>
          </form>
        <?php endif; ?>
      </div>
    </div>
  </div>
</div>
<?php include __DIR__ . '/components/footer.php'; ?>

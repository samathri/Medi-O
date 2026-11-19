<?php
$pageTitle = 'Settings';
require_once __DIR__ . '/../components/staff-header.php';
if (!is_admin()) { http_response_code(403); exit('Admin only.'); }

$fields = [
    'pharmacy_name' => 'Pharmacy name',
    'pharmacy_phone' => 'Phone number',
    'pharmacy_address' => 'Address',
    'pharmacy_hours' => 'Opening hours',
    'shipping_fee' => 'Delivery fee (Rs.)',
    'free_shipping_over' => 'Free delivery over (Rs.)',
];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    csrf_check();
    foreach ($fields as $key => $label) {
        if (isset($_POST[$key])) {
            q('INSERT INTO settings (name, value) VALUES (?,?) ON DUPLICATE KEY UPDATE value=VALUES(value)',
                [$key, trim($_POST[$key])]);
        }
    }
    flash('success', 'Settings saved.');
    redirect('admin/settings.php');
}
?>
<h1 class="h3 fw-bold mb-4">Pharmacy settings</h1>

<div class="mo-card p-4" style="max-width:640px">
  <form method="post">
    <?= csrf_field() ?>
    <?php foreach ($fields as $key => $label): ?>
      <div class="mb-3">
        <label class="form-label fw-semibold"><?= e($label) ?></label>
        <input name="<?= $key ?>" class="form-control" value="<?= e(setting($key)) ?>">
      </div>
    <?php endforeach; ?>
    <button class="btn mo-btn">Save settings</button>
  </form>
  <hr>
  <div class="small text-muted">
    <i class="bi bi-key me-1"></i>API keys (Google, PayHere, Gemini, SMTP) are configured in
    <code>config.local.php</code> on the server — see the README for step-by-step guides.
  </div>
</div>
<?php require_once __DIR__ . '/../components/staff-footer.php'; ?>

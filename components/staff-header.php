<?php
/** Staff/admin layout header. Set $pageTitle and $active before including.
 *  Guard: pharmacist or admin only. */
require_once dirname(__DIR__) . '/config.php';
$staff = require_staff();

$pendingRx = (int)q_val("SELECT COUNT(*) FROM prescriptions WHERE status='pending'");
$openOrders = (int)q_val("SELECT COUNT(*) FROM orders WHERE status IN ('placed','processing')");
$unreadChats = (int)q_val('SELECT COALESCE(SUM(staff_unread),0) FROM chat_conversations');

$nav = [
    ['staff/index.php', 'bi-speedometer2', 'Dashboard', null],
    ['staff/scan.php', 'bi-qr-code-scan', 'Scan QR', null],
    ['staff/prescriptions.php', 'bi-file-medical', 'Prescriptions', $pendingRx],
    ['staff/orders.php', 'bi-box-seam', 'Orders', $openOrders],
    ['staff/chat.php', 'bi-chat-dots', 'Chats', $unreadChats],
    ['staff/medications.php', 'bi-alarm', 'Medications', null],
    ['staff/whatsapp.php', 'bi-whatsapp', 'WhatsApp', null],
];
if (is_admin()) {
    $nav[] = ['admin/index.php', 'bi-graph-up', 'Analytics', null];
    $nav[] = ['admin/products.php', 'bi-capsule', 'Products', null];
    $nav[] = ['admin/users.php', 'bi-people', 'Users', null];
    $nav[] = ['admin/inquiries.php', 'bi-envelope', 'Inquiries', null];
    $nav[] = ['admin/settings.php', 'bi-gear', 'Settings', null];
}
$activePage = $active ?? basename($_SERVER['SCRIPT_NAME']);
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title><?= e($pageTitle ?? 'Staff') ?> · Medi-O Staff</title>
<link rel="icon" href="<?= url('assets/img/medi-o-logo.svg') ?>">
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
<link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
<link href="<?= url('assets/css/app.css') ?>?v=<?= filemtime(dirname(__DIR__) . '/assets/css/app.css') ?>" rel="stylesheet">
<script>window.MEDIO = {base: <?= json_encode(BASE_URL) ?>, csrf: <?= json_encode(csrf_token()) ?>, loggedIn: true};</script>
</head>
<body>
<div class="mo-staff-wrap">
  <aside class="mo-sidebar">
    <a class="brand" href="<?= url('index.php') ?>">
      <span class="mo-logo"><i class="bi bi-capsule"></i></span>
      <span class="fw-bold fs-5">Medi-O <span class="badge text-bg-dark border ms-1" style="font-size:.6rem"><?= is_admin() ? 'ADMIN' : 'STAFF' ?></span></span>
    </a>
    <nav class="d-flex flex-column flex-lg-column flex-row flex-grow-1" style="min-width:0">
      <?php foreach ($nav as [$href, $icon, $label, $badge]): ?>
        <a class="nav-item <?= str_ends_with($href, $activePage) ? 'active' : '' ?>" href="<?= url($href) ?>">
          <i class="bi <?= $icon ?>"></i><span><?= $label ?></span>
          <?php if ($badge): ?><span class="badge text-bg-warning ms-auto"><?= $badge ?></span><?php endif; ?>
        </a>
      <?php endforeach; ?>
    </nav>
    <div class="sidebar-footer p-3 border-top border-secondary border-opacity-25">
      <div class="small text-white-50 mb-2"><?= e($staff['name']) ?></div>
      <a href="<?= url('logout.php') ?>" class="btn btn-sm btn-outline-light w-100"><i class="bi bi-box-arrow-right me-1"></i>Log out</a>
    </div>
  </aside>
  <main class="mo-staff-main">
    <div class="mo-toasts" id="toasts"></div>
    <?php foreach (flash_pull() as $f): ?>
      <script>document.addEventListener('DOMContentLoaded',()=>MedioToast(<?= json_encode($f['message']) ?>, <?= json_encode($f['type']) ?>));</script>
    <?php endforeach; ?>

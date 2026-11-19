<?php
/** Staff/admin layout header. Set $pageTitle and $active before including.
 *  Guard: pharmacist or admin only. */
require_once dirname(__DIR__) . '/config.php';
$staff = require_staff();
ob_start(); // let pages below still redirect after handling a form

$pendingRx = (int)q_val("SELECT COUNT(*) FROM prescriptions WHERE status='pending'");
$openOrders = (int)q_val("SELECT COUNT(*) FROM orders WHERE status IN ('placed','processing')");
$unreadChats = (int)q_val('SELECT COALESCE(SUM(staff_unread),0) FROM chat_conversations');

$nav = [
    'Daily work' => [
        ['staff/index.php', 'bi-house', 'Today', null],
        ['staff/prescriptions.php', 'bi-file-medical', 'Prescriptions', $pendingRx],
        ['staff/orders.php', 'bi-box-seam', 'Orders', $openOrders],
        ['staff/scan.php', 'bi-qr-code-scan', 'Hand over (scan QR)', null],
        ['staff/chat.php', 'bi-chat-dots', 'Customer chats', $unreadChats],
    ],
    'Patients' => [
        ['staff/medications.php', 'bi-alarm', 'Dose reminders', null],
    ],
];
if (is_admin()) {
    $nav['Manage shop'] = [
        ['admin/index.php', 'bi-graph-up', 'Sales report', null],
        ['admin/products.php', 'bi-capsule', 'Products & stock', null],
        ['admin/users.php', 'bi-people', 'Customers & staff', null],
        ['admin/inquiries.php', 'bi-envelope', 'Contact messages', null],
        ['admin/settings.php', 'bi-gear', 'Settings', null],
    ];
}
$curPath = substr($_SERVER['SCRIPT_NAME'], strlen(parse_url(BASE_URL, PHP_URL_PATH) ?: '') + 1);
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title><?= e($pageTitle ?? 'Staff') ?> · Medi-O Staff</title>
<link rel="icon" href="<?= url('assets/img/medi-o-logo.svg') ?>">
<link rel="manifest" href="<?= url('manifest.webmanifest') ?>">
<meta name="theme-color" content="#00843d">
<meta name="mobile-web-app-capable" content="yes">
<meta name="apple-mobile-web-app-capable" content="yes">
<meta name="apple-mobile-web-app-title" content="Medi-O">
<link rel="apple-touch-icon" href="<?= url('assets/img/apple-touch-icon.png') ?>">
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
<link href="https://fonts.googleapis.com/css2?family=DM+Sans:opsz,wght@9..40,400;9..40,500;9..40,600;9..40,700;9..40,800&display=swap" rel="stylesheet">
<link href="<?= url('assets/css/app.css') ?>?v=<?= filemtime(dirname(__DIR__) . '/assets/css/app.css') ?>" rel="stylesheet">
<script>window.MEDIO = {base: <?= json_encode(BASE_URL) ?>, csrf: <?= json_encode(csrf_token()) ?>, loggedIn: true};</script>
</head>
<body>
<div class="mo-staff-wrap">
  <aside class="mo-sidebar">
    <a class="brand" href="<?= url('index.php') ?>">
      <span class="mo-logo"><i class="bi bi-capsule"></i></span>
      <span>Medi<em>-O</em> <span class="badge ms-1" style="font-size:.58rem;font-family:var(--sans);background:var(--butter);color:var(--ink);letter-spacing:.08em"><?= is_admin() ? 'ADMIN' : 'STAFF' ?></span></span>
    </a>
    <nav class="d-flex flex-column flex-lg-column flex-row flex-grow-1" style="min-width:0">
      <?php $here = isset($active) ? dirname($curPath) . '/' . $active : $curPath;
      foreach ($nav as $group => $links): ?>
        <div class="nav-group"><?= e($group) ?></div>
        <?php foreach ($links as [$href, $icon, $label, $badge]): ?>
          <a class="nav-item <?= $href === $here ? 'active' : '' ?>" href="<?= url($href) ?>">
            <i class="bi <?= $icon ?>"></i><span><?= $label ?></span>
            <?php if ($badge): ?><span class="badge text-bg-warning ms-auto"><?= $badge ?></span><?php endif; ?>
          </a>
        <?php endforeach; ?>
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

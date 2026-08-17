<?php
/** Shared page header. Set $pageTitle before including. */
require_once __DIR__ . '/../config.php';
$u = current_user();
$cartCount = $u ? (int)q_val('SELECT COALESCE(SUM(quantity),0) FROM cart_items WHERE user_id=?', [$u['id']]) : 0;
$notifCount = $u ? (int)q_val('SELECT COUNT(*) FROM notifications WHERE user_id=? AND is_read=0', [$u['id']]) : 0;
$chatUnread = 0;
if ($u && $u['role'] === 'customer') {
    $chatUnread = (int)q_val('SELECT COALESCE(user_unread,0) FROM chat_conversations WHERE user_id=?', [$u['id']]);
}
$active = basename($_SERVER['SCRIPT_NAME']);
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title><?= e($pageTitle ?? 'Medi-O') ?> · Medi-O Pharmacy</title>
<link rel="icon" href="<?= url('assets/img/medi-o-logo.svg') ?>">
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
<link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
<link href="<?= url('assets/css/app.css') ?>?v=<?= filemtime(__DIR__ . '/../assets/css/app.css') ?>" rel="stylesheet">
<script>window.MEDIO = {base: <?= json_encode(BASE_URL) ?>, csrf: <?= json_encode(csrf_token()) ?>, loggedIn: <?= $u ? 'true' : 'false' ?>};</script>
</head>
<body>
<a class="visually-hidden-focusable" href="#main">Skip to content</a>

<nav class="navbar navbar-expand-lg mo-navbar sticky-top">
  <div class="container">
    <a class="navbar-brand d-flex align-items-center gap-2" href="<?= url('index.php') ?>">
      <span class="mo-logo"><i class="bi bi-capsule"></i></span>
      <span class="fw-bold fs-4">Medi<span class="text-gradient">-O</span></span>
    </a>

    <div class="d-flex align-items-center gap-1 order-lg-3">
      <button class="mo-iconbtn d-none d-sm-inline-flex" type="button" data-bs-toggle="modal" data-bs-target="#searchModal" aria-label="Search">
        <i class="bi bi-search"></i>
      </button>

      <a class="mo-iconbtn position-relative" href="<?= url('cart.php') ?>" aria-label="Cart">
        <i class="bi bi-bag"></i>
        <span class="mo-badge" id="cartBadge" <?= $cartCount ? '' : 'hidden' ?>><?= $cartCount ?></span>
      </a>

      <?php if ($u): ?>
        <div class="dropdown">
          <button class="mo-iconbtn position-relative" data-bs-toggle="dropdown" aria-label="Notifications">
            <i class="bi bi-bell"></i>
            <span class="mo-badge" id="notifBadge" <?= $notifCount ? '' : 'hidden' ?>><?= $notifCount ?></span>
          </button>
          <div class="dropdown-menu dropdown-menu-end mo-dropdown p-0" style="width:330px">
            <div class="d-flex justify-content-between align-items-center px-3 py-2 border-bottom">
              <strong>Notifications</strong>
              <button class="btn btn-sm btn-link text-decoration-none p-0" id="markAllRead">Mark all read</button>
            </div>
            <div id="notifList" class="mo-notif-list"><div class="text-center text-muted py-4 small">Loading…</div></div>
          </div>
        </div>

        <div class="dropdown">
          <button class="mo-avatar-btn" data-bs-toggle="dropdown" aria-label="Account">
            <?php if (!empty($u['avatar'])): ?>
              <img src="<?= e($u['avatar']) ?>" alt="" referrerpolicy="no-referrer">
            <?php else: ?>
              <span><?= e(strtoupper(mb_substr($u['name'], 0, 1))) ?></span>
            <?php endif; ?>
          </button>
          <ul class="dropdown-menu dropdown-menu-end mo-dropdown">
            <li class="px-3 py-2 border-bottom">
              <div class="fw-semibold"><?= e($u['name']) ?></div>
              <div class="small text-muted"><?= e($u['email'] ?: pretty_phone($u['phone'])) ?></div>
            </li>
            <?php if (is_staff()): ?>
              <li><a class="dropdown-item" href="<?= url(is_admin() ? 'admin/index.php' : 'staff/index.php') ?>"><i class="bi bi-speedometer2 me-2"></i>Dashboard</a></li>
            <?php endif; ?>
            <li><a class="dropdown-item" href="<?= url('profile.php') ?>"><i class="bi bi-person me-2"></i>My Profile</a></li>
            <li><a class="dropdown-item" href="<?= url('my-qr.php') ?>"><i class="bi bi-qr-code me-2"></i>My QR Card</a></li>
            <li><a class="dropdown-item" href="<?= url('orders.php') ?>"><i class="bi bi-box-seam me-2"></i>My Orders</a></li>
            <li><a class="dropdown-item" href="<?= url('prescriptions.php') ?>"><i class="bi bi-file-medical me-2"></i>Prescriptions</a></li>
            <li><a class="dropdown-item" href="<?= url('medications.php') ?>"><i class="bi bi-alarm me-2"></i>Medication Tracker</a></li>
            <li><hr class="dropdown-divider"></li>
            <li><a class="dropdown-item text-danger" href="<?= url('logout.php') ?>"><i class="bi bi-box-arrow-right me-2"></i>Log out</a></li>
          </ul>
        </div>
      <?php else: ?>
        <a class="btn mo-btn-outline d-none d-sm-inline-flex ms-1" href="<?= url('login.php') ?>">Log in</a>
        <a class="btn mo-btn ms-1" href="<?= url('signup.php') ?>">Sign up</a>
      <?php endif; ?>

      <button class="navbar-toggler mo-iconbtn ms-1" type="button" data-bs-toggle="offcanvas" data-bs-target="#mobileNav" aria-label="Menu">
        <i class="bi bi-list fs-4"></i>
      </button>
    </div>

    <div class="collapse navbar-collapse order-lg-2 justify-content-center d-none d-lg-flex">
      <ul class="navbar-nav mo-nav gap-lg-1">
        <li><a class="nav-link <?= $active === 'index.php' ? 'active' : '' ?>" href="<?= url('index.php') ?>">Home</a></li>
        <li><a class="nav-link <?= $active === 'shop.php' ? 'active' : '' ?>" href="<?= url('shop.php') ?>">Shop</a></li>
        <li><a class="nav-link <?= $active === 'prescriptions.php' ? 'active' : '' ?>" href="<?= url('prescriptions.php') ?>">Prescriptions</a></li>
        <li><a class="nav-link <?= $active === 'medications.php' ? 'active' : '' ?>" href="<?= url('medications.php') ?>">Tracker</a></li>
        <li><a class="nav-link position-relative <?= $active === 'chat.php' ? 'active' : '' ?>" href="<?= url('chat.php') ?>">Chat
          <?php if ($chatUnread): ?><span class="mo-badge mo-badge-inline"><?= $chatUnread ?></span><?php endif; ?>
        </a></li>
        <li><a class="nav-link <?= $active === 'contact.php' ? 'active' : '' ?>" href="<?= url('contact.php') ?>">Contact</a></li>
      </ul>
    </div>
  </div>
</nav>

<!-- Mobile menu -->
<div class="offcanvas offcanvas-end mo-offcanvas" tabindex="-1" id="mobileNav">
  <div class="offcanvas-header">
    <span class="fw-bold fs-5">Menu</span>
    <button type="button" class="btn-close" data-bs-dismiss="offcanvas"></button>
  </div>
  <div class="offcanvas-body pt-0">
    <div class="list-group list-group-flush mo-mobile-nav">
      <a class="list-group-item" href="<?= url('index.php') ?>"><i class="bi bi-house me-2"></i>Home</a>
      <a class="list-group-item" href="<?= url('shop.php') ?>"><i class="bi bi-shop me-2"></i>Shop</a>
      <a class="list-group-item" href="<?= url('prescriptions.php') ?>"><i class="bi bi-file-medical me-2"></i>Prescriptions</a>
      <a class="list-group-item" href="<?= url('medications.php') ?>"><i class="bi bi-alarm me-2"></i>Medication Tracker</a>
      <a class="list-group-item" href="<?= url('chat.php') ?>"><i class="bi bi-chat-dots me-2"></i>Chat with Pharmacy</a>
      <a class="list-group-item" href="<?= url('my-qr.php') ?>"><i class="bi bi-qr-code me-2"></i>My QR Card</a>
      <a class="list-group-item" href="<?= url('orders.php') ?>"><i class="bi bi-box-seam me-2"></i>My Orders</a>
      <a class="list-group-item" href="<?= url('contact.php') ?>"><i class="bi bi-envelope me-2"></i>Contact</a>
      <?php if (!$u): ?>
        <div class="d-grid gap-2 mt-3">
          <a class="btn mo-btn" href="<?= url('login.php') ?>">Log in</a>
          <a class="btn mo-btn-outline" href="<?= url('signup.php') ?>">Create account</a>
        </div>
      <?php endif; ?>
    </div>
  </div>
</div>

<!-- Search modal -->
<div class="modal fade" id="searchModal" tabindex="-1">
  <div class="modal-dialog modal-dialog-centered modal-lg">
    <div class="modal-content mo-card">
      <div class="modal-body p-4">
        <div class="mo-search-box">
          <i class="bi bi-search"></i>
          <input type="search" id="liveSearch" class="form-control form-control-lg border-0 shadow-none"
                 placeholder="Search medicines, vitamins, personal care…" autocomplete="off">
        </div>
        <div id="searchResults" class="mt-3"></div>
      </div>
    </div>
  </div>
</div>

<div class="mo-toasts" id="toasts"></div>
<?php foreach (flash_pull() as $f): ?>
<script>document.addEventListener('DOMContentLoaded',()=>MedioToast(<?= json_encode($f['message']) ?>, <?= json_encode($f['type']) ?>));</script>
<?php endforeach; ?>

<main id="main">

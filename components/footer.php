</main>

<footer class="mo-footer mt-auto">
  <div class="container pt-5 pb-4">
    <div class="row g-4 align-items-end pb-4 mb-4" style="border-bottom:1px solid rgba(255,255,255,.12)">
      <div class="col-lg-8">
        <p class="mo-footer-big mb-0">Questions about a medicine?<br><em>Ask a pharmacist</em>, not a search engine.</p>
      </div>
      <div class="col-lg-4 d-flex gap-2 justify-content-lg-end flex-wrap">
        <a class="btn mo-btn" style="background:var(--butter);border-color:var(--butter);color:var(--ink)" href="<?= url('chat.php') ?>">Chat with us</a>
        <a class="btn mo-btn-outline" style="border-color:rgba(255,255,255,.4);color:#fff" href="tel:<?= e(setting('pharmacy_phone')) ?>">Call the counter</a>
      </div>
    </div>
    <div class="row g-4">
      <div class="col-lg-4">
        <div class="d-flex align-items-center gap-2 mb-3">
          <span class="mo-logo"></span>
          <span class="mo-wordmark">Medi<em>-O</em></span>
        </div>
        <p class="pe-lg-5 small">A licensed community pharmacy in Sri Lanka. Order online, have prescriptions checked
          by a pharmacist, and collect at the counter with your QR card.</p>
        <div class="d-flex gap-2">
          <a class="mo-social" href="#" aria-label="Facebook"><i class="bi bi-facebook"></i></a>
          <a class="mo-social" href="#" aria-label="Instagram"><i class="bi bi-instagram"></i></a>
        </div>
      </div>
      <div class="col-6 col-lg-2">
        <h6 class="mb-3">Shop</h6>
        <ul class="mo-footer-links">
          <li><a href="<?= url('shop.php') ?>">All products</a></li>
          <li><a href="<?= url('shop.php?category=Medicines') ?>">Medicines</a></li>
          <li><a href="<?= url('shop.php?category=Vitamins+%26+Supplements') ?>">Vitamins</a></li>
          <li><a href="<?= url('cart.php') ?>">My cart</a></li>
        </ul>
      </div>
      <div class="col-6 col-lg-2">
        <h6 class="mb-3">Services</h6>
        <ul class="mo-footer-links">
          <li><a href="<?= url('prescriptions.php') ?>">Upload prescription</a></li>
          <li><a href="<?= url('medications.php') ?>">Medication tracker</a></li>
          <li><a href="<?= url('chat.php') ?>">Chat with pharmacy</a></li>
          <li><a href="<?= url('my-qr.php') ?>">My QR card</a></li>
        </ul>
      </div>
      <div class="col-6 col-lg-2">
        <h6 class="mb-3">Company</h6>
        <ul class="mo-footer-links">
          <li><a href="<?= url('about.php') ?>">About us</a></li>
          <li><a href="<?= url('contact.php') ?>">Contact</a></li>
          <li><a href="<?= url('faq.php') ?>">FAQ</a></li>
          <li><a href="<?= url('privacy.php') ?>">Privacy policy</a></li>
          <li><a href="<?= url('terms.php') ?>">Terms of service</a></li>
        </ul>
      </div>
      <div class="col-6 col-lg-2">
        <h6 class="mb-3">The counter</h6>
        <ul class="mo-footer-links small">
          <li><?= e(setting('pharmacy_address')) ?></li>
          <li><?= e(setting('pharmacy_phone')) ?></li>
          <li><?= e(setting('pharmacy_hours')) ?></li>
        </ul>
      </div>
    </div>
    <div class="mt-5 pt-3 d-flex flex-wrap justify-content-between gap-2 small" style="border-top:1px solid rgba(255,255,255,.12);color:#8aa0aa">
      <span>© <?= date('Y') ?> Medi-O Pharmacy</span>
      <span>Prescription medicines are dispensed only after pharmacist review.</span>
    </div>
  </div>
</footer>

<?php $bn = basename($_SERVER["SCRIPT_NAME"]); $me = current_user(); ?>
<nav class="gx-tabbar d-lg-none" aria-label="Main">
  <a class="<?= $bn === "index.php" ? "on" : "" ?>" href="<?= url("index.php") ?>"><i class="bi bi-house"></i>Home</a>
  <a class="<?= $bn === "shop.php" ? "on" : "" ?>" href="<?= url("shop.php") ?>"><i class="bi bi-grid"></i>Shop</a>
  <a class="rx <?= $bn === "prescriptions.php" ? "on" : "" ?>" href="<?= url("prescriptions.php") ?>"><i class="bi bi-camera"></i>Prescription</a>
  <a class="<?= $bn === "cart.php" ? "on" : "" ?>" href="<?= url("cart.php") ?>"><i class="bi bi-cart3"></i>Cart</a>
  <a class="<?= in_array($bn, ["profile.php","orders.php","login.php"]) ? "on" : "" ?>" href="<?= url($me ? "orders.php" : "login.php") ?>"><i class="bi bi-person"></i><?= $me ? "My orders" : "Log in" ?></a>
</nav>
<?php if (!is_staff()): ?><a class="mo-wa-fab" href="<?= url('chat.php') ?>" aria-label="Chat with a pharmacist"><i class="bi bi-chat-dots-fill"></i><span>Ask a pharmacist</span></a><?php endif; ?>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="<?= url('assets/js/app.js') ?>?v=<?= filemtime(__DIR__ . '/../assets/js/app.js') ?>"></script>
</body>
</html>

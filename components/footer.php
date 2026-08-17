</main>

<footer class="mo-footer mt-auto">
  <div class="container py-5">
    <div class="row g-4">
      <div class="col-lg-4">
        <div class="d-flex align-items-center gap-2 mb-3">
          <span class="mo-logo"><i class="bi bi-capsule"></i></span>
          <span class="fw-bold fs-4 text-white">Medi<span class="text-gradient">-O</span></span>
        </div>
        <p class="text-white-50 pe-lg-5">Your trusted online pharmacy — order medicines, upload prescriptions,
          and collect with your personal QR code. No more waiting in queues.</p>
        <div class="d-flex gap-2">
          <a class="mo-social" href="https://wa.me/<?= e(setting('pharmacy_whatsapp')) ?>" target="_blank" rel="noopener" aria-label="WhatsApp"><i class="bi bi-whatsapp"></i></a>
          <a class="mo-social" href="#" aria-label="Facebook"><i class="bi bi-facebook"></i></a>
          <a class="mo-social" href="#" aria-label="Instagram"><i class="bi bi-instagram"></i></a>
        </div>
      </div>
      <div class="col-6 col-lg-2">
        <h6 class="text-white mb-3">Shop</h6>
        <ul class="mo-footer-links">
          <li><a href="<?= url('shop.php') ?>">All products</a></li>
          <li><a href="<?= url('shop.php?category=Medicines') ?>">Medicines</a></li>
          <li><a href="<?= url('shop.php?category=Vitamins+%26+Supplements') ?>">Vitamins</a></li>
          <li><a href="<?= url('cart.php') ?>">My cart</a></li>
        </ul>
      </div>
      <div class="col-6 col-lg-2">
        <h6 class="text-white mb-3">Services</h6>
        <ul class="mo-footer-links">
          <li><a href="<?= url('prescriptions.php') ?>">Upload prescription</a></li>
          <li><a href="<?= url('medications.php') ?>">Medication tracker</a></li>
          <li><a href="<?= url('chat.php') ?>">Chat with pharmacy</a></li>
          <li><a href="<?= url('my-qr.php') ?>">My QR card</a></li>
        </ul>
      </div>
      <div class="col-6 col-lg-2">
        <h6 class="text-white mb-3">Company</h6>
        <ul class="mo-footer-links">
          <li><a href="<?= url('about.php') ?>">About us</a></li>
          <li><a href="<?= url('contact.php') ?>">Contact</a></li>
          <li><a href="<?= url('faq.php') ?>">FAQ</a></li>
          <li><a href="<?= url('privacy.php') ?>">Privacy policy</a></li>
          <li><a href="<?= url('terms.php') ?>">Terms of service</a></li>
        </ul>
      </div>
      <div class="col-6 col-lg-2">
        <h6 class="text-white mb-3">Visit us</h6>
        <ul class="mo-footer-links small">
          <li><i class="bi bi-geo-alt me-1"></i><?= e(setting('pharmacy_address')) ?></li>
          <li><i class="bi bi-telephone me-1"></i><?= e(setting('pharmacy_phone')) ?></li>
          <li><i class="bi bi-clock me-1"></i><?= e(setting('pharmacy_hours')) ?></li>
        </ul>
      </div>
    </div>
    <div class="border-top border-white border-opacity-10 mt-4 pt-3 d-flex flex-wrap justify-content-between text-white-50 small">
      <span>© <?= date('Y') ?> Medi-O Pharmacy. All rights reserved.</span>
      <span>Made with <i class="bi bi-heart-fill text-danger"></i> in Sri Lanka</span>
    </div>
  </div>
</footer>

<a class="mo-wa-fab" href="https://wa.me/<?= e(setting('pharmacy_whatsapp')) ?>?text=<?= rawurlencode('Hello Medi-O! I need some help.') ?>"
   target="_blank" rel="noopener" aria-label="Chat on WhatsApp"><i class="bi bi-whatsapp"></i></a>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="<?= url('assets/js/app.js') ?>"></script>
</body>
</html>

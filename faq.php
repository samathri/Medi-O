<?php
require_once __DIR__ . '/config.php';
$pageTitle = 'FAQ';

$faqs = [
    'Orders & payment' => [
        ['How do I pay for my order?', 'You can pay online by card through PayHere, or choose "Cash" and pay when you collect your order at the counter (or on delivery).'],
        ['Is online payment secure?', 'Yes — payments are processed by PayHere, Sri Lanka\'s leading payment gateway. Medi-O never sees or stores your card details.'],
        ['Can I cancel an order?', 'Contact us via chat or WhatsApp as soon as possible. Orders that haven\'t been prepared yet can be cancelled by our staff.'],
    ],
    'Prescriptions' => [
        ['How do I order prescription medicines?', 'Upload a clear photo or PDF of your prescription on the Prescriptions page. A licensed pharmacist reviews it (usually within a few hours) and you\'ll be notified when it\'s approved.'],
        ['What is the QR code on my prescription?', 'Every prescription gets a unique verification QR. Anyone scanning it sees an authenticity check confirming it\'s registered with Medi-O — without revealing your personal details.'],
        ['How long does verification take?', 'During opening hours, usually under 2 hours. You\'ll get an in-app notification, an email, and a WhatsApp message (if enabled).'],
    ],
    'QR collection' => [
        ['What is my personal QR card?', 'It\'s your permanent digital pass, found under "My QR Card". At the counter, the pharmacist scans it and instantly sees your ready orders and approved prescriptions — no queue, no explaining.'],
        ['What if I don\'t have my phone with me?', 'You can print your QR card, or simply tell the pharmacist your name and phone number — they can look you up manually.'],
        ['Can someone else collect for me?', 'Yes — share your QR card (or order QR) with a family member. Treat it like a claim ticket and only share it with people you trust.'],
    ],
    'Reminders & tracking' => [
        ['How do WhatsApp medicine reminders work?', 'Add your medicines and times in the Medication Tracker. When it\'s time for a dose, we send you a WhatsApp message (or email). Mark doses as taken to build your streak.'],
        ['Can the pharmacy set up my medication plan?', 'Yes! When you collect prescription medicines, our pharmacist can set the schedule for you — it appears in your tracker automatically.'],
        ['How do I stop reminders?', 'Pause or delete any medicine in your tracker, or turn off WhatsApp updates in Profile → Account details.'],
    ],
];

include __DIR__ . '/components/header.php';
?>
<div class="container py-4 py-md-5">
  <div class="page-head pt-2 text-center">
    <h1 class="h2">Frequently asked questions</h1>
    <p class="text-muted">Everything you need to know about ordering, prescriptions and QR pickup.</p>
  </div>

  <div class="row justify-content-center">
    <div class="col-lg-9">
      <?php $n = 0;
      foreach ($faqs as $group => $items): ?>
        <h2 class="h6 fw-bold text-uppercase text-muted mt-4 mb-3"><?= e($group) ?></h2>
        <div class="accordion mb-2" id="faq<?= md5($group) ?>">
          <?php foreach ($items as [$q, $a]): $n++; ?>
            <div class="accordion-item mo-card mb-2 overflow-hidden" data-reveal>
              <h3 class="accordion-header">
                <button class="accordion-button collapsed fw-semibold" type="button" data-bs-toggle="collapse" data-bs-target="#q<?= $n ?>">
                  <?= e($q) ?>
                </button>
              </h3>
              <div id="q<?= $n ?>" class="accordion-collapse collapse">
                <div class="accordion-body text-secondary"><?= e($a) ?></div>
              </div>
            </div>
          <?php endforeach; ?>
        </div>
      <?php endforeach; ?>

      <div class="mo-card p-4 text-center mt-4" data-reveal>
        <h2 class="h5 fw-bold">Still have a question?</h2>
        <p class="text-muted small mb-3">Our pharmacists are happy to help.</p>
        <a class="btn mo-btn me-2" href="<?= url('chat.php') ?>"><i class="bi bi-chat-dots me-1"></i>Chat with us</a>
        <a class="btn btn-outline-success" target="_blank" rel="noopener" href="https://wa.me/<?= e(setting('pharmacy_whatsapp')) ?>"><i class="bi bi-whatsapp me-1"></i>WhatsApp</a>
      </div>
    </div>
  </div>
</div>
<?php include __DIR__ . '/components/footer.php'; ?>

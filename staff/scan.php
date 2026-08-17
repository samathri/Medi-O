<?php
$pageTitle = 'Scan QR';
require_once __DIR__ . '/../components/staff-header.php';
?>
<div class="d-flex flex-wrap justify-content-between align-items-center mb-4">
  <h1 class="h3 fw-bold mb-0">Scan a customer QR</h1>
</div>

<div class="row g-4">
  <div class="col-lg-6">
    <div class="mo-card p-4">
      <div id="reader" class="rounded-3 overflow-hidden" style="max-width:480px;margin:auto"></div>
      <div class="text-center small text-muted mt-3"><i class="bi bi-camera me-1"></i>Point the camera at a
        customer's QR card, order QR, or prescription QR.</div>
    </div>
  </div>
  <div class="col-lg-6">
    <div class="mo-card p-4">
      <h2 class="h6 fw-bold text-uppercase text-muted mb-3">No camera? Enter code manually</h2>
      <form onsubmit="handleScan(this.code.value); return false;" class="d-flex gap-2">
        <input class="form-control" name="code" placeholder="Paste scanned URL or token…">
        <button class="btn mo-btn">Open</button>
      </form>
      <hr>
      <h2 class="h6 fw-bold text-uppercase text-muted mb-2">What each QR does</h2>
      <ul class="small text-muted mb-0 d-flex flex-column gap-2">
        <li><strong>Personal QR card</strong> — shows the customer's ready orders &amp; approved prescriptions
          so you can hand everything over and mark it collected.</li>
        <li><strong>Order QR</strong> (on the order receipt) — opens that exact order for collection.</li>
        <li><strong>Prescription QR</strong> — public authenticity check of a prescription.</li>
      </ul>
    </div>
  </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/html5-qrcode@2.3.8/html5-qrcode.min.js"></script>
<script>
let lastScan = 0;
function handleScan(text) {
  const now = Date.now();
  if (now - lastScan < 2500) return; // debounce duplicate reads
  lastScan = now;
  text = (text || '').trim();
  try {
    // Full URL from one of our QRs → follow it directly
    const u = new URL(text);
    if (u.searchParams.get('t')) {
      location.href = `${window.MEDIO.base}/staff/verify.php?t=${encodeURIComponent(u.searchParams.get('t'))}`;
      return;
    }
  } catch { /* not a URL — treat as raw token */ }
  if (text) location.href = `${window.MEDIO.base}/staff/verify.php?t=${encodeURIComponent(text)}`;
}
const scanner = new Html5Qrcode('reader');
Html5Qrcode.getCameras().then(cams => {
  if (!cams.length) throw new Error('no cam');
  const back = cams.find(c => /back|rear|environment/i.test(c.label)) || cams[0];
  scanner.start(back.id, {fps: 10, qrbox: {width: 240, height: 240}}, handleScan);
}).catch(() => {
  document.getElementById('reader').innerHTML =
    '<div class="alert alert-warning small m-0"><i class="bi bi-camera-video-off me-1"></i>' +
    'Camera not available. Use the manual entry box instead.</div>';
});
</script>
<?php require_once __DIR__ . '/../components/staff-footer.php'; ?>

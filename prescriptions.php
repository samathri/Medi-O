<?php
require_once __DIR__ . '/config.php';
require_once __DIR__ . '/includes/qr.php';
$u = require_login();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    csrf_check();
    $notes = trim($_POST['notes'] ?? '');
    $f = $_FILES['prescription'] ?? null;

    if (!$f || $f['error'] !== UPLOAD_ERR_OK) {
        flash('error', 'Please choose a file to upload (max 5 MB).');
    } elseif ($f['size'] > 5 * 1024 * 1024) {
        flash('error', 'File is too large — maximum size is 5 MB.');
    } else {
        $mime = mime_content_type($f['tmp_name']);
        $allowed = ['image/jpeg' => 'jpg', 'image/png' => 'png', 'image/webp' => 'webp', 'application/pdf' => 'pdf'];
        if (!isset($allowed[$mime])) {
            flash('error', 'Only JPG, PNG, WEBP or PDF files are accepted.');
        } else {
            $name = 'rx_' . $u['id'] . '_' . bin2hex(random_bytes(8)) . '.' . $allowed[$mime];
            $dest = ROOT_PATH . '/uploads/prescriptions/' . $name;
            if (move_uploaded_file($f['tmp_name'], $dest)) {
                q('INSERT INTO prescriptions (user_id, file_path, original_name, notes, verify_token) VALUES (?,?,?,?,?)', [
                    $u['id'], 'uploads/prescriptions/' . $name,
                    substr($f['name'], 0, 255), $notes ?: null, new_token(),
                ]);
                // Let every pharmacist know a new prescription is waiting
                foreach (q_all("SELECT id FROM users WHERE role IN ('pharmacist','admin')") as $staff) {
                    notify((int)$staff['id'], 'prescription', 'New prescription from ' . $u['name'],
                        'Waiting for review.', url('staff/prescriptions.php'));
                }
                flash('success', 'Prescription uploaded! Our pharmacist will review it shortly.');
            } else {
                flash('error', 'Upload failed — please try again.');
            }
        }
    }
    redirect('prescriptions.php');
}

$prescriptions = q_all('SELECT p.*, s.name AS pharmacist_name FROM prescriptions p
                        LEFT JOIN users s ON s.id = p.pharmacist_id
                        WHERE p.user_id = ? ORDER BY p.id DESC', [$u['id']]);

$pageTitle = 'My prescriptions';
include __DIR__ . '/components/header.php';
?>
<div class="container py-4 py-md-5">
  <div class="page-head pt-0">
    <h1 class="h2">My prescriptions</h1>
    <p class="text-muted mb-0">Upload a photo or PDF of your prescription — a licensed pharmacist verifies it,
      then you can order the medicines or collect them at the counter.</p>
  </div>

  <div class="row g-4">
    <div class="col-lg-5" data-reveal>
      <div class="mo-card p-4 position-sticky" style="top:90px">
        <h2 class="h5 fw-bold mb-3"><i class="bi bi-cloud-arrow-up me-2" style="color:var(--mo-primary)"></i>Upload new</h2>
        <form method="post" enctype="multipart/form-data" id="rxForm">
          <?= csrf_field() ?>
          <label id="dropZone" class="d-block border-2 rounded-4 text-center p-4 mb-3"
                 style="border:2px dashed var(--mo-line);cursor:pointer;transition:all .2s">
            <i class="bi bi-file-earmark-medical" style="font-size:2.4rem;color:var(--mo-primary)"></i>
            <div class="fw-semibold mt-2" id="dropLabel">Tap to choose a file<br><span class="small text-muted fw-normal">or drag &amp; drop — JPG, PNG, WEBP, PDF · max 5 MB</span></div>
            <input type="file" name="prescription" id="rxFile" class="d-none" accept=".jpg,.jpeg,.png,.webp,.pdf" required>
            <img id="rxPreview" class="img-fluid rounded-3 mt-2 d-none" style="max-height:180px" alt="">
          </label>
          <div class="mb-3">
            <label class="form-label fw-semibold">Notes for the pharmacist <span class="text-muted fw-normal">(optional)</span></label>
            <textarea name="notes" class="form-control" rows="2" placeholder="e.g. Monthly medicines for my mother"></textarea>
          </div>
          <button class="btn mo-btn w-100"><i class="bi bi-send me-2"></i>Submit for review</button>
        </form>
      </div>
    </div>

    <div class="col-lg-7">
      <?php if (!$prescriptions): ?>
        <div class="mo-card mo-empty" data-reveal>
          <i class="bi bi-file-medical"></i>
          <h2 class="h5">No prescriptions yet</h2>
          <p>Your uploaded prescriptions and their verification status will appear here.</p>
        </div>
      <?php else: ?>
        <div class="d-flex flex-column gap-3">
          <?php foreach ($prescriptions as $rx): ?>
            <div class="mo-card p-3 p-md-4" data-reveal>
              <div class="d-flex flex-wrap align-items-center gap-3">
                <div class="mo-step-icon m-0" style="width:46px;height:46px;font-size:1.2rem"><i class="bi bi-file-earmark-medical"></i></div>
                <div class="flex-grow-1 min-width-0">
                  <div class="fw-bold text-truncate"><?= e($rx['original_name']) ?></div>
                  <div class="small text-muted">Uploaded <?= time_ago($rx['created_at']) ?>
                    <?php if ($rx['pharmacist_name']): ?> · reviewed by <?= e($rx['pharmacist_name']) ?><?php endif; ?>
                  </div>
                </div>
                <span class="mo-status mo-status-<?= e($rx['status']) ?>"><?= ucfirst(e($rx['status'])) ?></span>
              </div>

              <?php if ($rx['review_note']): ?>
                <div class="alert <?= $rx['status'] === 'rejected' ? 'alert-danger' : 'alert-info' ?> small py-2 mt-3 mb-0">
                  <i class="bi bi-chat-left-text me-1"></i><strong>Pharmacist:</strong> <?= e($rx['review_note']) ?>
                </div>
              <?php endif; ?>

              <div class="d-flex flex-wrap gap-2 mt-3">
                <a class="btn btn-sm mo-btn-soft" href="<?= url('file.php?rx=' . $rx['id']) ?>" target="_blank"><i class="bi bi-eye me-1"></i>View file</a>
                <button class="btn btn-sm mo-btn-soft" data-bs-toggle="collapse" data-bs-target="#qr<?= $rx['id'] ?>"><i class="bi bi-qr-code me-1"></i>Verification QR</button>
                <?php if ($rx['status'] === 'ready'): ?>
                  <span class="mo-status mo-status-ready"><i class="bi bi-bag-check me-1"></i>Ready — collect at the counter</span>
                <?php endif; ?>
              </div>
              <div class="collapse mt-3 text-center" id="qr<?= $rx['id'] ?>">
                <img src="<?= qr_data_uri(url('verify-prescription.php?t=' . $rx['verify_token'])) ?>" alt="Verification QR" style="width:170px">
                <div class="small text-muted mt-1">Anyone scanning this sees an authenticity check —
                  no personal data is revealed.</div>
              </div>
            </div>
          <?php endforeach; ?>
        </div>
      <?php endif; ?>
    </div>
  </div>
</div>

<script>
const zone = document.getElementById('dropZone'), fileInput = document.getElementById('rxFile');
const preview = document.getElementById('rxPreview'), label = document.getElementById('dropLabel');
zone.addEventListener('dragover', e => { e.preventDefault(); zone.style.borderColor = 'var(--mo-primary)'; zone.style.background = '#f0f5f9'; });
zone.addEventListener('dragleave', () => { zone.style.borderColor = 'var(--mo-line)'; zone.style.background = ''; });
zone.addEventListener('drop', e => {
  e.preventDefault();
  zone.style.borderColor = 'var(--mo-line)'; zone.style.background = '';
  if (e.dataTransfer.files.length) { fileInput.files = e.dataTransfer.files; showFile(); }
});
fileInput.addEventListener('change', showFile);
function showFile() {
  const f = fileInput.files[0];
  if (!f) return;
  label.innerHTML = `<i class="bi bi-check-circle text-success me-1"></i>${f.name} <span class="small text-muted">(${(f.size/1024/1024).toFixed(1)} MB)</span>`;
  if (f.type.startsWith('image/')) {
    preview.src = URL.createObjectURL(f);
    preview.classList.remove('d-none');
  } else {
    preview.classList.add('d-none');
  }
}
</script>
<?php include __DIR__ . '/components/footer.php'; ?>

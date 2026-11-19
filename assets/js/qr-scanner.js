'use strict';

// Resolve only local Medi-O destinations; never follow an arbitrary scanned URL.
function medioQrTarget(text, base) {
  let token = String(text || '').trim();
  let prescription = false;
  try {
    const parsed = new URL(token);
    if (!['http:', 'https:'].includes(parsed.protocol)) throw new Error('Invalid QR URL');
    prescription = parsed.pathname.endsWith('/verify-prescription.php');
    if (!prescription && !parsed.pathname.endsWith('/staff/verify.php')) throw new Error('Unsupported QR URL');
    token = parsed.searchParams.get('t') || '';
  } catch (error) {
    if (token.includes('://')) throw error;
  }
  if (token.startsWith('r:')) { prescription = true; token = token.slice(2); }
  if (!(prescription ? /^[a-f0-9]{40}$/ : /^(?:[uo]:)?[a-f0-9]{40}$/).test(token)) throw new Error('Enter a valid Medi-O QR URL or token.');
  return base.replace(/\/$/, '') + (prescription ? '/verify-prescription.php?t=' : '/staff/verify.php?t=') + encodeURIComponent(token);
}

document.addEventListener('DOMContentLoaded', () => {
  const status = document.getElementById('scannerStatus');
  const button = document.getElementById('startScanner');
  const form = document.getElementById('manualQrForm');
  let navigating = false;
  const handleScan = text => {
    if (navigating) return;
    try { const target = medioQrTarget(text, window.MEDIO.base); navigating = true; window.location.assign(target); }
    catch (error) { status.textContent = error.message; }
  };
  form.addEventListener('submit', event => { event.preventDefault(); handleScan(form.elements.code.value); });
  let scanner;
  let attempt = 0;
  const timeout = promise => {
    let timer;
    return Promise.race([promise, new Promise((_, reject) => { timer = setTimeout(() => reject(new Error('Camera request timed out. Check camera permission or enter the code manually.')), 12000); })]).finally(() => clearTimeout(timer));
  };
  button.addEventListener('click', async () => {
    const current = ++attempt;
    button.disabled = true;
    status.textContent = 'Waiting for camera permission…';
    try {
      if (typeof Html5Qrcode === 'undefined') throw new Error('Scanner could not load. Check your connection or enter the code manually.');
      const cameras = await timeout(Html5Qrcode.getCameras());
      if (!cameras.length) throw new Error('No camera found. Enter the code manually.');
      scanner = scanner || new Html5Qrcode('reader');
      // A previous timed-out attempt may still hold the camera — release it first.
      try { await scanner.stop(); } catch (ignored) { /* not running */ }
      const camera = cameras.find(c => /back|rear|environment/i.test(c.label)) || cameras[0];
      const starting = scanner.start(camera.id, { fps: 10, qrbox: { width: 240, height: 240 } }, handleScan);
      starting.then(() => { if (current !== attempt) scanner.stop().catch(() => {}); }).catch(() => {});
      await timeout(starting);
      status.textContent = 'Camera ready. Point it at a Medi-O QR code.';
      button.hidden = true;
    } catch (error) {
      ++attempt;
      status.textContent = error instanceof Error ? error.message : 'Camera unavailable or permission denied. Enter the code manually.';
      button.disabled = false;
      button.textContent = 'Retry camera';
    }
  });
});

/* Medi-O v2 — shared client-side behaviour (vanilla JS, no build step). */

'use strict';

const $ = (sel, ctx = document) => ctx.querySelector(sel);
const $$ = (sel, ctx = document) => [...ctx.querySelectorAll(sel)];

/* ---------- Toasts ---------- */
function MedioToast(message, type = 'info', timeout = 4200) {
  const box = $('#toasts');
  if (!box) return alert(message);
  const icons = { success: 'bi-check-circle-fill text-success', error: 'bi-x-circle-fill text-danger', info: 'bi-info-circle-fill text-primary' };
  const el = document.createElement('div');
  el.className = `mo-toast ${type}`;
  el.innerHTML = `<i class="bi ${icons[type] || icons.info}"></i><div>${message}</div>`;
  box.appendChild(el);
  setTimeout(() => { el.classList.add('hide'); setTimeout(() => el.remove(), 350); }, timeout);
}
window.MedioToast = MedioToast;

/* ---------- Tiny fetch helper (adds CSRF header) ---------- */
async function api(path, data = null) {
  const opts = data
    ? { method: 'POST', headers: { 'Content-Type': 'application/json', 'X-CSRF': window.MEDIO.csrf }, body: JSON.stringify(data) }
    : { headers: { 'X-CSRF': window.MEDIO.csrf } };
  const res = await fetch(`${window.MEDIO.base}/api/${path}`, opts);
  const json = await res.json().catch(() => ({ ok: false, error: 'Bad response' }));
  if (!res.ok && json.error) throw new Error(json.error);
  return json;
}
window.medioApi = api;

/* ---------- Cart ---------- */
async function addToCart(productId, qty = 1, btn = null) {
  if (!window.MEDIO.loggedIn) {
    location.href = `${window.MEDIO.base}/login.php?add=${productId}&next=${encodeURIComponent(location.pathname + location.search)}`;
    return;
  }
  if (btn) { btn.disabled = true; btn.dataset.orig = btn.innerHTML; btn.innerHTML = '<span class="spinner-border spinner-border-sm"></span>'; }
  try {
    const r = await api('cart.php', { action: 'add', product_id: productId, qty });
    if (r.ok) {
      updateCartBadge(r.count);
      MedioToast(r.message || 'Added to cart', 'success');
      return true;
    }
    MedioToast(r.error || 'Could not add to cart', 'error');
    return false;
  } catch (e) {
    MedioToast(e.message, 'error');
    return false;
  } finally {
    if (btn) { btn.disabled = false; btn.innerHTML = btn.dataset.orig; }
  }
}
window.addToCart = addToCart;

function updateCartBadge(count) {
  const b = $('#cartBadge');
  if (!b) return;
  b.textContent = count;
  b.hidden = count <= 0;
}
window.updateCartBadge = updateCartBadge;

document.addEventListener('click', e => {
  const btn = e.target.closest('[data-add-to-cart]');
  if (btn) {
    e.preventDefault();
    const qtyInput = $('#qtyInput');
    addToCart(+btn.dataset.addToCart, qtyInput ? +qtyInput.value : 1, btn)
      .then(ok => { if (ok && btn.hasAttribute('data-buy-now')) location.href = `${window.MEDIO.base}/cart.php`; });
  }
});

/* ---------- Live search ---------- */
const searchInput = $('#liveSearch');
if (searchInput) {
  let t;
  const resultsBox = $('#searchResults');
  searchInput.addEventListener('input', () => {
    clearTimeout(t);
    const qy = searchInput.value.trim();
    if (qy.length < 2) { resultsBox.innerHTML = ''; return; }
    t = setTimeout(async () => {
      try {
        const r = await fetch(`${window.MEDIO.base}/api/search.php?q=${encodeURIComponent(qy)}`);
        const items = await r.json();
        resultsBox.innerHTML = items.length
          ? items.map(p => `
            <a class="mo-search-result" href="${window.MEDIO.base}/product.php?id=${p.id}">
              <img src="${p.image}" alt="" loading="lazy">
              <div class="flex-grow-1">
                <div class="fw-semibold small">${p.name}</div>
                <div class="small text-muted">${p.category ?? ''}</div>
              </div>
              <div class="fw-bold text-nowrap">${p.price}</div>
            </a>`).join('')
          : '<div class="text-center text-muted py-3">No products found</div>';
      } catch { /* ignore */ }
    }, 250);
  });
  $('#searchModal')?.addEventListener('shown.bs.modal', () => searchInput.focus());
}

/* ---------- Notifications dropdown ---------- */
const notifList = $('#notifList');
if (notifList && window.MEDIO.loggedIn) {
  let loaded = false;
  document.addEventListener('show.bs.dropdown', async e => {
    if (!e.target.closest('.dropdown')?.contains(notifList) || loaded) return;
    loaded = true;
    try {
      const r = await api('notifications.php');
      notifList.innerHTML = r.items.length
        ? r.items.map(n => `
          <a class="mo-notif-item ${+n.is_read ? '' : 'unread'}" href="${n.link || '#'}">
            <div class="fw-semibold small">${n.title}</div>
            ${n.body ? `<div class="small text-muted">${n.body}</div>` : ''}
            <div class="small text-muted mt-1"><i class="bi bi-clock me-1"></i>${n.ago}</div>
          </a>`).join('')
        : '<div class="mo-empty py-4"><i class="bi bi-bell-slash"></i>No notifications yet</div>';
    } catch {
      notifList.innerHTML = '<div class="text-center text-muted py-3 small">Could not load</div>';
    }
  });
  $('#markAllRead')?.addEventListener('click', async () => {
    await api('notifications.php', { action: 'read_all' });
    $('#notifBadge')?.setAttribute('hidden', '');
    $$('.mo-notif-item.unread').forEach(el => el.classList.remove('unread'));
  });
}

/* ---------- Quantity steppers ---------- */
document.addEventListener('click', e => {
  const step = e.target.closest('[data-step]');
  if (!step) return;
  const input = step.parentElement.querySelector('input');
  const v = Math.max(1, Math.min(+input.max || 99, (+input.value || 1) + +step.dataset.step));
  input.value = v;
  input.dispatchEvent(new Event('change', { bubbles: true }));
});

/* ---------- Auto-dismiss password visibility toggles ---------- */
document.addEventListener('click', e => {
  const t = e.target.closest('[data-toggle-password]');
  if (!t) return;
  const input = $(t.dataset.togglePassword);
  if (!input) return;
  input.type = input.type === 'password' ? 'text' : 'password';
  t.querySelector('i').className = input.type === 'password' ? 'bi bi-eye' : 'bi bi-eye-slash';
});

/* Content shows immediately (no reveal animation) — faster to read. */

// "/" opens product search
document.addEventListener('keydown', e => {
  if (e.key !== '/' || /input|textarea|select/i.test(document.activeElement.tagName)) return;
  const m = document.getElementById('searchModal');
  if (m && window.bootstrap) { e.preventDefault(); bootstrap.Modal.getOrCreateInstance(m).show(); }
});

/* ---------- Live alerts (web + installed app) ---------- */
if (window.MEDIO.loggedIn) {
  let lastId = null;
  const showSystem = n => {
    if (!('Notification' in window) || Notification.permission !== 'granted' || !document.hidden) return;
    const note = { body: n.body, icon: `${window.MEDIO.base}/assets/img/app-icon-192.png`, tag: 'medio-' + n.id, data: { link: n.link } };
    navigator.serviceWorker?.getRegistration().then(reg => reg ? reg.showNotification(n.title, note) : new Notification(n.title, note));
  };
  const esc = s => String(s).replace(/[&<>"']/g, c => ({ '&': '&amp;', '<': '&lt;', '>': '&gt;', '"': '&quot;', "'": '&#39;' }[c]));
  const poll = async () => {
    try {
      const r = await api(`notifications.php?since=${lastId ?? 2147483647}`);
      const badge = $('#notifBadge');
      if (badge) { badge.textContent = r.unread; badge.hidden = r.unread <= 0; }
      if (lastId !== null) r.items.forEach(n => {
        MedioToast(`${esc(n.title)}${n.body ? ' — ' + esc(n.body.slice(0, 120)) : ''}`, 'info');
        showSystem(n);
      });
      lastId = r.last;
    } catch { /* offline — try again next round */ }
  };
  poll();
  setInterval(poll, 20000);
  document.addEventListener('visibilitychange', () => { if (!document.hidden) poll(); });
}

/* ---------- Installable app (PWA) ---------- */
if ('serviceWorker' in navigator) {
  navigator.serviceWorker.register(`${window.MEDIO.base}/sw.js`, { scope: `${window.MEDIO.base}/` }).catch(() => {});
}
(() => {
  const inApp = window.matchMedia('(display-mode: standalone)').matches || navigator.standalone || /MediOApp/i.test(navigator.userAgent);
  const store = (k, v) => { try { return v === undefined ? localStorage.getItem(k) : localStorage.setItem(k, v); } catch { return null; } };
  const bar = (html, key) => {
    if (store(key)) return null;
    const el = document.createElement('div');
    el.className = 'gx-appbar';
    el.innerHTML = html + '<button class="gx-appbar-x" aria-label="Dismiss"><i class="bi bi-x-lg"></i></button>';
    el.querySelector('.gx-appbar-x').onclick = () => { store(key, '1'); el.remove(); };
    document.body.appendChild(el);
    return el;
  };

  // 1) "Install the app" (Android/desktop Chrome & Edge give us an install prompt)
  let deferred = null;
  window.addEventListener('beforeinstallprompt', e => {
    e.preventDefault(); deferred = e;
    if (inApp) return;
    const el = bar('<i class="bi bi-phone"></i><span><b>Get the Medi-O app</b> — install it on this device in one tap.</span><button class="btn btn-sm mo-btn-yellow" data-install>Install</button>', 'medio-install-dismissed');
    el?.querySelector('[data-install]').addEventListener('click', async () => {
      deferred.prompt(); await deferred.userChoice; deferred = null; el.remove();
    });
  });
  $$('[data-install-app]').forEach(b => b.addEventListener('click', () => {
    if (deferred) { deferred.prompt(); return; }
    MedioToast(/iPhone|iPad/i.test(navigator.userAgent)
      ? 'On iPhone: tap the Share button, then “Add to Home Screen”.'
      : 'Open the browser menu (⋮) and choose “Install app” or “Add to Home screen”.', 'info', 7000);
  }));

  // 2) "Turn on alerts on this device" (system notifications while the app/site is open in the background)
  if (window.MEDIO.loggedIn && 'Notification' in window && Notification.permission === 'default') {
    const el = bar('<i class="bi bi-bell"></i><span><b>Turn on alerts</b> for order updates, chat replies and dose reminders on this device.</span><button class="btn btn-sm mo-btn-yellow" data-alerts>Turn on</button>', 'medio-alerts-dismissed');
    el?.querySelector('[data-alerts]').addEventListener('click', async () => {
      const p = await Notification.requestPermission();
      el.remove(); store('medio-alerts-dismissed', '1');
      MedioToast(p === 'granted' ? 'Alerts are on for this device.' : 'Alerts stay off — you\'ll still get emails.', p === 'granted' ? 'success' : 'info');
    });
  }
})();

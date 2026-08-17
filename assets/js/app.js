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
    location.href = `${window.MEDIO.base}/login.php`;
    return;
  }
  if (btn) { btn.disabled = true; btn.dataset.orig = btn.innerHTML; btn.innerHTML = '<span class="spinner-border spinner-border-sm"></span>'; }
  try {
    const r = await api('cart.php', { action: 'add', product_id: productId, qty });
    if (r.ok) {
      updateCartBadge(r.count);
      MedioToast(r.message || 'Added to cart', 'success');
    } else {
      MedioToast(r.error || 'Could not add to cart', 'error');
    }
  } catch (e) {
    MedioToast(e.message, 'error');
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
    addToCart(+btn.dataset.addToCart, qtyInput ? +qtyInput.value : 1, btn);
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

/* ---------- Reveal-on-scroll ---------- */
const io = new IntersectionObserver(entries => {
  entries.forEach(en => { if (en.isIntersecting) { en.target.style.opacity = 1; en.target.style.transform = 'none'; io.unobserve(en.target); } });
}, { threshold: .12 });
$$('[data-reveal]').forEach(el => {
  el.style.opacity = 0;
  el.style.transform = 'translateY(22px)';
  el.style.transition = 'all .55s cubic-bezier(.2,.7,.3,1)';
  io.observe(el);
});

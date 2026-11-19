/* Medi-O service worker — makes the site installable as an app.
 * Pages and API calls always go to the server first (so web and app show the same live data);
 * only static files are cached, and a friendly offline page is shown when there's no connection. */
const VERSION = 'medio-v1';
const STATIC = ['offline.html', 'assets/css/app.css', 'assets/js/app.js',
                'assets/img/app-icon-192.png', 'assets/img/app-icon-512.png'];

self.addEventListener('install', e => {
  e.waitUntil(caches.open(VERSION).then(c => c.addAll(STATIC)).then(() => self.skipWaiting()));
});

self.addEventListener('activate', e => {
  e.waitUntil(caches.keys()
    .then(keys => Promise.all(keys.filter(k => k !== VERSION).map(k => caches.delete(k))))
    .then(() => self.clients.claim()));
});

self.addEventListener('fetch', e => {
  const req = e.request;
  if (req.method !== 'GET' || new URL(req.url).origin !== location.origin) return;

  // Pages: always live from the server; offline page if unreachable
  if (req.mode === 'navigate') {
    e.respondWith(fetch(req).catch(() => caches.match('offline.html')));
    return;
  }
  // Static assets (css/js/images): cache, refreshed in the background
  if (/\.(css|js|png|jpg|jpeg|webp|svg|woff2?)$/i.test(new URL(req.url).pathname)) {
    e.respondWith(caches.open(VERSION).then(async cache => {
      const hit = await cache.match(req);
      const net = fetch(req).then(res => { if (res.ok) cache.put(req, res.clone()); return res; }).catch(() => hit);
      return hit || net;
    }));
  }
  // Everything else (API, uploads) goes straight to the network.
});

// Tapping a system notification opens the related page in the app
self.addEventListener('notificationclick', e => {
  e.notification.close();
  const link = (e.notification.data && e.notification.data.link) || './';
  e.waitUntil(self.clients.matchAll({ type: 'window', includeUncontrolled: true }).then(list => {
    for (const c of list) { if ('focus' in c) { c.navigate(link); return c.focus(); } }
    return self.clients.openWindow(link);
  }));
});

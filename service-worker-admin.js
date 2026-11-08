const CACHE_VERSION = 'toapp-admin-v1';
const PRECACHE_URLS = [
  '/offline-admin.html',
  '/manifest-admin.webmanifest',
  '/assets/global/img/pwa/icon-192.png',
  '/assets/global/img/pwa/icon-512.png',
  '/assets/global/img/pwa/apple-touch-icon.png',
];

self.addEventListener('install', (event) => {
  event.waitUntil(
    caches.open(CACHE_VERSION).then((cache) => cache.addAll(PRECACHE_URLS)).then(() => self.skipWaiting())
  );
});

self.addEventListener('activate', (event) => {
  event.waitUntil(
    caches.keys().then((keys) =>
      Promise.all(keys.filter((key) => key !== CACHE_VERSION).map((key) => caches.delete(key)))
    ).then(() => self.clients.claim())
  );
});

function isNavigationRequest(request) {
  return request.mode === 'navigate'
    || (request.method === 'GET' && request.headers.get('accept')?.includes('text/html'));
}

function shouldBypass(url) {
  if (url.origin !== self.location.origin) {
    return true;
  }
  const path = url.pathname;
  if (!path.startsWith('/adto-app')) {
    return true;
  }
  return path.includes('/api/') || path.endsWith('/unread') || path.endsWith('/messages');
}

self.addEventListener('fetch', (event) => {
  const { request } = event;
  if (request.method !== 'GET') {
    return;
  }

  const url = new URL(request.url);
  if (shouldBypass(url)) {
    return;
  }

  if (isNavigationRequest(request)) {
    event.respondWith(
      fetch(request).catch(() => caches.match('/offline-admin.html'))
    );
    return;
  }

  if (
    url.pathname.startsWith('/assets/global/img/pwa/')
    || url.pathname.endsWith('.webmanifest')
    || url.pathname === '/offline-admin.html'
  ) {
    event.respondWith(
      caches.match(request).then((cached) => {
        const network = fetch(request).then((response) => {
          if (response && response.ok) {
            const copy = response.clone();
            caches.open(CACHE_VERSION).then((cache) => cache.put(request, copy));
          }
          return response;
        }).catch(() => cached);
        return cached || network;
      })
    );
    return;
  }

  event.respondWith(
    fetch(request).catch(() => caches.match(request))
  );
});

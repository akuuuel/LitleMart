const CACHE_NAME = 'litlemart-v1';
const STATIC_ASSETS = [
  './',
  './manifest.json',
  'https://cdn.tailwindcss.com',
  'https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js',
  'https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;900&display=swap'
];

// Install: Cache static assets
self.addEventListener('install', event => {
  event.waitUntil(
    caches.open(CACHE_NAME).then(cache => cache.addAll(STATIC_ASSETS))
  );
  self.skipWaiting();
});

// Activate: Clean old caches
self.addEventListener('activate', event => {
  event.waitUntil(
    caches.keys().then(keys => Promise.all(
      keys.filter(key => key !== CACHE_NAME).map(key => caches.delete(key))
    ))
  );
});

// Fetch: Network First for pages, Cache First for assets
self.addEventListener('fetch', event => {
  const url = new URL(event.request.url);

  // For HTML navigation requests, try Network First
  if (event.request.mode === 'navigate') {
    event.respondWith(
      fetch(event.request)
        .catch(() => caches.match('/')) // Fallback to home if offline
    );
    return;
  }

  // For static assets (CSS, JS, Fonts, Images), try Cache First
  event.respondWith(
    caches.match(event.request).then(response => {
      return response || fetch(event.request).then(fetchResponse => {
        // Cache new assets on the fly
        if (event.request.url.match(/\.(js|css|png|jpg|jpeg|gif|woff2|svg)$/)) {
          return caches.open(CACHE_NAME).then(cache => {
            cache.put(event.request, fetchResponse.clone());
            return fetchResponse;
          });
        }
        return fetchResponse;
      });
    })
  );
});

const OFFLINE_VERSION = 1;
const CACHE_NAME = 'suplike';
const OFFLINE_URL = 'offline.html';
self.addEventListener('install', (event) => {
  event.waitUntil((async () => {
    const cache = await caches.open(CACHE_NAME);
    await cache.add(new Request(OFFLINE_URL, { cache: 'reload' }));

    await cache.addAll([
      // List the URLs of the files in the 'libs' folder
      './lib/font-awesome/css/all.min.css',
      './lib/font-awesome/webfonts/fa-solid-900.woff2',
      './lib/font-awesome/webfonts/fa-regular-400.woff2',
      './lib/bootstrap/css/bootstrap.min.css',
      './lib/bootstrap/js/bootstrap.bundle.min.js',
    ]);

  })());
});

self.addEventListener('activate', (event) => {
  event.waitUntil((async () => {
    if ('navigationPreload' in self.registration) {
      await self.registration.navigationPreload.enable();
    }
  })());

  // Tell the active service worker to take control of the page immediately.
  self.clients.claim();
});

self.addEventListener('fetch', (event) => {
  // We only want to call event.respondWith() if this is a navigation request
  // for an HTML page.
  if (event.request.mode === 'navigate') {
    event.respondWith((async () => {
      try {
        // First, try to use the navigation preload response if it's supported.
        const preloadResponse = await event.preloadResponse;
        if (preloadResponse) {
          return preloadResponse;
        }

        // Check if the requested resource is one of the library files
        const requestUrl = new URL(event.request.url);
        if (
          requestUrl.href.includes('lib/font-awesome/') ||
          requestUrl.href.includes('lib/bootstrap/')
        ) {
          const cache = await caches.open(CACHE_NAME);
          const cachedResponse = await cache.match(event.request);
          if (cachedResponse) {
            return cachedResponse;
          }
        }

        // If the requested resource is not in cache, fetch it from the network
        const networkResponse = await fetch(event.request);
        return networkResponse;
      } catch (error) {
        console.log('Fetch failed; returning offline page instead.', error);

        const cache = await caches.open(CACHE_NAME);
        const cachedResponse = await cache.match(OFFLINE_URL);
        return cachedResponse;
      }
    })());
  }
});

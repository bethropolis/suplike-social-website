self.addEventListener('install', function(e) {
  e.waitUntil(
      caches.open('pwa-example').then(function(cache) {
          return cache.addAll([
              '/',
              '/index.php', 
              '/header.php',
              '/footer.php',
              '/social.php', 
              '/settings.php', 
              '/message.php', 
              '/post.php', 
              '/profile.php', 
              '/search.php',  
              '/css',
              '/css/chat.css',
              '/css/style.css' 
          ]);
      })
  );
});

self.addEventListener('fetch', function(event) {
  event.respondWith(
      caches.match(event.request).then(function(response) {
          return response || fetch(event.request);
      })
  );
});
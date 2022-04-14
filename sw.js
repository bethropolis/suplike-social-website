const Suplike = "suplike-V1";

const assets = [
    './',
    './index.php',
    './header.php',
    './footer.php',
    './social.php',
    './settings.php',
    './message.php',
    './post.php',
    './profile.php',
    './notification.php',
    './css/chat.min.css',
    './css/style.min.css',
    './css/comment.min.css',
    './css/search.min.css',
    './css/post.min.css',
    './js/index.min.js',
    './js/loader.js',
    './js/online.js',
    './js/registerSW.js',
    './sw.js',
    './manifest.json',
    './lib/bootstrap/css/bootstrap.min.css',
    './lib/font-awesome/font-awesome.min.css',
    './lib/jquery/jquery.min.js',
    './lib/bootstrap/js/bootstrap.min.js',
    './lib/lightbox/lightbox.min.css',
    './lib/lightbox/lightbox.min.js',
    './lib/vue/vue.min.js',
    './lib/lazyload/jquery.lazyload-any.js',
    './img/logo.png',
    './img/M.jpg'
]

self.addEventListener('fetch', function (event) {
    let online = navigator.onLine
    if (!online) {
        event.respondWith(
            caches.match(event.request).then(function (res) {
                if (res) {
                    return res;
                }
                requestBackend(event);
            })
        )
    }
});

self.addEventListener('activate', function (event) {
    event.waitUntil(
        caches.keys().then(function (keys) {
            return Promise.all(keys.map(function (key, i) {
                if (key !== Suplike) {
                    return caches.delete(keys[i]);
                }
            }))
        })
    )
});

function requestBackend(event) {
    var url = event.request.clone();
    return fetch(url).then(function (res) {
        //if not a valid response send the error
        if (!res || res.status !== 200 || res.type !== 'basic') {
            return res;
        }

        var response = res.clone();

        caches.open(Suplike).then(function (cache) {
            cache.put(event.request, response);
        });

        return res;
    })
}
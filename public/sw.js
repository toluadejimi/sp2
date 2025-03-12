self.addEventListener("install", event => {
    event.waitUntil(
        caches.open("pwa-cache").then(cache => {
            return cache.addAll([
                "/",
                "/css/app.css",
                "/js/app.js",
                "/images/icons/icon-192x192.png"
            ]);
        })
    );
});

self.addEventListener("fetch", event => {
    event.respondWith(
        caches.match(event.request).then(response => {
            return response || fetch(event.request);
        })
    );
});

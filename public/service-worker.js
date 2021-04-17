// This is the service worker with the Cache-first network

importScripts('https://storage.googleapis.com/workbox-cdn/releases/5.0.0/workbox-sw.js');

const {registerRoute} = workbox.routing;
const {CacheFirst} = workbox.strategies;
const {NetworkFirst} = workbox.strategies;
const {StaleWhileRevalidate} = workbox.strategies;
const {ExpirationPlugin} = workbox.expiration;





self.addEventListener("message", (event) => {
    if (event.data && event.data.type === "SKIP_WAITING") {
        self.skipWaiting();
    }
});

// cache 5000 images for 60 minutes
registerRoute(
    ({request}) => request.destination === 'image',
    new CacheFirst({
        cacheName: 'images',
        plugins: [
            new ExpirationPlugin({
                maxEntries: 5000,
                maxAgeSeconds: 60 * 60,
            }),
        ],
    })
);

// cache css & scripts
registerRoute(
    ({request}) => request.destination === 'script' ||
    request.destination === 'style',
    new StaleWhileRevalidate({
        cacheName: 'static-resources',
    })
);


// cache fonts & fontawesome
registerRoute(
    ({url}) => url.origin === 'https://fonts.googleapis.com' ||
    url.origin === 'https://fonts.googleapis.com' ||
    url.origin === 'https://use.fontawesome.com',
    new StaleWhileRevalidate(),
);


// cache everything
registerRoute(
    new RegExp('/*'),
    new NetworkFirst({
        cacheName: 'pages-content'
    })
);
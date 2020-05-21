// This is the service worker with the Cache-first network

const CACHE = "pwabuilder-precache";

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

// cache 500 images for 30 days
registerRoute(
    ({request}) => request.destination === 'image',
    new CacheFirst({
        cacheName: 'images',
        plugins: [
            new ExpirationPlugin({
                maxEntries: 500,
                maxAgeSeconds: 30 * 24 * 60 * 60, // 30 Days
            }),
        ],
    })
);

// cache css & scripts
registerRoute(
    ({request}) => request.destination === 'script' ||
    request.destination === 'style',
    new CacheFirst({
        cacheName: 'static-resources',
        plugins: [
            new ExpirationPlugin({
                maxEntries: 500,
                maxAgeSeconds: 60 * 60 * 24, // 1  Day
            }),
        ],
    })
);

// cache fonts & fontawesome
registerRoute(
    ({url}) => url.origin === 'https://fonts.googleapis.com' ||
    url.origin === 'https://fonts.googleapis.com' ||
    url.origin === 'https://use.fontawesome.com',
    new CacheFirst(),
);


// cache everything
registerRoute(
    new RegExp('/*'),
    new NetworkFirst({
        cacheName: CACHE
    })
);

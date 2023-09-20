// This is the service worker with the Cache-first network


importScripts('https://storage.googleapis.com/workbox-cdn/releases/6.2.0/workbox-sw.js');

// this way of importing stuff works without "module" feature of JS.
// https://stackoverflow.com/questions/69436284/instantiate-javascript-object-from-constant
const {registerRoute} = workbox.routing;
const {Route} = workbox.routing;
const {NavigationRoute} = workbox.routing;
const {CacheFirst} = workbox.strategies;
const {NetworkFirst} = workbox.strategies;
const {StaleWhileRevalidate} = workbox.strategies;
const {ExpirationPlugin} = workbox.expiration;

// Handle images:
const imageRoute = new Route(({ request }) => {
    return request.destination === 'image'
}, new StaleWhileRevalidate({
    cacheName: 'images'
}));

// Handle scripts:
const scriptsRoute = new Route(({ request }) => {
    return request.destination === 'script';
}, new CacheFirst({
    cacheName: 'scripts'
}));

// Handle styles:
const stylesRoute = new Route(({ request }) => {
    return request.destination === 'style';
}, new CacheFirst({
    cacheName: 'styles'
}));


// Handle the rest:
const navigationRoute = new NavigationRoute(new NetworkFirst({
    cacheName: 'navigations'
  }));



// Register routes
registerRoute(imageRoute);
registerRoute(scriptsRoute);
registerRoute(stylesRoute);
registerRoute(navigationRoute);
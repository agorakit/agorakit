let mix = require('laravel-mix');
let tailwindcss = require('tailwindcss');

require('laravel-mix-purgecss');


mix.js('resources/js/app.js', 'public/js')

mix.sass('resources/sass/app.scss', 'public/css')
    .options({
        processCssUrls: false,
        postCss: [tailwindcss('tailwind.config.js')],
    })


if (mix.inProduction()) {
    mix.purgeCss({
        whitelistPatterns: [/show/, /tooltip/, /dropdown/],
    })
        .version();
}



mix.browserSync({ 
    proxy: '127.0.0.1:8000',
    notify: false 
});

// unpoly
mix.copy('node_modules/unpoly/dist/unpoly.min.js', 'public/js/unpoly.js');
mix.copy('node_modules/unpoly/dist/unpoly.min.css', 'public/css/unpoly.css');


// selectize js
mix.copy('node_modules/selectize.js/dist/js/standalone/selectize.min.js', 'public/js/selectize.js');
mix.copy('node_modules/selectize.js/dist/css/selectize.bootstrap3.css', 'public/css/selectize.css');

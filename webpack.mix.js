let mix = require('laravel-mix');



mix.js('resources/js/app.js', 'public/js')
.sass('resources/sass/app.scss', 'public/css');

mix.browserSync({proxy: '127.0.0.1:8000'});


mix.copy('node_modules/unpoly/dist/unpoly.min.js', 'public/js/unpoly.min.js');

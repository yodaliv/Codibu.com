const mix = require('laravel-mix');

/*
 |--------------------------------------------------------------------------
 | Mix Asset Management
 |--------------------------------------------------------------------------
 |
 | Mix provides a clean, fluent API for defining some Webpack build steps
 | for your Laravel application. By default, we are compiling the Sass
 | file for the application as well as bundling up all the JS files.
 |
 */

mix.js('resources/js/version-2/app.js', 'public/js/app.bootstrap.5.1.js')
    .js('resources/views/dashboard/dashboard.js', 'public/js/dashboard.js')
    .sass('resources/sass/version-2/app.scss', 'public/css/app.bootstrap.5.1.css')
    //.sass('resources/sass/app.scss', 'public/css')
    //.js('resources/js/app.js', 'public/js')
    // .copyDirectory('resources/plugins', 'public/plugins');

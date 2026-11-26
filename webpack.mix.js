const mix = require('laravel-mix');

/*
 |--------------------------------------------------------------------------
 | Mix Asset Management - Gearsense Dashboard
 |--------------------------------------------------------------------------
 |
 | Bundling Bootstrap 5 assets.
 |
 */

mix.js('resources/js/app.js', 'public/js')
    .postCss('resources/css/app.css', 'public/css', [
        require('autoprefixer'),
    ]);

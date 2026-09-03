const mix = require('laravel-mix');

mix.js('resources/js/app.js', 'public/js')
   .js('resources/js/pages/home.js', 'public/js/pages')
   .js('resources/js/pages/edit.js', 'public/js/pages')
   .css('resources/css/app.css', 'public/css');
const mix         = require('laravel-mix');
const tailwindcss = require('tailwindcss');

mix.sass('resources/sass/app.scss', 'public/css')
   .options({
       processCssUrls: false,
       postCss: [tailwindcss('./tailwind.config.js')],
   })
   .js('resources/js/app.js', 'public/js')
   .sass('resources/sass/multitenancy/app.scss', 'public/css/multitenancy.css')
   .copy('resources/images', 'public/images')
   .copy('node_modules/@fortawesome/fontawesome-pro/webfonts', 'public/webfonts');

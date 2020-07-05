let mix = require('laravel-mix');
let tailwindcss = require('tailwindcss');

mix
    .js('resources/js/architect.js', 'public')
    .extract()
    .setPublicPath('public')
    .postCss('resources/css/architect.css', 'public', [tailwindcss('tailwind.config.js')])
    .version();

if (!mix.inProduction()) {
    mix.sourceMaps();
}

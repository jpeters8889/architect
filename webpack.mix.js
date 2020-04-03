let mix = require('laravel-mix');
let tailwindcss = require('tailwindcss');

mix
    .js('resources/js/architect.js', 'public')
    .extract([
        'jpeters-vue-ui',
        'vue',
        'vue-router',
        'portal-vue',
        'lodash',
        'axios',
        'architect-js-helpers',
        '@fortawesome/fontawesome-svg-core',
        '@fortawesome/free-brands-svg-icons',
        '@fortawesome/free-regular-svg-icons',
        '@fortawesome/free-solid-svg-icons',
        '@fortawesome/vue-fontawesome',
    ])
    .setPublicPath('public')
    .postCss('resources/css/architect.css', 'public', [tailwindcss('tailwind.config.js')])
    .version()
    .webpackConfig({ devtool: "inline-source-map" });

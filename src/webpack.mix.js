const mix = require('laravel-mix');
require('laravel-mix-jigsaw');

mix.disableSuccessNotifications();
mix.setPublicPath('source/assets');

mix.jigsaw()
    // .js('source/_assets/js/main.js', 'js')
    .sass('source/assets/scss/style.scss', 'stylesheets')
    .options({
        processCssUrls: false,
    })
    .version();

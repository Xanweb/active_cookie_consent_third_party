const mix = require('laravel-mix');

mix.webpackConfig({
    resolve: {
        symlinks: false
    },
    externals: {
        jquery: 'jQuery',
        vue: 'Vue',
    }
})

mix.options({
    processCssUrls: false
})

mix.setPublicPath('..')

mix.js('assets/js/index.js', 'js/acc-third-party.js')
   .sass('assets/sass/main.scss', 'css/acc-third-party.css')

mix.disableNotifications()

// Disable mix-manifest.json
// @see https://github.com/JeffreyWay/laravel-mix/issues/580
Mix.manifest.refresh = _ => void 0;

const mix = require('laravel-mix');

mix.webpackConfig({
    resolve: {
        symlinks: false
    },
    externals: {
        jquery: 'jQuery',
        vue: 'Vue',
        cookie: 'Cookie'
    }
})

mix.options({
    processCssUrls: false
})

mix.setPublicPath('..')

mix.sass('assets/sass/google-map.scss', 'css')
    .js('assets/js/google-map.js', 'js')

mix.sass('assets/sass/youtube.scss', 'css')
    .js('assets/js/youtube.js', 'js')

mix.sass('assets/sass/gorwthcurve-vimeo-video.scss', 'css')
    .js('assets/js/gorwthcurve-vimeo-video.js', 'js')

mix.disableNotifications()

// Disable mix-manifest.json
// @see https://github.com/JeffreyWay/laravel-mix/issues/580
Mix.manifest.refresh = _ => void 0;

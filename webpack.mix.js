let mix = require('laravel-mix');

mix.js('src/app.js', 'js')
    .sass('src/main.scss', 'css')
        .setPublicPath('dist');

mix.browserSync('http://localhost/~hetu/Sandbox/Eclipse_progs/_AppForShow');
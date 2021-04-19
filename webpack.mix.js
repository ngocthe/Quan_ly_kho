const mix = require('laravel-mix');
const config = require("./webpack.config.js");
mix.config.fileLoaderDirs.images = 'assets/images';
mix.js("resources/js/main.js", "public/js/app.js");
require('vuetifyjs-mix-extension')
if(mix.inProduction()){
    mix.vuetify('vuetify-loader')
}; 
mix.sass('resources/sass/app.scss', 'public/css')
mix.webpackConfig(config);
mix.disableNotifications();
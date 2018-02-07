var elixir = require('laravel-elixir');
var htmlmin = require('gulp-htmlmin');
var gulp = require('gulp');

require('elixir-jshint');

elixir.config.production = true;
elixir.config.sourcemaps = true;
elixir.config.assetsPath = 'resources/assets/';
elixir.config.publicPath = 'public/assets/';

// elixir.config.css.sass.folder = 'scss';
// elixir.config.css.sass.pluginOptions.includePaths = require( 'node-bourbon' ).includePaths;

var paths = {
    src   : elixir.config.assetsPath,
    dest  : elixir.config.publicPath,
    bower : 'vendor/bower/'
};

/*
 |--------------------------------------------------------------------------
 | Elixir Asset Management
 |--------------------------------------------------------------------------
 |
 | Elixir provides a clean, fluent API for defining some basic Gulp tasks
 | for your Laravel application. By default, we are compiling the Sass
 | file for our application, as well as publishing vendor resources.
 |
 */

elixir.extend('compress', function() {
    new elixir.Task('compress', function() {
        return gulp.src('./storage/framework/views/*')
            .pipe(htmlmin({
                collapseWhitespace:    true,
                removeAttributeQuotes: true,
                removeComments:        true,
                minifyJS:              true,
            }))
            .pipe(gulp.dest('./storage/framework/views/'));
    })
    .watch('./storage/framework/views/*');
});

elixir(function(mix) {
    mix.styles([
        'animate.min.css',
        'blueimp-gallery.css',
        'bonsai.css',
        'bootstrap.min.css',
        'bootstrap-image-gallery.css',
        'custom-style.css',
        'font-awesome.min.css',
        'jquery.tagsinput.css',
        'magnific-popup.css',
        'reponsive.css',
        'select2.min.css',
        'ticker.css',
        'uploadfile.css',
        'uploadify.css'
    ], 'public/assets/css')

    .styles([
        'videojs.ads.css',
        'videojs-preroll.css',
        'plugins/videojs_ads/videojs.watermark.css',
        'plugins/videojs_ads/videojs.vast.vpaid.min.css'
    ], 'public/assets/css/videojs_ads.css')

    // .jshint([
    //     paths.src + 'js/*.js',
    //     '!' + paths.src + 'js/plugins/*.js'
    // ])

    .scripts([
        'jquery.min.js',
        'bootstrap.min.js',
        'jquery.bootstrap.newsbox.js',
        'jquery.uploadfile.js',
        'blueimp-gallery.js',
        // 'bootstrap-image-gallery.js',
        'jquery.tagsinput.js',
        'select2.min.js',
        'mg_flipbook-1.0.0.js',
        'jquery.magnific-popup.min.js',
        // 'player.js',
        'adult.js',
        'videoscript.js',
        'jquery.tablesorter.min.js',
        'jquery.timeago.js',
        'ticker.js',
        'jquery.uploadify-3.1.min.js',
        'bonsai.min.js',
        'main-dev.js',
        'custom.js'
    ], 'public/assets/js/all.js')

    .scripts([
        'videojs.js',
        'videojs.ads.js',
        'plugins/videojs_ads/videojs.watermark.js',
        'plugins/videojs_ads/videojs_5.vast.vpaid.js',
        'plugins/videojs_ads/es5-shim.js',
        'plugins/videojs_ads/ie8fix.js'
    ], 'public/assets/js/videojs_ads.js')

    .compress();
});

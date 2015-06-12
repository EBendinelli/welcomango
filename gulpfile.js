// do not change the path since in the css file, we have path like /img/example.jpg
var
    assets_js  = 'web/js';
    assets_css = 'web/css';
    font_path  = 'web/fonts';
    img_path   = 'web/img';

var
    notifier     = require('node-notifier');
    gulp         = require('gulp'),
    rename       = require('gulp-rename'),
    uglify       = require('gulp-uglify'),
    minifyCSS    = require('gulp-minify-css')
    gulpUtil     = require('gulp-util'),
    gulpIf       = require('gulp-if'),
    gulpClean    = require('gulp-clean'),
    gulpNotify   = require('gulp-notify'),
    gulpJshint   = require('gulp-jshint'),
    gulpImagemin = require('gulp-imagemin'),
    concat       = require('gulp-concat');

// Flags
// useless for now
//    isDev    = gulpUtil.env.dev    ? true : false,
//    isNotify = gulpUtil.env.notify ? true : false;

// Default
gulp.task('default', function() {
    gulp.start('assets-js');
    gulp.start('assets-css');
    gulp.start('fonts');
    gulp.start('img');
});

// Assets
gulp.task('assets-js', function() {

    gulp.src([
        'components/pages-assets/plugins/pace/pace.min.js',
        'components/pages-assets/plugins/modernizr.custom.js',
        'components/pages-assets/plugins/jquery-ui/jquery-ui.min.js',
        'components/pages-assets/plugins/boostrapv3/js/bootstrap.min.js',
        'components/pages-assets/plugins/jquery/jquery-easy.js',
        'components/pages-assets/plugins/jquery-unveil/jquery.unveil.min.js',
        'components/pages-assets/plugins/jquery-bez/jquery.bez.min.js',
        'components/pages-assets/plugins/imagesloaded/imagesloaded.pkgd.min.js',
        'components/pages-assets/plugins/jquery-actual/jquery.actual.min.js',
        'components/pages-assets/plugins/jquery-ios-list/jquery.ioslist.min.js',
        'components/pages-assets/plugins/jquery-scrollbar/jquery.scrollbar.min.js',

        'components/pages-assets/plugins/jquery-block-ui/jqueryblockui.min.js',
        'components/pages-assets/plugins/jquery-slimscroll/jquery.slimscroll.min.js',
        'components/pages-assets/plugins/jquery-slider/jquery.sidr.min.js',
        'components/pages-assets/plugins/bootstrap-datepicker/js/bootstrap-datepicker.js',
        'components/pages-assets/plugins/bootstrap-timepicker/js/bootstrap-timepicker.js',
        'components/pages-assets/plugins/bootstrap-select2/select2.min.js',
        'components/pages-assets/plugins/bootstrap-tag/bootstrap-tagsinput.js',
        'components/pages-assets/plugins/bootstrap3-wysihtml5/bootstrap3-wysihtml5.all.min.js'
    ])
        .pipe(uglify())
        .pipe(concat('plugins.js'))
        .pipe(gulp.dest(assets_js))
    ;

    gulp.src([
        'components/pages-assets/js/pages.js'
    ])
        .pipe(uglify())
        .pipe(concat('pages.js'))
        .pipe(gulp.dest(assets_js))
    ;
});


gulp.task('assets-css', function() {

    gulp.src([
        'components/pages-assets/css/pages.min.css',
        'components/pages-assets/css/pages-icons.css'
    ])
        .pipe(minifyCSS())
        .pipe(concat('pages.css'))
        .pipe(gulp.dest(assets_css))
    ;

    gulp.src([
        'components/pages-assets/plugins/pace/pace-theme-flash.css',
        'components/pages-assets/plugins/jquery-slider/css/jquery.sidr.light.css',
        'components/pages-assets/plugins/bootstrap-select2/select2.css',
        'components/pages-assets/plugins/bootstrap-select2/select2-bootstrap.css',
        'components/pages-assets/plugins/switchery/css/switchery.min.css',
        'components/pages-assets/plugins/bootstrap-timepicker/css/bootstrap-timepicker.css',
        'components/pages-assets/plugins/bootstrap-datepicker/css/datepicker.css',
        'components/pages-assets/plugins/bootstrap-tag/bootstrap-tagsinput.css',
        'components/pages-assets/plugins/jquery-scrollbar/jquery.scrollbar.css',
        'components/pages-assets/plugins/bootstrap3-wysihtml5/bootstrap3-wysihtml5.min.css'
    ])
        .pipe(minifyCSS())
        .pipe(concat('plugins.css'))
        .pipe(gulp.dest(assets_css))
    ;

    gulp.src([
        'components/pages-assets/plugins/bootstrap-select2/*.{png,gif}'
    ])
        .pipe(gulp.dest(assets_css))
    ;

    gulp.src([
        'components/pages-assets/plugins/boostrapv3/css/bootstrap.min.css',
        'components/pages-assets/plugins/font-awesome/css/font-awesome.min.css'
    ])
        .pipe(concat('bootstrap.min.css'))
        .pipe(gulp.dest(assets_css))
    ;
});

gulp.task('fonts', function() {
    gulp.src([
        'components/pages-assets/fonts/**/*',
        'components/pages-assets/plugins/boostrapv3/fonts/*',
        'components/pages-assets/plugins/font-awesome/fonts/*'
    ])
        .pipe(gulp.dest(font_path))
    ;
});

gulp.task('img', function() {

    gulp.src('components/pages-assets/img/**/*')
        .pipe(gulp.dest(img_path))
    ;

    gulp.src('components/pages-assets/plugins/data-tables/images/*')
        .pipe(gulp.dest(img_path + '/data-tables/'))
    ;
});

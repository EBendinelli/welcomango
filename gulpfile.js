// do not change the path since in the css file, we have path like /img/example.jpg
var
    assets_js  = 'web/js'; // to rename assets_admin_js
    assets_css = 'web/css'; // to rename assets_front_js
    font_path  = 'web/fonts';
    img_path   = 'web/img';

    assets_front_js  = 'web/js';
    assets_front_css = 'web/css';
    font_front_path  = 'web/fonts';
    img_front_path   = 'web/img';

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

    //// ADMIN ////
    gulp.src([
        'components/pages-assets/plugins/pace/pace.min.js',
        'components/pages-assets/plugins/modernizr.custom.js',
        'components/pages-assets/plugins/jquery-ui/jquery-ui.min.js',
        'components/pages-assets/plugins/dropzone/dropzone.js',
        'components/pages-assets/plugins/boostrapv3/js/bootstrap.min.js',
        'components/pages-assets/plugins/jquery/jquery-easy.js',
        'components/pages-assets/plugins/jquery-unveil/jquery.unveil.min.js',
        'components/pages-assets/plugins/jquery-bez/jquery.bez.min.js',
        'components/pages-assets/plugins/imagesloaded/imagesloaded.pkgd.min.js',
        'components/pages-assets/plugins/jquery-actual/jquery.actual.min.js',
        'components/pages-assets/plugins/jquery-ios-list/jquery.ioslist.min.js',
        'components/pages-assets/plugins/jquery-scrollbar/jquery.scrollbar.min.js',
        'components/pages-assets/plugins/jquery-validation/js/jquery.validate.min.js',
        'components/pages-assets/plugins/jquery-validation/js/additional-methods.min.js',
        'components/pages-assets/plugins/jquery-validation/js/localization/*',

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


    //// FRONT ////
    gulp.src([
        'components/pages-assets/pages-front/plugins/jquery/jquery-1.11.1.min.js',
        'components/pages-assets/pages-front/plugins/animateNumber/jquery.animateNumber.js',
        'components/pages-assets/pages-front/plugins/swiper/js/swiper.jquery.min.js',
        /*'components/pages-assets/pages-front/plugins/bootstrap/js/bootstrap.min.js',
        'components/pages-assets/pages-front/plugins/bootstrap/js/npm.js',*/
        'components/pages-assets/pages-front/plugins/boostrap-form-wizard/js/jquery.bootstrap.wizard.min.js',
        'components/pages-assets/pages-front/plugins/bootstrap-select2/select2.min.js',
        'components/pages-assets/pages-front/plugins/Jquery-sticky-kit/jquery.sticky-kit.js',
        'components/pages-assets/pages-front/plugins/bootstrap-datepicker/js/bootstrap-datepicker.js',
        'components/pages-assets/pages-front/plugins/countdown/jquery.countdown.min.js',
        'components/pages-assets/pages-front/plugins/fitjs/fit.min.js',
        'components/pages-assets/pages-front/plugins/ftscroller/ftscroller.js',
        'components/pages-assets/pages-front/plugins/imagesloaded/imagesloaded.pkgd.min.js',
        'components/pages-assets/pages-front/plugins/imagesloaded/jquery-appear/jquery.appear.js',
        'components/pages-assets/pages-front/plugins/pace/pace.min.js',
        'components/pages-assets/pages-front/plugins/text-rotate/jquery.simple-text-rotator.min.js',
        'components/pages-assets/pages-front/plugins/velocity/velocity.min.js',
        'components/pages-assets/pages-front/plugins/velocity/velocity.ui.js',
        'components/pages-assets/pages-front/plugins/vide/jquery.vide.min.js',
        'components/pages-assets/pages-front/plugins/waypoints/jquery.waypoints.min.js',

        'components/pages-assets/pages-front/plugins/jquery/jquery-easy.js',
        'components/pages-assets/pages-front/plugins/jquery-appear/jquery.appear.js',
        'components/pages-assets/pages-front/plugins/jquery-ui/jquery-ui.min.js',
        'components/pages-assets/pages-front/plugins/jquery-fit-text/jquery.fittext.js',
        'components/pages-assets/pages-front/plugins/jquery-isotope/isotope.pkgd.min.js',
        'components/pages-assets/pages-front/plugins/jquery-isotope/masonry-horizontal.js',
        'components/pages-assets/pages-front/plugins/jquery-scrollbar/jquery.scrollbar.min.js',
        'components/pages-assets/pages-front/plugins/jquery-unveil/jquery.unveil.min.js'
    ])
        .pipe(uglify())
        .pipe(concat('plugins-front.js'))
        .pipe(gulp.dest(assets_front_js))
    ;


    gulp.src([
        'components/pages-assets/pages-front/js/pages.frontend.js',
        'components/pages-assets/pages-front/js/pages.image.loader.js',
        'components/pages-assets/pages-front/js/pages.init.js',
        'components/pages-assets/pages-front/js/*',
        'components/pages-assets/pages-front/js/ui/*',
        'components/pages-assets/pages-front/assets/js/custom.js',
        'components/pages-assets/pages-front/assets/js/gallery.js'
    ])
        .pipe(uglify())
        .pipe(concat('pages-front.js'))
        .pipe(gulp.dest(assets_front_js))
    ;

    gulp.src([
        'components/webcomponentsjs/webcomponents.min.js'
    ])
        .pipe(uglify())
        .pipe(concat('webcomponentsjs.min.js'))
        .pipe(gulp.dest(assets_js))
    ;

    gulp.src([
        'components/flag-icon/flag-icon.html'
    ])
        .pipe(gulp.dest(assets_js))
    ;

    gulp.src([
        'components/polymer/polymer.html'
    ])
        .pipe(gulp.dest(assets_js))
    ;

    gulp.src([
        'components/polymer/polymer.js'
    ])
        .pipe(gulp.dest(assets_js))
    ;

    gulp.src([
        'components/uploadify/jquery.uploadify.min.js'
    ])
        .pipe(gulp.dest(assets_js))
    ;

    gulp.src([
        'components/Jcrop/js/Jcrop.min.js'
    ])
        .pipe(gulp.dest(assets_js))
    ;
});


gulp.task('assets-css', function() {
    //// ADMIN ////
    gulp.src([
        'components/pages-assets/css/pages.css',
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
        'components/pages-assets/plugins/bootstrap3-wysihtml5/bootstrap3-wysihtml5.min.css',
        'components/pages-assets/plugins/dropzone/css/dropzone.css',
        'components/pages-assets/plugins/dropzone/css/base.css'
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

    //// FRONT ////
    gulp.src([
        'components/pages-assets/pages-front/css/pages.css',
        'components/pages-assets/pages-front/css/pages-icons.min.css'
    ])
        .pipe(minifyCSS())
        .pipe(concat('pages-front.css'))
        .pipe(gulp.dest(assets_front_css))
    ;

    gulp.src([
        'components/pages-assets/pages-front/plugins/bootstrap/css/bootstrap-theme.min.css',
        'components/pages-assets/pages-front/plugins/bootstrap/css/bootstrap.min.css'
    ])
        .pipe(minifyCSS())
        .pipe(concat('bootstrap.css'))
        .pipe(gulp.dest(assets_front_css))
    ;

    gulp.src([
        'components/pages-assets/pages-front/plugins/font-awesome/css/font-awesome.css',
        'components/pages-assets/pages-front/plugins/jquery-scrollbar/jquery.scrollbar.css',
        'components/pages-assets/pages-front/plugins/pace/pace-theme-flash.css',
        'components/pages-assets/pages-front/plugins/swiper/css/swiper.css',
        'components/pages-assets/pages-front/plugins/text-rotate/simpletextrotator.css',
        'components/pages-assets/pages-front/plugins/bootstrap-select2/select2.css',
        'components/pages-assets/pages-front/plugins/bootstrap-select2/select2-bootstrap.css',
        'components/pages-assets/pages-front/plugins/jquery-datatable/media/css/*'
    ])
        .pipe(minifyCSS())
        .pipe(concat('plugins-front.css'))
        .pipe(gulp.dest(assets_front_css))
    ;

    gulp.src([
        'components/uploadify/uploadify.css',
    ])
        .pipe(minifyCSS())
        .pipe(concat('uploadify.min.css'))
        .pipe(gulp.dest(assets_css))
    ;

    gulp.src([
        'components/Jcrop/css/Jcrop.min.css',
    ])
        .pipe(gulp.dest(assets_css))
    ;

});

gulp.task('fonts', function() {

    //// ADMIN ////
    gulp.src([
        'components/pages-assets/fonts/**/*',
        'components/pages-assets/plugins/boostrapv3/fonts/*',
        'components/pages-assets/plugins/font-awesome/fonts/*'
    ])
        .pipe(gulp.dest(font_path))
    ;

    //// FRONT ////
    gulp.src([
        'components/pages-assets/pages-front/fonts/**/*',
        'components/pages-assets/pages-front/plugins/boostrapv3/fonts/*',
        'components/pages-assets/pages-front/plugins/font-awesome/fonts/*'
    ])
        .pipe(gulp.dest(font_front_path))
    ;

    gulp.src([
        'components/pages-assets/pages-front/fonts/**/*',
        'components/pages-assets/pages-front/plugins/boostrapv3/fonts/*',
        'components/pages-assets/pages-front/plugins/font-awesome/fonts/*'
    ])
        .pipe(gulp.dest(font_front_path))
    ;

});

gulp.task('img', function() {

    //// ADMIN ////
    gulp.src('components/pages-assets/img/**/*')
        .pipe(gulp.dest(img_path))
    ;

    gulp.src('components/pages-assets/plugins/data-tables/images/*')
        .pipe(gulp.dest(img_path + '/data-tables/'))
    ;

    gulp.src('components/flag-icon/svg/country-4x3/*')
        .pipe(gulp.dest(img_path + '/countries/'))
    ;

    //// FRONT////
    gulp.src('components/pages-assets/pages-front/images/**/*')
        .pipe(gulp.dest(img_front_path))
    ;

    gulp.src('components/pages-assets/pages-front/plugins/jquery-datatable/media/images/*')
        .pipe(gulp.dest(img_front_path + '/data-tables/'))
    ;
});

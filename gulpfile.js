var gulp = require('gulp');
var postcss = require('gulp-postcss');
var sass = require('gulp-sass');
var autoprefixer = require('gulp-autoprefixer');
var cleanCSS = require('gulp-clean-css');
var sourcemaps = require('gulp-sourcemaps');
var uglify = require('gulp-uglify');
var imagemin = require('gulp-imagemin');
var newer = require('gulp-newer');
var merge = require('merge-stream');
var browserSync = require('browser-sync').create();

// varibles for the paths
const
    dir = {
        src :   'src/',
        dist:   'dist/'
    };

// other variables
var projectURL  =   'project.test';

// SASS to CSS, minified, cleaned, sourcemapped, autoprefixed

gulp.task('sass', function() {

   return gulp.src(dir.src+'assets/scss/**/*.scss')
            .pipe(sass({
                outputStyle     : 'nested',
                imagePath       : dir.dist+'assets/images',
                precision       : 3
            }).on('error', sass.logError))
            .pipe(sourcemaps.init())
            .pipe( autoprefixer())
            .pipe(cleanCSS({compatibility: 'ie9'}))
            .pipe(sourcemaps.write())
            .pipe(gulp.dest(dir.dist+'assets/css'))
            .pipe(browserSync.stream({match: '**/*.css'}));
});

// JS minify
gulp.task('js', function() {
    return gulp.src(dir.src+'assets/js/**/*.js')
            .pipe(uglify())
            .pipe(gulp.dest(dir.dist+'assets/js'));
});

// Compress Images
gulp.task('images', function() {
    return gulp.src(dir.src+'assets/images/**/*.*')
            .pipe(newer(dir.dist+'assets/images/**/*'))
            .pipe(imagemin())
            .pipe(gulp.dest(dir.dist+'assets/images'));
});

// Fonts
gulp.task('fonts', function() {
    return gulp.src(dir.src+'assets/fonts/**/*.*')
            .pipe(newer(dir.dist+'assets/fonts/'))
            .pipe(gulp.dest(dir.dist+'assets/fonts'));
});

// Copy files
gulp.task('files', function() {
    var themeroot = gulp.src(dir.src+'*.*')
                    .pipe(gulp.dest(dir.dist));

    var themelang = gulp.src(dir.src+'languages/**/*.*')
                    .pipe(gulp.dest(dir.dist+'languages/'));

    var phpfiles = gulp.src(dir.src+'**/*.php')
                    .pipe(gulp.dest(dir.dist));

    var framework = gulp.src(dir.src+'framework/**/*.*')
                    .pipe(gulp.dest(dir.dist+'framework/'));

    return merge(themeroot, themelang, phpfiles, framework);
});

// Browsersync
gulp.task( 'browser-sync', function() {
    browserSync.init( {

      proxy: projectURL,
      // `true` Automatically open the browser with BrowserSync live server.
      // `false` Stop the browser from automatically opening.
      open: true,

      // Use a specific port (instead of the one auto-detected by Browsersync).
      //port: 7000,

    } );
  });

  //Watch tasks
  gulp.task('default', gulp.parallel('sass', 'js', 'images', 'fonts', 'files', 'browser-sync', function() {
    gulp.watch( dir.src+'**/*.php', gulp.series('files')).on('change', browserSync.reload); // Reload on PHP file changes.
    gulp.watch( dir.src+'**/*.scss', gulp.series( 'sass')); // Reload on SCSS file changes.
    gulp.watch( dir.src+'**/*.js', gulp.series( 'js' ) ).on('change', browserSync.reload); // Reload on vendorsJs file changes.
    gulp.watch( dir.src+'/framework/**/*.*', gulp.series('files')).on('change', browserSync.reload);
  }));

//Build task
gulp.task('build', gulp.parallel('sass', 'js', 'images', 'fonts', 'files') );

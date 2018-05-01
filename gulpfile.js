// Gulp.js configuration
'use strict';

const

  // source and build folders
  dir = {
    src         : 'src/',
    build       : 'dist/',
  },

  // Gulp and plugins
  gulp          = require('gulp'),
  gutil         = require('gulp-util'),
  newer         = require('gulp-newer'),
  imagemin      = require('gulp-imagemin'),
  sass          = require('gulp-sass'),
  postcss       = require('gulp-postcss'),
  stripdebug    = require('gulp-strip-debug'),
  uglify        = require('gulp-uglify')
;

// Browser-sync
var browsersync = false;

// PHP settings
const php = {
  src           : dir.src + '**/*.php',
  build         : dir.build
};

// copy PHP files
gulp.task('php', () => {
  return gulp.src(php.src)
    .pipe(newer(php.build))
    .pipe(gulp.dest(php.build));
});

// Image settings
const images = {
  src         : dir.src + 'images/**/*',
  build       : dir.build + 'assets/images/'
};

// Image processing
gulp.task('images', () => {
  return gulp.src(images.src)
    .pipe(newer(images.build))
    .pipe(imagemin())
    .pipe(gulp.dest(images.build));
});

// Fonts
gulp.task('fonts', () => {
	return gulp.src(dir.src + 'fonts/**/*')
	.pipe(newer(dir.build + 'assets/fonts/'))
	.pipe(gulp.dest(dir.build + 'assets/fonts/'));
});

// CSS settings
var css = {
  src         : dir.src + 'scss/**/*.scss',
  watch       : dir.src + 'scss/**/*',
  build       : dir.build + 'assets/css',
  sassOpts: {
    outputStyle     : 'nested',
    imagePath       : images.build,
    precision       : 3,
    errLogToConsole : true
  }
};

// CSS processing
gulp.task('css', ['images', 'fonts'], () => {
  return gulp.src(css.src)
    .pipe(sass(css.sassOpts))
    .pipe(gulp.dest(css.build))
    .pipe(browsersync ? browsersync.reload({ stream: true }) : gutil.noop());
});


// JavaScript settings
const js = {
  src         : dir.src + 'js/**/*',
  build       : dir.build + 'assets/js/'
};

// JavaScript processing
gulp.task('js', () => {
  return gulp.src(js.src)
    .pipe(stripdebug())
    .pipe(uglify())
    .pipe(gulp.dest(js.build))
    .pipe(browsersync ? browsersync.reload({ stream: true }) : gutil.noop());
});

// Copy the ico, screenshot, mandatory WP css theme file
gulp.task('basic', () => {
	return gulp.src(dir.src + '/*.+(png|jpg|css|ico)')
	.pipe(newer(dir.build))
	.pipe(gulp.dest(dir.build));
});

// Languages settings
const lang = {
  src           : dir.src + '/languages/**/*.*',
  build         : dir.build + '/languages'
};

// Languages
gulp.task('languages', () => {
  return gulp.src(lang.src)
  .pipe(newer(lang.build))
  .pipe(gulp.dest(lang.build));
});

// run all tasks
gulp.task('build', ['php', 'css', 'js', 'basic', 'languages']);


// Browsersync options
const syncOpts = {
  proxy       : 'frameworkwp.test',
  files       : dir.build + '**/*',
  open        : false,
  notify      : false,
  ghostMode   : false,
  ui: {
    port: 8001
  }
};

// browser-sync
gulp.task('browser_sync', () => {
  if (browsersync === false) {
    browsersync = require('browser-sync').create();
    browsersync.init(syncOpts);
  }
});

// watch for file changes
gulp.task('watch', ['browser_sync'], () => {

	// page changes
  	gulp.watch(php.src, ['php'], browsersync ? browsersync.reload : {});

	// image changes
	gulp.watch(images.src, ['images']);

	// CSS changes
	gulp.watch(css.watch, ['css']);

  // JavaScript main changes
  gulp.watch(js.src, ['js']);
  
  // Language changes 
  gulp.watch(lang.src, ['languages']);
  
  // Basic 
	gulp.watch(dir.src, ['basic']);

});

// default task
gulp.task('default', ['build', 'watch']);

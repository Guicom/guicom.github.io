let gulp = require('gulp'),
  sass = require('gulp-sass'),
  sourcemaps = require('gulp-sourcemaps'),
  cleanCss = require('gulp-clean-css'),
  rename = require('gulp-rename'),
  postcss = require('gulp-postcss'),
  autoprefixer = require('autoprefixer'),
  browserSync = require('browser-sync').create();

const paths = {
  scss: {
    src: 'themes/custom/socomec/assets/scss/style.scss',
    pardot: 'themes/custom/socomec/assets/scss/pardot.scss',
    pardotdest: 'themes/custom/socomec/assets/pardot-assets',
    ckeditor: 'themes/custom/socomec/assets/scss/ckeditor.scss',
    dest: 'themes/custom/socomec/assets/css',
    watch: 'themes/custom/socomec/assets/scss/**/*.scss',
    bootstrap: 'node_modules/bootstrap/scss/bootstrap.scss'
  },
  js: {
    bootstrap: 'node_modules/bootstrap/dist/js/bootstrap.min.js',
    jquery: 'node_modules/jquery/dist/jquery.min.js',
    popper: 'node_modules/popper.js/dist/umd/popper.min.js',
    dest: 'themes/custom/socomec/assets/js'
  }
};

// Compile sass into CSS & auto-inject into browsers
function styles () {
  return gulp.src([paths.scss.bootstrap, paths.scss.src,paths.scss.ckeditor])
    .pipe(sourcemaps.init())
    .pipe(sass().on('error', sass.logError))
    .pipe(postcss([autoprefixer({
      browsers: [
        'Chrome >= 35',
        'Firefox >= 38',
        'Edge >= 12',
        'Explorer >= 10',
        'iOS >= 8',
        'Safari >= 8',
        'Android 2.3',
        'Android >= 4',
        'Opera >= 12']
    })]))
    .pipe(sourcemaps.write())
    .pipe(gulp.dest(paths.scss.dest))
}

function pardotcss () {
  return gulp.src([paths.scss.pardot])
    .pipe(sourcemaps.init())
    .pipe(sass().on('error', sass.logError))
    .pipe(postcss([autoprefixer({
      browsers: [
        'Chrome >= 35',
        'Firefox >= 38',
        'Edge >= 12',
        'Explorer >= 10',
        'iOS >= 8',
        'Safari >= 8',
        'Android 2.3',
        'Android >= 4',
        'Opera >= 12']
    })]))
    .pipe(sourcemaps.write())
    .pipe(gulp.dest(paths.scss.pardotdest))
}

// Move the javascript files into our js folder
function js () {
  return gulp.src([paths.js.bootstrap, paths.js.jquery, paths.js.popper])
    .pipe(gulp.dest(paths.js.dest))
}

// Static Server + watching scss/html files
function serve () {
  gulp.watch([paths.scss.watch, paths.scss.bootstrap], {interval: 1000, usePolling: true}, gulp.series(styles));
}

const build = gulp.series(styles, gulp.parallel(js, serve));

gulp.task('pardot', gulp.series(pardotcss));

exports.css = styles;
exports.styles = styles;
exports.js = js;
exports.serve = serve;

exports.default = build;

// Include gulp
const gulp = require('gulp');
const browserify = require('gulp-browserify');

// Include Our Plugins
const eslint = require('gulp-eslint');
const sass = require('gulp-sass');
const concat = require('gulp-concat');
const uglify = require('gulp-uglify');
const rename = require('gulp-rename');
const sourcemaps = require('gulp-sourcemaps');
const cleanCSS = require('gulp-clean-css');
const del = require('del');
const runSequence = require('run-sequence');

var environment = 'development';

function isLiveSever() {
    return environment === 'production';
}

// Lint Task
gulp.task('lint', function() {
    return gulp.src(['assets/scripts/*.js'])
        .pipe(eslint())
        .pipe(eslint.format())
        .pipe(eslint.failAfterError());
});

gulp.task('scripts:vendor', function() {
    return gulp.src([
            'assets/scripts/vendor/jquery.js',
            'assets/scripts/vendor/*.js'
        ])
        .pipe(isLiveSever() ? sourcemaps.init() : null)
        .pipe(concat('vendor.js'))
        .pipe(gulp.dest('dist/js'))
        .pipe(rename('vendor.min.js'))
        .pipe(uglify())
        .pipe(isLiveSever() ? sourcemaps.write() : null)
        .pipe(gulp.dest('dist/js'))
});

gulp.task('scripts:app', function() {
    return gulp.src(['assets/scripts/index.js'])
        .pipe(isLiveSever() ? sourcemaps.init() : null)
        .pipe(browserify({
            transform: ['babelify'],
        }))
        .pipe(concat('app.js'))
        .pipe(gulp.dest('dist/js'))
        .pipe(rename('app.min.js'))
        .pipe(uglify())
        .pipe(sourcemaps.write('.'))
        .pipe(gulp.dest('dist/js'))
        ;
});

// Compile Our Sass
gulp.task('stylesheets:vendor', function() {
    return gulp.src('assets/scss/vendor/*.scss')
        .pipe(isLiveSever() ? sourcemaps.init() : null)
        .pipe(concat('vendor.css'))
        .pipe(sass())
        .pipe(cleanCSS({compatibility: 'edge'}))
        .pipe(isLiveSever() ? sourcemaps.write() : null)
        .pipe(gulp.dest('dist/css'));
});

gulp.task('stylesheets:app', function() {
    return gulp.src('assets/scss/*.scss')
        .pipe(isLiveSever() ? sourcemaps.init() : null)
        .pipe(concat('style.css'))
        .pipe(sass())
        .pipe(cleanCSS({compatibility: 'edge'}))
        .pipe(isLiveSever() ? sourcemaps.write() : null)
        .pipe(gulp.dest('dist/css'));
});

gulp.task('clean', function() {
    return del.sync(['dist/**']);
});


// Watch Files For Changes
gulp.task('watch', function() {
    gulp.watch('assets/scripts/*.js', ['lint', 'scripts:vendor', 'scripts:app']);
    gulp.watch('assets/scss/*.scss', ['stylesheets:vendor','stylesheets:app']);
});

gulp.task('build', function(callback) {
    runSequence(
        'lint',
        'clean',
        ['stylesheets:vendor', 'stylesheets:app'],
        'scripts:vendor',
        'scripts:app',
        'watch',
        callback
    );
});

gulp.task('launch', function(callback) {
    environment = 'production';
    runSequence('build');
});

// Default Task
gulp.task('default', ['build']);
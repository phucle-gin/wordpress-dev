var gulp = require('gulp');
var plumber = require('gulp-plumber');
var sass = require('gulp-sass')(require('sass'));
var rename = require('gulp-rename');
const cleanCSS = require('gulp-clean-css');

var uglify = require('gulp-uglify');
var concat = require('gulp-concat');
var log = require('fancy-log');
var sourcemaps = require('gulp-sourcemaps');
// var browserSync = require('browser-sync').create();

gulp.task('styles', async function() {
    gulp.src('sass/*.scss')
    .pipe(sourcemaps.init())
    .pipe(plumber(function (error) {
            console.log(error);
            this.emit('end');
        }))
    .pipe(sass())
    .pipe(cleanCSS())
    .pipe(rename('main.min.css'))
    .pipe(sourcemaps.write('/'))
    .pipe(gulp.dest('dist/css'));
});

gulp.task('scripts', function() {
  return gulp.src([
    'js/*.js',
    ])
    .pipe(concat('main.js'))
    .pipe(rename({suffix: '.min'}))
    .pipe(uglify().on('error', function(error){
        log.error('[Error]', error.toString());
        this.emit('end');
    }))
    .pipe(gulp.dest('dist/js'))
});

gulp.task('plugins', function() {
  return gulp.src([
    'js/plugins/*.js',
    ])
    .pipe(concat('plugins.js'))
    .pipe(rename({suffix: '.min'}))
    .pipe(uglify().on('error', function(error){
        log.error('[Error]', error.toString());
        this.emit('end');
    }))
    .pipe(gulp.dest('dist/js'))
});

gulp.task('default',function() {
    // browserSync.init({
    //     proxy: 'http://wordpress.local'
    // });
    gulp.watch('sass/**/*.scss',gulp.series('styles'));
    gulp.watch('js/**/*.js',gulp.series('scripts'));
    // gulp.watch('js/plugins/*.js',gulp.series('plugins'));
    gulp.watch('**/*.php');
});

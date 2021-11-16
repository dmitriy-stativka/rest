"use strict"

// Load plugins
const gulp = require('gulp');
const sass = require('gulp-sass');
const browsersync = require('browser-sync').create();
const notify = require('gulp-notify');
const plumber = require('gulp-plumber');
const autoprefixer = require('gulp-autoprefixer');

require('dotenv').config();
const proxy_url = process.env.BROWSERSYNC_PROXY_URL || 'restorationwp:8888';

// BrowserSync
function browserSync(done) {
    browsersync.init({
        injectChanges: true,
        proxy: proxy_url,
        host: proxy_url,
    });
    done();
}

// BrowserSync Reload
function browserSyncReload(done) {
    browsersync.reload();
    done();
}

// CSS task
function css() {
    return gulp.src('src/sass/style.scss')
        .pipe(plumber({
            errorHandler: notify.onError(function (err) {
                return{
                    title: "Error",
                    message: err.message
                }
            })
        }))
        .pipe(sass({outputStyle: 'compressed'}))
        .pipe(autoprefixer({
            grid: 'autoplace',
            // browsers: ['last 8 versions'], // .browserlistrc
            cascade: false
        }))
        .pipe(gulp.dest('build/'))
        .pipe(browsersync.stream());
}

// Watch files
function watchFiles() {
    gulp.watch("src/sass/**/*", css);
    gulp.watch('*.*', browserSyncReload);
}

const watch = gulp.parallel(watchFiles, browserSync);

exports.css = css;
exports.default = watch;
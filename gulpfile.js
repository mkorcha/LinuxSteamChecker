var gulp    = require('gulp'),
    sass    = require('gulp-sass'),
    connect = require('gulp-connect-php'),
    minicss = require('gulp-minify-css'),
    uglify  = require('gulp-uglify'),
    concat  = require('gulp-concat')
;

var paths = {
    root   : './public',
    pub_css: './public/css',
    pub_js : './public/js',
    sass   : './resources/sass/**/*.scss',
    js     : './resources/js/**/*.js'
};

gulp.task('debug', function() {
    connect.server({
        base: paths.root
    });
});

gulp.task('sass', function() {
    gulp.src(paths.sass)
        .pipe(sass())
        .pipe(minicss())
        .pipe(gulp.dest(paths.pub_css));
});

gulp.task('js', function() {
    gulp.src(paths.js)
        .pipe(concat('main.js'))
        //.pipe(uglify())
        .pipe(gulp.dest(paths.pub_js));
});

gulp.task('watch', function() {
    gulp.watch(paths.sass, ['sass']);
    gulp.watch(paths.js,   ['js']);
});

gulp.task('default', ['sass', 'js', 'debug', 'watch']);

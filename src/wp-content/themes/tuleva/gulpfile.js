var gulp = require('gulp'),
    sourcemaps = require('gulp-sourcemaps'),
    gulpif = require('gulp-if'),
    sass = require('gulp-sass'),
    cleanCSS = require('gulp-clean-css'),
    autoprefixer = require('gulp-autoprefixer'),
    watch = require('gulp-watch'),
    util = require('gulp-util'),
    exit = require('gulp-exit'),
    cssDir = 'css',
    sassSrc = 'scss/*.scss',
    error = function(err) {
        if (err) {
            util.log(err.toString());
        }

        if (this.emit) {
            this.emit('end');
        }
    },
    logFinishedTask = function(name) {
        return function() {
            util.log(name + ' task finished.');
        };
    },
    buildCss = function(isCompressed) {
        return gulp.src(sassSrc)
            .pipe(gulpif(!isCompressed, sourcemaps.init()))
            .pipe(sass({ outputStyle: isCompressed ? 'compressed' : 'normal' })
                            .on('end', logFinishedTask('SASS'))
                            .on('error', error))
            .pipe(gulpif(isCompressed, cleanCSS({level: 2})
                            .on('end', logFinishedTask('CleanCSS'))
                            .on('error', error)))
            .pipe(autoprefixer({
                    browsers: ['> 1%', 'last 2 versions', 'safari >= 7'],
                    cascade: false
                })
                .on('end', logFinishedTask('Autoprefixer'))
                .on('error', error))
            .pipe(gulpif(!isCompressed, sourcemaps.write('.')
                            .on('end', logFinishedTask('Sourcemaps'))
                            .on('error', error)))
            .pipe(gulp.dest(cssDir))
            .pipe(gulpif(isCompressed, exit()));
    };

gulp.task('production', function() {
  return buildCss(true);
});

gulp.task('default', function() {
    return watch(sassSrc, function() {
        return buildCss(false);
    })
    .on('change', function(path) {
        util.log('Changed: ' + path);
    })
    .on('error', error);
});

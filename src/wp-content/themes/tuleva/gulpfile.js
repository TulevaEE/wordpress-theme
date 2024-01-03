var gulp = require('gulp'),
    sourcemaps = require('gulp-sourcemaps'),
    gulpif = require('gulp-if'),
    sass = require('gulp-sass')(require('sass')),
    cleanCSS = require('gulp-clean-css'),
    autoprefixer = require('gulp-autoprefixer'),
    watch = require('gulp-watch'),
    log = require('fancy-log'),
    exit = require('gulp-exit'),
    cssDir = 'css',
    sassSrc = 'scss/**/*.scss',
    error = function(err) {
        if (err) {
            log(err.toString());
        }

        if (this.emit) {
            this.emit('end');
        }
    },
    logFinishedTask = function(name) {
        return function() {
            log(name + ' task finished.');
        };
    },
    buildCss = function(isCompressed) {
        return gulp.src(sassSrc)
            .pipe(gulpif(!isCompressed, sourcemaps.init()))
            .pipe(sass({ outputStyle: isCompressed ? 'compressed' : 'expanded' })
                            .on('end', logFinishedTask('SASS'))
                            .on('error', error))
            .pipe(gulpif(isCompressed, cleanCSS({level: 2})
                            .on('end', logFinishedTask('CleanCSS'))
                            .on('error', error)))
            .pipe(autoprefixer({
                    overrideBrowserslist: ['> 1%', 'last 2 versions', 'safari >= 7'],
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
        log('Changed: ' + path);
    })
    .on('error', error);
});

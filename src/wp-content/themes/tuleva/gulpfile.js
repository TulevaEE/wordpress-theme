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
    sassSrc = 'sass/*.scss',
    error = function(err) {
        if (err) {
            util.log(err.toString());
        }

        if (this.emit) {
            this.emit('end');
        }
    },
    buildCss = function(isCompressed) {
        return gulp.src(sassSrc)
            .pipe(gulpif(!isCompressed, watch(sassSrc)))
            .pipe(gulpif(!isCompressed, sourcemaps.init()))
            .pipe(sass({ outputStyle: isCompressed ? 'compressed' : 'normal' })
                            .on('error', error))
            .pipe(gulpif(isCompressed, cleanCSS({level: 2})))
            .pipe(autoprefixer({
                browsers: ['> 1%', 'last 2 versions', 'safari >= 7'],
                cascade: false
            }))
            .pipe(gulpif(!isCompressed, sourcemaps.write('.')))
            .pipe(gulp.dest(cssDir))
            .pipe(exit());
    }

gulp.task('default', function() {
  return buildCss(false);
});

gulp.task('pre-commit', function() {
  return buildCss(true);
});

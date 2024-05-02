const gulp = require("gulp");
const sourcemaps = require("gulp-sourcemaps");
const gulpif = require("gulp-if");
const sass = require("gulp-sass")(require("sass"));
const cleanCSS = require("gulp-clean-css");
const autoprefixer = require("gulp-autoprefixer");
const watch = require("gulp-watch");
const log = require("fancy-log");
const exit = require("gulp-exit");
const cssDir = "css";
const sassSrc = "scss/**/*.scss";

const onError = function (err) {
    if (err) {
        log(err.toString());
    }

    if (this.emit) {
        this.emit("end");
    }
};

const logFinishedTask = function (name) {
    return function () {
        log(name + " task finished.");
    };
};

const buildCss = function (isCompressed) {
    return gulp
        .src(sassSrc)
        .pipe(gulpif(!isCompressed, sourcemaps.init()))
        .pipe(
            sass({ outputStyle: isCompressed ? "compressed" : "expanded" })
                .on("end", logFinishedTask("SASS"))
                .on("error", onError)
        )
        .pipe(
            gulpif(
                isCompressed,
                cleanCSS({ level: 2 })
                    .on("end", logFinishedTask("CleanCSS"))
                    .on("error", onError)
            )
        )
        .pipe(
            autoprefixer({
                overrideBrowserslist: [
                    "> 1%",
                    "last 2 versions",
                    "safari >= 7",
                ],
                cascade: false,
            })
                .on("end", logFinishedTask("Autoprefixer"))
                .on("error", onError)
        )
        .pipe(
            gulpif(
                !isCompressed,
                sourcemaps
                    .write(".")
                    .on("end", logFinishedTask("Sourcemaps"))
                    .on("error", onError)
            )
        )
        .pipe(gulp.dest(cssDir))
        .pipe(gulpif(isCompressed, exit()));
};

gulp.task("production", function () {
    return buildCss(true);
});

gulp.task("default", function () {
    return watch(sassSrc, function () {
        return buildCss(false);
    })
        .on("change", function (path) {
            log("Changed: " + path);
        })
        .on("error", onError);
});

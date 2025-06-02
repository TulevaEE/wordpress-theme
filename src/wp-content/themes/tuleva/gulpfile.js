const gulp = require("gulp");
const sourcemaps = require("gulp-sourcemaps");
const gulpif = require("gulp-if");
const sass = require("gulp-sass")(require("sass"));
const cleanCSS = require("gulp-clean-css");
const autoprefixer = require("gulp-autoprefixer");
const watch = require("gulp-watch");
const log = require("fancy-log");
const exit = require("gulp-exit");
const fs = require("node:fs/promises");
const enqueueVersionFile = "helpers/enqueue_versions.php";
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

const updateEnqueueVersion = async function (name) {
    const data = await fs.readFile(enqueueVersionFile, { encoding: "utf8" });

    const jsEnqueueRegex = /\$JS_ENQUEUE_VERSION.+\n/;
    const cssEnqueueRegex = /\$CSS_ENQUEUE_VERSION.+\n/;

    const getRandomPostfix = () =>
        Math.floor(Math.random() * Math.pow(2, 48)).toString(16);

    const getDateString = () => new Date().toISOString().split("T")[0];

    const newEnqueueFileContents = data
        .replace(
            jsEnqueueRegex,
            `$JS_ENQUEUE_VERSION="${getDateString()}-${getRandomPostfix()}";\n`
        )
        .replace(
            cssEnqueueRegex,
            `$CSS_ENQUEUE_VERSION="${getDateString()}-${getRandomPostfix()}";\n`
        );

    await fs.writeFile(enqueueVersionFile, newEnqueueFileContents, {
        encoding: "utf8",
    });
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
            updateEnqueueVersion();
        })
        .on("error", onError);
});

const gulp = require('gulp');
const sass = require('gulp-sass')(require('sass'));
const autoprefixer = require('gulp-autoprefixer');

// Define a task to compile SCSS to CSS
gulp.task('sass', function () {
    return gulp.src('../scss/custom.scss') // Path to your SCSS files
        .pipe(sass().on('error', sass.logError))
        .pipe(autoprefixer())
        .pipe(gulp.dest('../css')); // Output directory for CSS
});

// Define a watch task to automatically compile SCSS on changes
gulp.task('watch', function () {
    gulp.watch('../scss/custom.scss', gulp.series('sass'));
});

// Define the default task that runs when you execute 'gulp' in the terminal
gulp.task('default', gulp.series('sass', 'watch'));
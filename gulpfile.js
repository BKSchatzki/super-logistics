import gulp from 'gulp';
import zip from 'gulp-zip';
import { exec } from 'child_process';

// Step 1: Build the Vue app
function buildVue(done) {
    exec('npm run build', (err, stdout, stderr) => {
        if (err) return done(err);
        console.log(stdout);
        console.error(stderr);
        done();
    });
}

// Step 2: Zip the plugin files (including the dist folder from the Vue build)
function zipPlugin() {
    return gulp.src([
        'super-logistics.php',
        'src/**/*',
        'vendor/**/*',
        'view/dist/**/*'
    ], { base: '.' })
        .pipe(zip('super-logistics.zip', { compress: false })) // Disable compression
        .pipe(gulp.dest('./build'));
}

// Use named exports
export const build = gulp.series(buildVue, zipPlugin);

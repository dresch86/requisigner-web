const glob = require('glob');
const path = require('path');
const mix = require('laravel-mix');

/*
 |--------------------------------------------------------------------------
 | Mix Asset Management
 |--------------------------------------------------------------------------
 |
 | Mix provides a clean, fluent API for defining some Webpack build steps
 | for your Laravel applications. By default, we are compiling the CSS
 | file for the application as well as bundling up all the JS files.
 |
 */

for (let file of glob.sync('resources/js/**/*.js')) {
    let aDirs = file.split('/');
    aDirs.shift();
    aDirs.unshift('public');
    aDirs.pop();

    mix.js(file, aDirs.join('/'))
    .options({
        terser: {
            terserOptions: {
                keep_classnames: true,
                keep_fnames: true
            }
        }
    })
    .version();
}

for (let file of glob.sync('resources/css/*.css')) {
    mix.postCss(file, 'public/css', [])
    .options({processCssUrls: false})
    .version();
}
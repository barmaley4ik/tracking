/* eslint-disable indent */
const cssImport = require('postcss-import')
const cssNesting = require('postcss-nesting')
const mix = require('laravel-mix')
const path = require('path')
const purgecss = require('@fullhuman/postcss-purgecss')
const tailwindcss = require('tailwindcss')


/*
 |--------------------------------------------------------------------------
 | Mix Asset Management
 |--------------------------------------------------------------------------
 |
 | Mix provides a clean, fluent API for defining some Webpack build steps
 | for your Laravel application. By default, we are compiling the Sass
 | file for the application as well as bundling up all the JS files.
 |
 */

mix.copyDirectory('node_modules/vue2-dropzone/dist', 'public/node_modules/vue2-dropzone/dist')

mix.copyDirectory('resources/images', 'storage/app/public/images')


mix.css('resources/css/backend.css', 'public/css/backend/app.css')

mix.js('resources/js/backend.js', 'public/js/backend/app.js')

.postCss('resources/css/backend.css', 'public/css/backend/app.css')
    .options({
        postCss: [
            cssImport(),
            // eslint-disable-next-line indent
            cssNesting(),
            tailwindcss('tailwind.config.js'),
            ...mix.inProduction() ? [
                purgecss({
                    content: ['./resources/views/**/*.blade.php', './resources/js/**/*.vue'],
                    defaultExtractor: content => content.match(/[\w-/:.]+(?<!:)/g) || [],
                    whitelistPatternsChildren: [/nprogress/],
                }),
            ] : [],
        ],
    })
    .webpackConfig({
        output: { chunkFilename: 'js/backend/[name].js?id=[chunkhash]' },
        resolve: {
            alias: {
                vue$: 'vue/dist/vue.js',
                '@': path.resolve('resources/js'),
            },
        },
        module: {
            rules: [{
                test: /\.js$/,
                exclude: /node_modules/,
                loader: 'babel-loader',
            }],
        },
    })
    .version()
    .sourceMaps()
/**
 * This file is part of the Spryker Demoshop.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

const path = require('path');
const oryx = require('@spryker/oryx');
const webpack = require('webpack');
const autoprefixer = require('autoprefixer');
const ExtractTextPlugin = require('extract-text-webpack-plugin');
const settings = require('./settings');

const baseCssRegex = /ShopUI\/\S+\/styles\/basics(\/\S+)?\.scss$/;
const baseCss = new ExtractTextPlugin({
    filename: `css/${settings.name}.basics.css`
});

const utilCssRegex = /ShopUI\/\S+\/styles\/utils(\/\S+)?\.scss$/;
const utilCss = new ExtractTextPlugin({
    filename: `css/${settings.name}.utils.css`
});

const css = new ExtractTextPlugin({
    filename: `css/${settings.name}.[name].css`,
    allChunks: true
});

const cssExtractSettings = {
    fallback: 'style-loader',
    use: [{
        loader: 'css-loader',
        query: {
            sourceMap: !settings.options.isProduction
        }
    }, {
        loader: 'sass-loader',
        query: {
            sourceMap: true
        }
    }]
};

let devtool = 'inline-source-map';
let postCssPlugins = [];

if (settings.options.isProduction) {
    devtool = false;

    postCssPlugins = [
        autoprefixer({
            browsers: ['last 4 versions']
        })
    ];
}

const config = {
    context: settings.paths.rootDir,
    stats: settings.options.isVerbose ? 'verbose' : 'errors-only',
    devtool,

    watch: settings.options.isWatching,
    watchOptions: {
        aggregateTimeout: 300,
        poll: 500,
        ignored: /(node_modules)/
    },

    entry: {
        shop: path.join(__dirname, '../main.ts')
    },

    output: {
        path: settings.paths.publicDir,
        filename: `./js/${settings.name}.[name].js`,
        publicPath: '/assets/',
        jsonpFunction: `webpackJsonp_${settings.name}`
    },

    resolve: {
        extensions: ['.ts', '.js', '.json', '.css', '.scss']
    },

    resolveLoader: {
        modules: [
            'node_modules',
            path.join(settings.paths.srcDir, 'node_modules')
        ]
    },

    module: {
        rules: [{
            test: /\.ts$/,
            loader: 'ts-loader',
            options: {
                configFile: path.join(__dirname, './tsconfig.json'),
                compilerOptions: {
                    baseUrl: settings.paths.rootDir
                }
            },
        }, {
            test: baseCssRegex,
            loader: baseCss.extract(cssExtractSettings)
        }, {
            test: utilCssRegex,
            loader: utilCss.extract(cssExtractSettings)
        }, {
            test: /\.s?css/i,
            exclude: [
                baseCssRegex,
                utilCssRegex
            ],
            loader: css.extract(cssExtractSettings)
        }, {
            test: /\.(ttf|woff2?|eot|svg|otf)\??(\d*\w*=?\.?)+$/i,
            use: [{
                loader: 'file-loader',
                options: {
                    name: '/fonts/[name].[ext]',
                    publicPath: settings.paths.publicPath
                }
            }]
        }, {
            test: /\.(jpe?g|png|gif|svg)\??(\d*\w*=?\.?)+$/i,
            use: [{
                loader: 'file-loader',
                options: {
                    name: '/images/[name].[ext]',
                    publicPath: settings.paths.publicPath
                }
            }]
        }]
    },

    plugins: [
        new webpack.LoaderOptionsPlugin({
            options: {
                context: settings.paths.rootDir,
                postcss: postCssPlugins
            }
        }),
        new webpack.optimize.CommonsChunkPlugin({
            name: 'shop'
        }),
        new webpack.optimize.CommonsChunkPlugin({
            name: 'manifest'
        }),
        baseCss,
        utilCss,
        css
    ]
};

module.exports = config;

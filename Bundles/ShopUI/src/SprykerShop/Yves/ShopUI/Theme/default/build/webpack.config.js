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

/**
 * 
 * ExtractTextPlugin
 */
const cssPluginSettings = {
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

const basicsCssRegex = /ShopUI\/\S+\/styles\/basics(\/\S+)?\.scss$/;
const basicsCssPlugin = new ExtractTextPlugin({
    filename: `css/${settings.name}.basics.css`
});

const utilsCssRegex = /ShopUI\/\S+\/styles\/utils(\/\S+)?\.scss$/;
const utilsCssPlugin = new ExtractTextPlugin({
    filename: `css/${settings.name}.utils.css`
});

const shopCssPlugin = new ExtractTextPlugin({
    filename: `css/${settings.name}.[name].css`,
    allChunks: true
});

/**
 * 
 * shop entry points (oryx)
 */
const sprykerEntries = oryx.find(settings.entries.spryker, []);
const projectEntries = oryx.find(settings.entries.project, []);
const shopEntries = [
    ...new Set([
        path.join(__dirname, '../main.ts'),
        ...sprykerEntries,
        ...projectEntries
    ])
];

/**
 * 
 * postCss
 */
const postCssPlugins = [];
if (settings.options.isProduction) {
    postCssPlugins.push(
        autoprefixer({
            browsers: ['last 4 versions']
        })
    )
}

/**
 * 
 * configuration
 */
const config = {
    context: settings.paths.rootDir,
    stats: settings.options.isVerbose ? 'verbose' : 'errors-only',
    devtool: settings.options.isProduction ? false : 'inline-source-map',
    watch: settings.options.isWatching,
    watchOptions: {
        aggregateTimeout: 300,
        poll: 500,
        ignored: /(node_modules)/
    },

    entry: {
        shop: shopEntries
    },

    output: {
        path: settings.paths.publicDir,
        filename: `./js/${settings.name}.[name].js`,
        publicPath: '/assets/',
        jsonpFunction: `webpackJsonp_${settings.name}`
    },

    resolve: {
        extensions: ['.ts', '.js', '.json', '.css', '.scss'],
        alias: {
            'ShopUI': settings.paths.srcDir
        }
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
                    baseUrl: settings.paths.rootDir,
                    paths: {
                        '*': ['*', settings.paths.srcDir + '/*'],
                        'ShopUI/*': [settings.paths.srcDir + '/*']
                    }
                }
            },
        }, {
            test: basicsCssRegex,
            loader: basicsCssPlugin.extract(cssPluginSettings)
        }, {
            test: utilsCssRegex,
            loader: utilsCssPlugin.extract(cssPluginSettings)
        }, {
            test: /\.s?css/i,
            exclude: [
                basicsCssRegex,
                utilsCssRegex
            ],
            loader: shopCssPlugin.extract(cssPluginSettings)
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
        basicsCssPlugin,
        utilsCssPlugin,
        shopCssPlugin,

        new webpack.LoaderOptionsPlugin({
            options: {
                context: settings.paths.rootDir,
                postcss: postCssPlugins
            }
        }),
        new webpack.DefinePlugin({
            PRODUCTION: settings.options.isProduction
        }),
        // new webpack.optimize.CommonsChunkPlugin({
        //     name: 'shop'
        // }),
        new webpack.optimize.CommonsChunkPlugin({
            name: 'manifest'
        })
    ]
};

module.exports = config;

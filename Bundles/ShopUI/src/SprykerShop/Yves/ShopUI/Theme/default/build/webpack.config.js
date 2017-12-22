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
 * shop entry points (oryx)
 */

const componentEntries = new Map();

function populateEntries(p) { 
    const dir = path.dirname(p);
    const componentName = path.basename(dir);
    const componentType = path.basename(path.dirname(dir));
    componentEntries.set(`${componentType}/${componentName}`, p);
}

oryx.find(settings.entries.spryker, []).forEach(populateEntries);
oryx.find(settings.entries.project, []).forEach(populateEntries);

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

const appCssPlugin = new ExtractTextPlugin({
    filename: `css/${settings.name}.[name].css`,
    allChunks: true
});

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
        app: [
            path.join(__dirname, '../main.ts'),
            ...Array.from(componentEntries.values())
        ]
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
                configFile: path.join(__dirname, 'tsconfig.json'),
                compilerOptions: {
                    baseUrl: settings.paths.rootDir,
                    outDir: settings.paths.publicPath,
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
            loader: appCssPlugin.extract(cssPluginSettings)
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
        appCssPlugin,

        new webpack.LoaderOptionsPlugin({
            options: {
                context: settings.paths.rootDir,
                postcss: postCssPlugins
            }
        }),

        new webpack.DefinePlugin({
            PRODUCTION: settings.options.isProduction
        }),

        new webpack.optimize.CommonsChunkPlugin({
            name: 'app',
            minChunks: 2
        }),

        new webpack.optimize.CommonsChunkPlugin({
            name: 'manifest',
            minChunks: Infinity
        })
    ]
};

module.exports = config;

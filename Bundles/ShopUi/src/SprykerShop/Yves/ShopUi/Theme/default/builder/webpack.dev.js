const path = require('path');
const webpack = require('webpack');
const ExtractTextPlugin = require('extract-text-webpack-plugin');
const scanner = require('./libs/scanner');
const settings = require('./settings');

/**
 * 
 * ExtractTextPlugin
 */
const cssPluginConfig = {
    fallback: 'style-loader',
    use: [{
        loader: 'css-loader',
        query: {
            sourceMap: true
        }
    }, {
        loader: 'sass-loader',
        query: {
            sourceMap: true
        }
    }]
};

const basicsCssRegex = /ShopUi\/\S+\/styles\/basics(\/\S+)?\.scss$/;
const basicsCssPlugin = new ExtractTextPlugin({
    filename: `css/${settings.name}.basics.css`
});

const utilsCssRegex = /ShopUi\/\S+\/styles\/utils(\/\S+)?\.scss$/;
const utilsCssPlugin = new ExtractTextPlugin({
    filename: `css/${settings.name}.utils.css`
});

const appCssPlugin = new ExtractTextPlugin({
    filename: `css/${settings.name}.[name].css`,
    allChunks: true
});

/**
 * 
 * settingsuration
 */
const config = {
    context: settings.paths.rootDir,
    mode: 'development',
    stats: {
        colors: true,
        chunks: false,
        chunkModules: false,
        chunkOrigins: false,
        modules: false,
        entrypoints: false
    },
    devtool: 'inline-source-map',

    entry: {
        app: [
            path.join(settings.paths.srcDir, 'main.ts'),
            ...scanner.scan()
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
        modules: [
            'node_modules/@spryker/shop-ui-builder/node_modules',
            'node_modules'
        ],
        alias: {
            'shop-ui': settings.paths.srcDir
        }
    },

    resolveLoader: {
        modules: [
            'node_modules/@spryker/shop-ui-builder/node_modules',
            'node_modules'
        ]
    },

    module: {
        rules: [{
            test: /\.ts$/,
            loader: 'ts-loader',
            options: {
                context: settings.paths.srcDir,
                configFile: path.join(__dirname, 'tsconfig.json'),
                compilerOptions: {
                    baseUrl: settings.paths.rootDir,
                    outDir: settings.paths.publicPath,
                    paths: {
                        '*': ['*', settings.paths.srcDir + '/*'],
                        'ShopUi/*': [settings.paths.srcDir + '/*']
                    }
                }
            },
        }, {
            test: basicsCssRegex,
            loader: basicsCssPlugin.extract(cssPluginConfig)
        }, {
            test: utilsCssRegex,
            loader: utilsCssPlugin.extract(cssPluginConfig)
        }, {
            test: /\.scss/i,
            exclude: [
                basicsCssRegex,
                utilsCssRegex
            ],
            loader: appCssPlugin.extract(cssPluginConfig)
        }]
    },

    plugins: [
        basicsCssPlugin,
        utilsCssPlugin,
        appCssPlugin,

        new webpack.LoaderOptionsPlugin({
            options: {
                context: settings.paths.rootDir
            }
        }),

        new webpack.DefinePlugin({
            __NAME__: `'${settings.name}'`,
            __PRODUCTION__: false
        })
    ]
};

module.exports = config;

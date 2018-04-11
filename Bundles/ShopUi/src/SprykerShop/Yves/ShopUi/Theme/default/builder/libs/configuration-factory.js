const path = require('path');
const webpack = require('webpack');
const ExtractTextPlugin = require('extract-text-webpack-plugin');

class ConfigurationFactory {

    constructor(settings, finder) {
        this.settings = settings;
        this.finder = finder;
    }

    getAppEntryPoint() {
        return [
            path.join(this.settings.paths.srcDir, './main.ts'),
            ...this.finder.findComponents()
        ]
    }

    getConfiguration() {
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
            filename: `css/${this.settings.name}.basics.css`
        });

        const utilsCssRegex = /ShopUi\/\S+\/styles\/utils(\/\S+)?\.scss$/;
        const utilsCssPlugin = new ExtractTextPlugin({
            filename: `css/${this.settings.name}.utils.css`
        });

        const appCssPlugin = new ExtractTextPlugin({
            filename: `css/${this.settings.name}.[name].css`,
            allChunks: true
        });

        /**
         *
         * configuration
         */
        return {
            // mode: 'development',
            context: this.settings.paths.rootDir,
            devtool: 'inline-source-map',

            stats: {
                colors: true,
                chunks: false,
                chunkModules: false,
                chunkOrigins: false,
                modules: false,
                entrypoints: false
            },

            entry: {
                app: this.getAppEntryPoint()
            },

            output: {
                path: this.settings.paths.publicDir,
                filename: `./js/${this.settings.name}.[name].js`,
                publicPath: '/assets/',
                jsonpFunction: `webpackJsonp_${this.settings.name}`
            },

            resolve: {
                extensions: ['.ts', '.js', '.json', '.css', '.scss'],
                alias: {
                    'shop-ui': this.settings.paths.srcDir
                }
            },

            module: {
                rules: [{
                    test: /\.ts$/,
                    loader: 'ts-loader',
                    options: {
                        context: this.settings.paths.rootDir,
                        configFile: path.join(this.settings.paths.rootDir, './tsconfig.json'),
                        compilerOptions: {
                            baseUrl: this.settings.paths.rootDir,
                            outDir: this.settings.paths.publicPath
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

                new webpack.DefinePlugin({
                    __NAME__: `'${this.settings.name}'`,
                    __PRODUCTION__: false
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
    }

}

module.exports = ConfigurationFactory;

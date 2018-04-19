const path = require('path');
const webpack = require('webpack');
const MiniCssExtractPlugin = require('mini-css-extract-plugin');
const CleanWebpackPlugin = require('clean-webpack-plugin');
const ConfigurationFactory = require('./configuration-factory');

module.exports = class ConfigurationFactoryForDevelopment extends ConfigurationFactory {
    getGlobalVariables() {
        return {
            __NAME__: `'${this.settings.name}'`,
            __PRODUCTION__: false
        }
    }

    getAppEntryPoint() {
        return [
            path.join(this.settings.dirs.ui.project, './app.ts'),
            path.join(this.settings.dirs.ui.project, './styles/basics.scss'),
            ...this.finder.findComponents(),
            path.join(this.settings.dirs.ui.project, './styles/utils.scss')
        ]
    }

    getVendorEntryPoint() {
        return [
            path.join(this.settings.dirs.ui.project, './vendor.ts'),
        ]
    }

    getTSLoaderOptions() {
        return {
            context: this.settings.dirs.context,
            configFile: path.join(this.settings.dirs.context, './tsconfig.json'),
            compilerOptions: {
                baseUrl: this.settings.dirs.context,
                outDir: this.settings.paths.public
            }
        }
    }

    getCssLoaderOptions() {
        return {
            importLoaders: 1
        }
    }

    getPostcssLoaderOptions() {
        return {
            ident: 'postcss',
            plugins: []
        }
    }

    getSassLoaderOptions() {
        return {}
    }

    getSassResourcesLoaderOptions() {
        return {
            resources: path.join(this.settings.paths.ui.project, './styles/shared.scss')
        }
    }

    getMiniCssExtractPluginOptions() {
        return {
            filename: `./css/${this.settings.name}.[name].css`,
        }
    }

    getCleanWebpackPluginPaths() {
        return [
            'js',
            'css'
        ]
    }

    getCleanWebpackPluginOptions() {
        return {
            root: this.settings.dirs.public,
            verbose: true,
            beforeEmit: true
        }
    }

    createConfiguration() {
        /**
         *
         * configuration
         */
        return {
            context: this.settings.dirs.context,
            mode: this.settings.mode,
            watch: this.settings.watch,
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
                app: this.getAppEntryPoint(),
                vendor: this.getVendorEntryPoint()
            },

            output: {
                path: this.settings.dirs.public,
                filename: `./js/${this.settings.name}.[name].js`,
                publicPath: '/assets/',
                jsonpFunction: `webpackJsonp_${this.settings.name}`
            },

            resolve: {
                extensions: ['.ts', '.js', '.json', '.css', '.scss'],
                alias: {
                    'ui-shop': this.settings.dirs.ui.shop,
                    'ui-project': this.settings.dirs.ui.project
                }
            },

            module: {
                rules: [
                    { test: /\.ts$/, loader: 'ts-loader', options: this.getTSLoaderOptions() },
                    {
                        test: /\.scss/i,
                        use: [
                            MiniCssExtractPlugin.loader,
                            { loader: 'css-loader', options: this.getCssLoaderOptions() },
                            { loader: 'postcss-loader', options: this.getPostcssLoaderOptions() },
                            { loader: 'sass-loader', options: this.getSassLoaderOptions() },
                            { loader: 'sass-resources-loader', options: this.getSassResourcesLoaderOptions() }
                        ]
                    }
                ]
            },

            optimization: {
                runtimeChunk: 'single',
                concatenateModules: false,
                splitChunks: {
                    chunks: 'initial',
                    minChunks: 1,
                    cacheGroups: {
                        default: false,
                        vendors: false
                    }
                }
            },

            plugins: [
                new webpack.DefinePlugin(this.getGlobalVariables()),
                new CleanWebpackPlugin(this.getCleanWebpackPluginPaths(), this.getCleanWebpackPluginOptions()),
                new MiniCssExtractPlugin(this.getMiniCssExtractPluginOptions())
            ]
        }
    }
}

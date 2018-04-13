const path = require('path');
const webpack = require('webpack');
const MiniCssExtractPlugin = require('mini-css-extract-plugin');
const CleanWebpackPlugin = require('clean-webpack-plugin');

class ConfigurationFactoryForDevelopment {

    constructor(settingFactory, finder) {
        this.settings = settingFactory.getSettings();
        this.finder = finder;
    }

    getGlobalVariables() {
        return {
            __NAME__: `'${this.settings.name}'`,
            __PRODUCTION__: false
        }
    }

    getAppEntryPoint() {
        return [
            path.join(this.settings.paths.srcDir, './app.ts'),
            path.join(this.settings.paths.srcDir, './styles/basics.scss'),
            ...this.finder.findComponents(),
            path.join(this.settings.paths.srcDir, './styles/utils.scss')
        ]
    }

    getVendorEntryPoint() {
        return [
            '@webcomponents/webcomponentsjs/custom-elements-es5-adapter',
            '@webcomponents/webcomponentsjs/webcomponents-sd-ce'
        ]
    }

    getTSLoaderOptions() {
        return {
            context: this.settings.paths.rootDir,
            configFile: path.join(this.settings.paths.rootDir, './tsconfig.json'),
            compilerOptions: {
                baseUrl: this.settings.paths.rootDir,
                outDir: this.settings.paths.publicPath
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
            root: this.settings.paths.publicDir,
            verbose: true,
            beforeEmit: true
        }
    }

    getConfiguration() {
        /**
         *
         * configuration
         */
        return {
            context: this.settings.paths.rootDir,
            mode: 'development',
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
                rules: [
                    { test: /\.ts$/, loader: 'ts-loader', options: this.getTSLoaderOptions() },
                    {
                        test: /\.scss/i,
                        use: [
                            MiniCssExtractPlugin.loader,
                            { loader: 'css-loader', options: this.getCssLoaderOptions() },
                            { loader: 'postcss-loader', options: this.getPostcssLoaderOptions() },
                            { loader: 'sass-loader', options: this.getSassLoaderOptions() }
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

module.exports = ConfigurationFactoryForDevelopment;

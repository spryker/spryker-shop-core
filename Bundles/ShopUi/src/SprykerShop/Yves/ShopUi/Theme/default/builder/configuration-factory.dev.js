const path = require('path');
const webpack = require('webpack');
const MiniCssExtractPlugin = require('mini-css-extract-plugin');
const CleanWebpackPlugin = require('clean-webpack-plugin');
const ConfigurationFactory = require('./configuration-factory');

module.exports = class DevelopmentConfigurationFactory extends ConfigurationFactory {
    constructor(settings, finder) {
        super(settings, finder);

        this.tsConfigFile = path.join(this.settings.dirs.context, './tsconfig.json');
        this.mainEntryPointFile = path.join(this.settings.dirs.ui.project, './app.ts');
        this.vendorEntryPointFile = path.join(this.settings.dirs.ui.project, './vendor.ts');
        this.basicStyleFile = path.join(this.settings.dirs.ui.project, './styles/basics.scss');
        this.utilStyleFile = path.join(this.settings.dirs.ui.project, './styles/utils.scss');
        this.sharedStyleFile = path.join(this.settings.dirs.ui.project, './styles/shared.scss');
    }

    findComponentEntryPoints() {
        process.stdout.write('Scanning for component entry points... ');
        const files = this.finder.find(this.settings.find.componentEntryPoints);

        const entryPoints = Object.values(files.reduce((map, file) => {
            const dir = path.dirname(file);
            const name = path.basename(dir);
            const type = path.basename(path.dirname(dir));
            map[`${type}/${name}`] = file;
            return map;
        }, {}));

        console.log(`${entryPoints.length} found`);
        return entryPoints;
    }

    findComponentStyles() {
        process.stdout.write('Scanning for component styles... ');
        const styles = this.finder.find(this.settings.find.componentStyles);
        console.log(`${styles.length} found`);
        return styles;
    }

    getShopModuleAliasesFromTsConfig() {
        const tsConfig = require(this.tsConfigFile);
        const tsConfigPaths = tsConfig.compilerOptions.paths;

        return Object.keys(tsConfigPaths).reduce((map, pathName) => {
            if (pathName === '*') {
                return map;
            }

            if (tsConfigPaths[pathName].length === 0) {
                return map;
            }

            const alias = pathName.replace(/(\/\*?)$/, '');
            const aliasPath = tsConfigPaths[pathName][0].replace(/(\/\*?)$/, '');
            const aliasDir = path.join(this.settings.dirs.context, aliasPath);
            map[alias] = aliasDir;
            return map;
        }, {});
    }

    getGlobalVariables() {
        return {
            __NAME__: `'${this.settings.name}'`,
            __PRODUCTION__: false
        }
    }

    getAppEntryPoint() {
        return [
            this.mainEntryPointFile,
            this.basicStyleFile,
            ...this.findComponentEntryPoints(),
            this.utilStyleFile
        ]
    }

    getVendorEntryPoint() {
        return [
            this.vendorEntryPointFile
        ]
    }

    getTSLoaderOptions() {
        return {
            context: this.settings.dirs.context,
            configFile: this.tsConfigFile,
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
            resources: [
                this.sharedStyleFile,
                ...this.findComponentStyles()
            ]
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
        return {
            context: this.settings.dirs.context,
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
                path: this.settings.dirs.public,
                filename: `./js/${this.settings.name}.[name].js`,
                publicPath: '/assets/',
                jsonpFunction: `webpackJsonp_${this.settings.name}`
            },

            resolve: {
                extensions: ['.ts', '.js', '.json', '.css', '.scss'],
                alias: this.getShopModuleAliasesFromTsConfig()
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

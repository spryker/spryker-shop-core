const webpack = require('webpack');
const autoprefixer = require('autoprefixer');
const UglifyJsPlugin = require('uglifyjs-webpack-plugin');
const OptimizeCSSAssetsPlugin = require('optimize-css-assets-webpack-plugin');
const DevelopmentConfigurationFactory = require('./configuration-factory.dev');

module.exports = class ProductionConfigurationFactory extends DevelopmentConfigurationFactory {
    getGlobalVariables() {
        return {
            __NAME__: `'${this.settings.name}'`,
            __PRODUCTION__: true
        }
    }

    getPostcssLoaderOptions() {
        return {
            ident: 'postcss',
            plugins: [
                autoprefixer({
                    'browsers': ['> 1%', 'last 2 versions']
                })
            ]
        }
    }

    getUglifyJsPluginOptions() {
        return {
            cache: true,
            parallel: true,
            sourceMap: false,
            uglifyOptions: {
                output: {
                    comments: false,
                    beautify: false
                }
            }
        }
    }

    getOptimizeCSSAssetsPluginOptions() {
        return {
            cssProcessorOptions: {
                discardComments: {
                    removeAll: true
                }
            }
        }
    }

    createConfiguration() {
        const configuration = super.createConfiguration();

        return {
            ...configuration,

            mode: 'production',
            devtool: false,

            optimization: {
                ...configuration.optimization,

                minimizer: [
                    new UglifyJsPlugin(this.getUglifyJsPluginOptions()),
                    new OptimizeCSSAssetsPlugin(this.getOptimizeCSSAssetsPluginOptions())
                ]
            }
        };
    }
}

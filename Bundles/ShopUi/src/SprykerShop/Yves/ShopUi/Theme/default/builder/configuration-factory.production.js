const webpack = require('webpack');
const autoprefixer = require('autoprefixer');
const UglifyJsPlugin = require('uglifyjs-webpack-plugin');
const OptimizeCSSAssetsPlugin = require('optimize-css-assets-webpack-plugin');
const ConfigurationFactoryForDevelopment = require('./configuration-factory.development');

module.exports = class ConfigurationFactoryForProduction extends ConfigurationFactoryForDevelopment {
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
            sourceMap: false
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
        const devConfiguration = super.createConfiguration();

        return {
            ...devConfiguration,

            devtool: false,

            optimization: {
                ...devConfiguration.optimization,

                minimizer: [
                    new UglifyJsPlugin(this.getUglifyJsPluginOptions()),
                    new OptimizeCSSAssetsPlugin(this.getOptimizeCSSAssetsPluginOptions())
                ]
            }
        };
    }
}

const ConfigurationFactory = require('./configuration-factory');
const webpack = require('webpack');
const ExtractTextPlugin = require('extract-text-webpack-plugin');

class ConfigurationFactoryForProduction extends ConfigurationFactory {

    getConfiguration() {
        return {
            ...super.getConfiguration(),

            // mode: 'production'
        };
    }

}

module.exports = ConfigurationFactoryForProduction;

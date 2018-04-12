const ConfigurationFactoryForDevelopment = require('./configuration-factory.dev');

class ConfigurationFactoryForDevelopmentInWatchMode extends ConfigurationFactoryForDevelopment {

    getConfiguration() {
        const devConfiguration = super.getConfiguration();

        return {
            ...devConfiguration,

            watch: true,
            watchOptions: {
                aggregateTimeout: 300,
                poll: 500,
                ignored: /(node_modules)/
            }
        }
    }

}

module.exports = ConfigurationFactoryForDevelopmentInWatchMode;

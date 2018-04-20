const DevelopmentConfigurationFactory = require('./configuration-factory.dev');

module.exports = class DevelopmentWatchConfigurationFactory extends DevelopmentConfigurationFactory {
    createConfiguration() {
        const configuration = super.createConfiguration();

        return {
            ...configuration,

            watch: true
        };
    }
}

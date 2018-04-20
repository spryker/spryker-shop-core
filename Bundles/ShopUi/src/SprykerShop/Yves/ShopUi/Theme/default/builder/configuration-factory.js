module.exports = class ConfigurationFactory {
    constructor(settings, finder) {
        this.settings = settings;
        this.finder = finder;
        console.log(`--> ${this.settings.name}`);
    }

    createConfiguration() {
        /**
         *
         * configuration
         */
        return {}
    }
}

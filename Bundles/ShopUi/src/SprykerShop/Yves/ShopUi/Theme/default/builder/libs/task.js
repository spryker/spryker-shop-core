module.exports = function task(settings) {
    const Finder = require(`${settings.dirs.ui.shop}/builder/libs/finder`);
    const ConfigurationFactory = require(`${settings.dirs.ui.shop}/builder/configuration-factory.${settings.mode}`);
    const Compiler = require(`${settings.dirs.ui.shop}/builder/libs/compiler`);

    const finder = new Finder(settings);
    const configurationFactory = new ConfigurationFactory(settings, finder);
    const compiler = new Compiler(configurationFactory);

    compiler.build();
}

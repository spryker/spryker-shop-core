module.exports = function task(settings, ConfigurationFactory) {
    const Finder = require(`${settings.dirs.ui.shop}/builder/libs/finder`);
    const Compiler = require(`${settings.dirs.ui.shop}/builder/libs/compiler`);

    const finder = new Finder();
    const configurationFactory = new ConfigurationFactory(settings, finder);
    const compiler = new Compiler(configurationFactory);

    compiler.build();
}

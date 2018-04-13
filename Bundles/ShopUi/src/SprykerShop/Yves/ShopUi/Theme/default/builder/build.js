const mode = process.argv.length > 2 ? process.argv[2] : 'dev';

const SettingFactory = require('./settings/setting-factory');
const Compiler = require('./libs/compiler');
const Finder = require('./libs/finder');
const ConfigurationFactory = require(`./configurations/configuration-factory.${mode}`);

const settingFactory = new SettingFactory();
const finder = new Finder(settingFactory);
const configurationFactory = new ConfigurationFactory(settingFactory, finder);
const compiler = new Compiler(configurationFactory);

compiler.run();

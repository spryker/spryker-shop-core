const mode = process.argv.length > 2 ? process.argv[2] : 'dev';
const Compiler = require('./libs/compiler');
const Finder = require('./libs/finder');
const SettingFactory = require('./settings/setting-factory');
const ConfigurationFactory = require(`./configurations/configuration-factory.${mode}`);

const compiler = new Compiler();
const settingFactory = new SettingFactory();
const settings = settingFactory.getSettings();
const finder = new Finder(settings);
const configurationFactory = new ConfigurationFactory(settings, finder);
const configuration = configurationFactory.getConfiguration();

compiler.run(configuration);

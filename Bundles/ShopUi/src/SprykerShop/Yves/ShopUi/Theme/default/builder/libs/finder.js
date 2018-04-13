const path = require('path');
const fg = require('fast-glob');

const defaultGlobSettings = {
    followSymlinkedDirectories: false,
    absolute: true
};

class Finder {

    constructor(settingFactory, globSettings = {}) {
        this.settings = settingFactory.getSettings();
        this.globSettings = Object.assign({}, defaultGlobSettings, globSettings);
    }

    glob(dirs, patterns) {
        return dirs.reduce((results, dir) => [
            ...results,
            ...fg.sync(patterns, {
                ...this.globSettings,
                cwd: dir
            })
        ], []);
    }

    toUniqueItemArray(array) {
        const map = array.reduce((map, fullPath) => {
            const dir = path.dirname(fullPath);
            const componentName = path.basename(dir);
            const componentType = path.basename(path.dirname(dir));
            return map.set(`${componentType}/${componentName}`, fullPath);
        }, new Map());

        return Array.from(map.values());
    }

    findCoreComponents() {
        process.stdout.write('Scanning for core components... ');
        const components = this.glob(this.settings.glob.core.dirs, this.settings.glob.core.patterns);
        console.log(`${components.length} found`);
        return components;
    }

    findProjectComponents() {
        process.stdout.write('Scanning for project components... ');
        const components = this.glob(this.settings.glob.project.dirs, this.settings.glob.project.patterns);
        console.log(`${components.length} found`);
        return components;
    }

    findComponents() {
        return this.toUniqueItemArray([
            ...this.findCoreComponents(),
            ...this.findProjectComponents()
        ]);
    }

}

module.exports = Finder;

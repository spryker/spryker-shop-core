const path = require('path');
const fg = require('fast-glob');

const defaultGlobSettings = {
    followSymlinkedDirectories: false,
    absolute: true
};

module.exports = class Finder {
    constructor(settings, globSettings = {}) {
        this.settings = settings;
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
        const map = array.reduce((map, dir) => {
            const dirName = path.dirname(dir);
            const name = path.basename(dirName);
            const type = path.basename(path.dirname(dirName));
            return map.set(`${type}/${name}`, dir);
        }, new Map());

        return Array.from(map.values());
    }

    findComponents() {
        process.stdout.write('Scanning for components... ');

        const components = this.glob(this.settings.find.components.dirs, this.settings.find.components.patterns);
        const uniqueComponents = this.toUniqueItemArray(components);

        console.log(`${uniqueComponents.length} found`);
        return uniqueComponents;
    }
}

const path = require('path');
const glob = require('fast-glob');

module.exports = class Finder {
    glob(dirs, patterns, globSettings) {
        return dirs.reduce((results, dir) => [
            ...results,
            ...glob.sync(patterns, {
                ...globSettings,
                cwd: dir
            })
        ], []);
    }

    find(settings) {
        return this.glob(settings.dirs, settings.patterns, settings.globSettings);
    }
}

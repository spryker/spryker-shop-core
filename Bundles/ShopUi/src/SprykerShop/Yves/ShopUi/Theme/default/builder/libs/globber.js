const fg = require('fast-glob');

function glob(dirs, patterns) {
    return dirs.reduce((results, dir) => [
        ...results,
        ...fg.sync(patterns, {
            cwd: dir,
            followSymlinkedDirectories: false,
            absolute: true
        })
    ], []);
}

module.exports = {
    glob
};

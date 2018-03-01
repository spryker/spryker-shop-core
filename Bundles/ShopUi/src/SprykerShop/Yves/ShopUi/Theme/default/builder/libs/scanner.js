const path = require('path');
const globber = require('./globber');
const settings = require('../settings');

function glob() { 
    const spryker = globber.glob(settings.glob.spryker.dirs, settings.glob.spryker.patterns);
    console.log(`Spryker: ${spryker.length} found`);

    const project = globber.glob(settings.glob.project.dirs, settings.glob.project.patterns);
    console.log(`Project: ${project.length} found`);

    return [
        ...spryker,
        ...project
    ];
}

function reduceToMap(map, fullPath) {
    const dir = path.dirname(fullPath);
    const componentName = path.basename(dir);
    const componentType = path.basename(path.dirname(dir));
    return map.set(`${componentType}/${componentName}`, fullPath);
}

function scan() { 
    console.log('Scanning for components...');
    return Array.from(glob().reduce(reduceToMap, new Map()).values());
}

module.exports = {
    scan
};

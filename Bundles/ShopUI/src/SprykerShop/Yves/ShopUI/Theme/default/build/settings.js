/**
 * This file is part of the Spryker Demoshop.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

const path = require('path');
const oryx = require('@spryker/oryx');
const argv = require('yargs').argv;

const rootDir = process.cwd();
const srcDir = path.join(__dirname, '..');
const publicPath = '/public/Yves/assets/';
const publicDir = path.join(rootDir, publicPath);

const settings = {
    name: 'yves_default',

    options: {
        isProduction: !!argv.production,
        isWatching: !!argv.watch,
        isVerbose: !!argv.verbose
    },

    paths: {
        rootDir,
        srcDir,
        publicPath,
        publicDir
    }
}

module.exports = settings;

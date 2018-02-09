/**
 * This file is part of the Spryker Demoshop.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

const path = require('path');
const oryx = require('@spryker/oryx');
const argv = require('yargs').argv;

const theme = 'default';
const rootDir = process.cwd();
const srcDir = path.join(__dirname, '..');
const publicPath = '/public/Yves/assets/';
const publicDir = path.join(rootDir, publicPath);

const settings = {
    name: `yves_${theme}`,
    theme,

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
    },

    entries: {
        spryker: {
            dirs: [
                rootDir
            ],
            patterns: [
                `**/spryker-shop/**/src/SprykerShop/Yves/*/Theme/${theme}/components/**/index.ts`,
                `**/spryker-shop/**/src/SprykerShop/Yves/*/Theme/${theme}/templates/**/index.ts`,
                `**/spryker-shop/**/src/SprykerShop/Yves/*/Theme/${theme}/views/**/index.ts`,
                '!/config/**',
                '!/data/**',
                '!/deploy/**',
                '!/node_modules/**',
                '!/public/**',
                '!/src/**',
                '!/test/**'
            ],
            description: 'loading Spryker components...'
        },

        project: {
            dirs: [
                rootDir
            ],
            patterns: [
                `**/Theme/${theme}/components/**/index.ts`,
                `**/Theme/${theme}/templates/**/index.ts`,
                `**/Theme/${theme}/views/**/index.ts`,
                '!config/**',
                '!data/**',
                '!deploy/**',
                '!node_modules/**',
                '!public/**',
                '!test/**',
                '!vendor/**',
                '!**/spryker/spryker-shop/**'
            ],
            description: 'loading project component...'
        }
    }
}

module.exports = settings;

const path = require('path');

const name = process.env.npm_package_config_shopUi_name;
const theme = process.env.npm_package_config_shopUi_theme;

const rootDir = process.cwd();
const publicPath = process.env.npm_package_config_shopUi_publicPath;
const publicDir = path.join(rootDir, publicPath);
const modulesDir = path.join(rootDir, process.env.npm_package_config_shopUi_modulePath, '..');
const srcDir = path.join(rootDir, process.env.npm_package_config_shopUi_modulePath, './src/SprykerShop/Yves/ShopUi/Theme/default');

const settings = {
    name,
    theme,

    paths: {
        rootDir,
        publicPath,
        publicDir,
        modulesDir,
        srcDir
    },

    glob: {
        spryker: {
            dirs: [
                modulesDir
            ],
            patterns: [
                `**/src/SprykerShop/Yves/*/Theme/${theme}/components/atoms/*/index.ts`,
                `**/src/SprykerShop/Yves/*/Theme/${theme}/components/molecules/*/index.ts`,
                `**/src/SprykerShop/Yves/*/Theme/${theme}/components/organisms/*/index.ts`,
                `**/src/SprykerShop/Yves/*/Theme/${theme}/templates/*/index.ts`,
                `**/src/SprykerShop/Yves/*/Theme/${theme}/views/*/index.ts`
            ]
        },

        project: {
            dirs: [
                rootDir
            ],
            patterns: [
                `**/Theme/${theme}/components/atoms/*/index.ts`,
                `**/Theme/${theme}/components/molecules/*/index.ts`,
                `**/Theme/${theme}/components/organisms/*/index.ts`,
                `**/Theme/${theme}/templates/*/index.ts`,
                `**/Theme/${theme}/views/*/index.ts`,
                '!vendor',
                '!config',
                '!data',
                '!deploy',
                '!node_modules',
                '!public',
                '!src',
                '!test'
            ]
        }
    }
}

module.exports = settings;

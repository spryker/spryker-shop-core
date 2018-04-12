const path = require('path');

class SettingFactory {

    constructor() {
        this.name = process.env.npm_package_config_shopUi_name;
        this.theme = process.env.npm_package_config_shopUi_theme;
        this.rootDir = process.cwd();
        this.publicPath = process.env.npm_package_config_shopUi_publicPath;
        this.publicDir = path.join(this.rootDir, this.publicPath);
        this.modulesDir = path.join(this.rootDir, process.env.npm_package_config_shopUi_modulePath, '..');
        this.srcDir = path.join(this.rootDir, process.env.npm_package_config_shopUi_modulePath, './src/SprykerShop/Yves/ShopUi/Theme', this.theme);
    }

    getGlobCoreDirs() {
        return [
            this.modulesDir
        ]
    }

    getGlobCorePatterns() {
        return [
            `**/src/SprykerShop/Yves/*/Theme/${this.theme}/components/atoms/*/index.ts`,
            `**/src/SprykerShop/Yves/*/Theme/${this.theme}/components/molecules/*/index.ts`,
            `**/src/SprykerShop/Yves/*/Theme/${this.theme}/components/organisms/*/index.ts`,
            `**/src/SprykerShop/Yves/*/Theme/${this.theme}/templates/*/index.ts`,
            `**/src/SprykerShop/Yves/*/Theme/${this.theme}/views/*/index.ts`
        ]
    }

    getGlobProjectDirs() {
        return [
            this.rootDir
        ]
    }

    getGlobProjectPatterns() {
        return [
            `**/Theme/${this.theme}/components/atoms/*/index.ts`,
            `**/Theme/${this.theme}/components/molecules/*/index.ts`,
            `**/Theme/${this.theme}/components/organisms/*/index.ts`,
            `**/Theme/${this.theme}/templates/*/index.ts`,
            `**/Theme/${this.theme}/views/*/index.ts`,
            '!vendor',
            '!config',
            '!data',
            '!deploy',
            '!node_modules',
            '!public',
            '!test'
        ]
    }

    getSettings() {
        return {
            name: this.name,
            theme: this.theme,

            paths: {
                rootDir: this.rootDir,
                publicPath: this.publicPath,
                publicDir: this.publicDir,
                modulesDir: this.modulesDir,
                srcDir: this.srcDir
            },

            glob: {
                core: {
                    dirs: this.getGlobCoreDirs(),
                    patterns: this.getGlobCorePatterns()
                },

                project: {
                    dirs: this.getGlobProjectDirs(),
                    patterns: this.getGlobProjectPatterns()
                }
            }
        }
    }

}

module.exports = SettingFactory;

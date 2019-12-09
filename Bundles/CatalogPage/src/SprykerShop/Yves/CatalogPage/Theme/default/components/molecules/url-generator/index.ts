import register from 'ShopUi/app/registry';
export default register('url-generator', () => import(/* webpackMode: "lazy" */'./url-generator'));

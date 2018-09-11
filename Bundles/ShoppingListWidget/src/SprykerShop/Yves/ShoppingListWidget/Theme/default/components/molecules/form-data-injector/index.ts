import register from 'ShopUi/app/registry';
export default register('form-data-injector', () => import(/* webpackMode: "lazy" */'./form-data-injector'));

import register from 'ShopUi/app/registry';
export default register('form-data-injector', () => import(/* webpackMode: "eager" */'./form-data-injector'));

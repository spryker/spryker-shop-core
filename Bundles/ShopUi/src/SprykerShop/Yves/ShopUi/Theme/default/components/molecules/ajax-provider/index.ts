import register from 'ShopUi/app/registry';
export default register('ajax-provider', () => import(
    /* webpackMode: "eager" */
    './ajax-provider'));

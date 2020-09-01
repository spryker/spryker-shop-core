import register from 'ShopUi/app/registry';
export default register('dynamic-notification-area', () => import(
    /* webpackMode: "eager" */
    /* webpackChunkName: "dynamic-notification-area" */
    './dynamic-notification-area'));

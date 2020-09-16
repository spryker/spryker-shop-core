import register from 'ShopUi/app/registry';
export default register('toggler-hash', () => import(
    /* webpackMode: "lazy" */
    /* webpackChunkName: "toggler-hash" */
    './toggler-hash'));

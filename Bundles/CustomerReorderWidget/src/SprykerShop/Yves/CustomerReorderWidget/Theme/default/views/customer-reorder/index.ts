import register from 'ShopUi/app/registry';
export default register('customer-reorder', () => import(
    /* webpackMode: "lazy" */
    /* webpackChunkName: "customer-reorder" */
    './customer-reorder'));

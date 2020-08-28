import register from 'ShopUi/app/registry';
export default register('style-loader', () => import(
    /* webpackMode: "lazy" */
    /* webpackChunkName: "style-loader" */
    './style-loader'));

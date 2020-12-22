import register from 'ShopUi/app/registry';
export default register('customer-reorder-form', () =>
    import(
        /* webpackMode: "lazy" */
        /* webpackChunkName: "customer-reorder-form" */
        './customer-reorder-form'
    ),
);

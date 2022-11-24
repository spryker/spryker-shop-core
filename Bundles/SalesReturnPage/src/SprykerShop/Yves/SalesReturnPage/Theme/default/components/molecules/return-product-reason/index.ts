import register from 'ShopUi/app/registry';
export default register(
    'return-product-reason',
    () =>
        import(
            /* webpackMode: "lazy" */
            /* webpackChunkName: "return-product-reason" */
            './return-product-reason'
        ),
);

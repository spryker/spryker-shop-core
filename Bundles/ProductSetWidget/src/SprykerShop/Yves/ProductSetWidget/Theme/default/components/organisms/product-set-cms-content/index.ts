import register from 'ShopUi/app/registry';
export default register(
    'product-set-cms-content',
    () =>
        import(
            /* webpackMode: "lazy" */
            /* webpackChunkName: "product-set-cms-content" */
            './product-set-cms-content'
        ),
);

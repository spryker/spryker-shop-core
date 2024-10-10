import register from 'ShopUi/app/registry';
export default register(
    'comparison-product-toggler',
    () =>
        import(
            /* webpackMode: "lazy" */
            /* webpackChunkName: "comparison-product-toggler" */
            './comparison-product-toggler'
        ),
);

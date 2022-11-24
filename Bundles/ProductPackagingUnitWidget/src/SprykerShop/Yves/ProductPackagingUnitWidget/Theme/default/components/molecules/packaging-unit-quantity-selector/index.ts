import register from 'ShopUi/app/registry';
export default register(
    'packaging-unit-quantity-selector',
    () =>
        import(
            /* webpackMode: "lazy" */
            /* webpackChunkName: "packaging-unit-quantity-selector" */
            './packaging-unit-quantity-selector'
        ),
);

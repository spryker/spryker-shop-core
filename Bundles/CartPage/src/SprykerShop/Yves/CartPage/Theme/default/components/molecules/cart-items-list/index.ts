import register from 'ShopUi/app/registry';
export default register(
    'product-cart-items-list',
    () =>
        import(
            /* webpackMode: "eager" */
            './cart-items-list'
        ),
);

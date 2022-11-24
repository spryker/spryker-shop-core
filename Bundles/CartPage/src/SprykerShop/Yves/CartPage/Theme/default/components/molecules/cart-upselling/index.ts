import register from 'ShopUi/app/registry';
export default register(
    'cart-upselling',
    () =>
        import(
            /* webpackMode: "eager" */
            './cart-upselling'
        ),
);

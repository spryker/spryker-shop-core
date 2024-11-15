import register from 'ShopUi/app/registry';
export default register(
    'cart-reorder-form',
    () =>
        import(
            /* webpackMode: "lazy" */
            /* webpackChunkName: "cart-reorder-form" */
            './cart-reorder-form'
        ),
);

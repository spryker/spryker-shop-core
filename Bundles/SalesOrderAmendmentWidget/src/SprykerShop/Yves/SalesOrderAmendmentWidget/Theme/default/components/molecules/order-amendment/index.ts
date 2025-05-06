import register from 'ShopUi/app/registry';

export default register(
    'order-amendment',
    () =>
        import(
            /* webpackMode: "lazy" */
            /* webpackChunkName: "order-amendment" */
            './order-amendment'
        ),
);

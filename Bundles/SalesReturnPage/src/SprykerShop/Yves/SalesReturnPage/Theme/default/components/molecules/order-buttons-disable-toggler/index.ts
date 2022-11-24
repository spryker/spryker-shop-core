import register from 'ShopUi/app/registry';
export default register(
    'order-buttons-disable-toggler',
    () =>
        import(
            /* webpackMode: "lazy" */
            /* webpackChunkName: "order-buttons-disable-toggler" */
            './order-buttons-disable-toggler'
        ),
);

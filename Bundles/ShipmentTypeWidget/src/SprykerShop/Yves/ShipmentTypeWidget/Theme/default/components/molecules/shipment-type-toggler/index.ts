import register from 'ShopUi/app/registry';
export default register(
    'shipment-type-toggler',
    () =>
        import(
            /* webpackMode: "lazy" */
            /* webpackChunkName: "shipment-type-toggler" */
            './shipment-type-toggler'
        ),
);

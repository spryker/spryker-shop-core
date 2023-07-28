import register from 'ShopUi/app/registry';
export default register(
    'service-point-selector',
    () =>
        import(
            /* webpackMode: "lazy" */
            /* webpackChunkName: "service-point-selector" */
            './service-point-selector'
        ),
);

import register from 'ShopUi/app/registry';
export default register(
    'save-new-address',
    () =>
        import(
            /* webpackMode: "lazy" */
            /* webpackChunkName: "save-new-address" */
            './save-new-address'
        ),
);

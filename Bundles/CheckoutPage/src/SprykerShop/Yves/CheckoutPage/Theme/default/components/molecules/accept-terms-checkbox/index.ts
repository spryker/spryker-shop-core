import register from 'ShopUi/app/registry';
export default register(
    'accept-terms-checkbox',
    () =>
        import(
            /* webpackMode: "lazy" */
            /* webpackChunkName: "accept-terms-checkbox" */
            './accept-terms-checkbox'
        ),
);

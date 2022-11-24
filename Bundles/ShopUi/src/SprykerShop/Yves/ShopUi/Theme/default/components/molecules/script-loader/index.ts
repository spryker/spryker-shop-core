import register from 'ShopUi/app/registry';
export default register(
    'script-loader',
    () =>
        import(
            /* webpackMode: "lazy" */
            /* webpackChunkName: "script-loader" */
            './script-loader'
        ),
);

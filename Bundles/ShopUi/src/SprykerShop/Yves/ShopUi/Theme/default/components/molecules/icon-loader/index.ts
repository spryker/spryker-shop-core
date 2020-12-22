import register from 'ShopUi/app/registry';
export default register('icon-loader', () =>
    import(
        /* webpackMode: "eager" */
        './icon-loader'
    ),
);

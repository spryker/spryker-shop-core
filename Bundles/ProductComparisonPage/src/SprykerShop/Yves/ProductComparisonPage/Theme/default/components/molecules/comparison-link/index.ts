import register from 'ShopUi/app/registry';
export default register(
    'comparison-link',
    () =>
        import(
            /* webpackMode: "lazy" */
            /* webpackChunkName: "comparison-link" */
            './comparison-link'
        ),
);

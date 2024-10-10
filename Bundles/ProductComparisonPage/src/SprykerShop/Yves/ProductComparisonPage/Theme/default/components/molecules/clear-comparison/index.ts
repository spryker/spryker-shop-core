import register from 'ShopUi/app/registry';
export default register(
    'clear-comparison',
    () =>
        import(
            /* webpackMode: "lazy" */
            /* webpackChunkName: "comparison-link" */
            './clear-comparison'
        ),
);

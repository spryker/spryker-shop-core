import register from 'ShopUi/app/registry';
export default register(
    'traceable-events-algolia',
    () =>
        import(
            /* webpackMode: "lazy" */
            /* webpackChunkName: "traceable-events-algolia" */
            './traceable-events-algolia'
        ),
);

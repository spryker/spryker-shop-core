import register from 'ShopUi/app/registry';
export default register('quote-request-history-select', () =>
    import(
        /* webpackMode: "lazy" */
        /* webpackChunkName: "quote-request-history-select" */
        './quote-request-history-select'
    ),
);

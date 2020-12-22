import register from 'ShopUi/app/registry';
export default register('merchant-selector-form', () =>
    import(
        /* webpackMode: "lazy" */
        /* webpackChunkName: "merchant-selector-form" */
        './merchant-selector-form'
    ),
);

import register from 'ShopUi/app/registry';
export default register(
    'company-business-unit-address-handler',
    () =>
        import(
            /* webpackMode: "lazy" */
            /* webpackChunkName: "company-business-unit-address-handler" */
            './company-business-unit-address-handler'
        ),
);

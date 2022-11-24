import register from 'ShopUi/app/registry';
export default register(
    'form-clear',
    () =>
        import(
            /* webpackMode: "lazy" */
            /* webpackChunkName: "form-clear" */
            './form-clear'
        ),
);

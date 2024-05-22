import register from 'ShopUi/app/registry';
export default register(
    'ajax-form-submitter',
    () =>
        import(
            /* webpackMode: "lazy" */
            /* webpackChunkName: "ajax-form-submitter" */
            './ajax-form-submitter'
        ),
);

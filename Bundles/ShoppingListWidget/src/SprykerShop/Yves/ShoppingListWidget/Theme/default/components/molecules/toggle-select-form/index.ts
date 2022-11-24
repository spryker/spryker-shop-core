import register from 'ShopUi/app/registry';
export default register(
    'toggle-select-form',
    () =>
        import(
            /* webpackMode: "lazy" */
            /* webpackChunkName: "toggle-select-form" */
            './toggle-select-form'
        ),
);

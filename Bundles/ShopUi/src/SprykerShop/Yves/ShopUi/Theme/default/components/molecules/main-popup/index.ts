import './style.scss';
import register from 'ShopUi/app/registry';
export default register(
    'main-popup',
    () =>
        import(
            /* webpackMode: "lazy" */
            /* webpackChunkName: "main-popup" */
            './main-popup'
        ),
);

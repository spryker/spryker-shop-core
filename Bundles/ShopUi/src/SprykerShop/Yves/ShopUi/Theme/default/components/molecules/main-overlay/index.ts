import './style.scss';
import register from 'ShopUi/app/registry';
export default register(
    'main-overlay',
    () =>
        import(
            /* webpackMode: "lazy" */
            /* webpackChunkName: "main-overlay" */
            './main-overlay'
        ),
);

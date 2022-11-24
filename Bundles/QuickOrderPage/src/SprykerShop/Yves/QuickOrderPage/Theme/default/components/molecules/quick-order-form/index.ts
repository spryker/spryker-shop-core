import './style.scss';
import register from 'ShopUi/app/registry';
export default register(
    'quick-order-form',
    () =>
        import(
            /* webpackMode: "lazy" */
            /* webpackChunkName: "quick-order-form" */
            './quick-order-form'
        ),
);

import register from 'ShopUi/app/registry';
import './style.scss';
export default register(
    'paypal-buttons',
    () =>
        import(
            /* webpackMode: "lazy" */
            /* webpackChunkName: "paypal-buttons" */
            './paypal-buttons'
        ),
);

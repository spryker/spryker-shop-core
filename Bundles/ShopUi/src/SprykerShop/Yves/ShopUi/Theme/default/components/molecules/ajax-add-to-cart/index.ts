import './style.scss';
import register from 'ShopUi/app/registry';
export default register(
    'ajax-add-to-cart',
    () =>
        import(
            /* webpackMode: "eager" */
            './ajax-add-to-cart'
        ),
);

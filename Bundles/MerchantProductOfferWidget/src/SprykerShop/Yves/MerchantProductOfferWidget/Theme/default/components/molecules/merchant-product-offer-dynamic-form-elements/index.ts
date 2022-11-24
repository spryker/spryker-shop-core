import './style.scss';
import register from 'ShopUi/app/registry';
export default register(
    'merchant-product-offer-dynamic-form-elements',
    () =>
        import(
            /* webpackMode: "lazy" */
            /* webpackChunkName: "merchant-product-offer-dynamic-form-elements" */
            './merchant-product-offer-dynamic-form-elements'
        ),
);

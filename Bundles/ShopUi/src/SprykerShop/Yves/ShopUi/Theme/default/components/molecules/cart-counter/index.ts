import './style.scss';
import register from 'ShopUi/app/registry';
export default register('cart-counter', () => import(
    /* webpackMode: "eager" */
    './cart-counter'));

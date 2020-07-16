import './style.scss';
import register from 'ShopUi/app/registry';
export default register('lazy-image', () => import(
    /* webpackMode: "eager" */
    './lazy-image'));

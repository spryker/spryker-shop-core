import './style.scss';
import register from 'ShopUi/app/registry';
export default register('password-complexity-indicator', () => import(
    /* webpackMode: "lazy" */
    /* webpackChunkName: "password-complexity-indicator" */
    './password-complexity-indicator'));

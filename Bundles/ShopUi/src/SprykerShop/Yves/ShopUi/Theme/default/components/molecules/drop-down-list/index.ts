import './styles.scss';
import register from 'ShopUi/app/registry';
export default register('drop-down-list', () => import(
    /* webpackMode: "lazy" */
    /* webpackChunkName: "drop-down-list" */
    './drop-down-list'));

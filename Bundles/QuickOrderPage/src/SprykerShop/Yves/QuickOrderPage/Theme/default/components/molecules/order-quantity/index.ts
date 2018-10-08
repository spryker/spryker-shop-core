import './style.scss';
import register from 'ShopUi/app/registry';
export default register('order-quantity', () => import(/* webpackMode: "lazy" */'./order-quantity'));

import './style.scss';
import register from 'ShopUi/app/registry';
export default register('cart-comments', () => import(/* webpackMode: "lazy" */'./cart-comments'));

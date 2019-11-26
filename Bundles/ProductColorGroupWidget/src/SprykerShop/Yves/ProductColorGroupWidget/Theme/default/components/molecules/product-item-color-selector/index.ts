import './style.scss';
import register from 'ShopUi/app/registry';
export default register('product-item-color-selector', () => import(/* webpackMode: "lazy" */'./product-item-color-selector'));

import './style.scss';
import register from 'ShopUi/app/registry';
export default register('product-detail-color-selector', () => import(/* webpackMode: "lazy" */'./product-detail-color-selector'));

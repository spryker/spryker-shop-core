import './style';
import register from 'ShopUi/app/registry';
export default register('volume-price', () => import(/* webpackMode: "lazy" */'./volume-price'));
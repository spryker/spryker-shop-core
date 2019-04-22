import './style.scss';
import register from 'ShopUi/app/registry';
export default register('url-generator-mask', () => import(/* webpackMode: "eager" */'./url-generator-mask'));

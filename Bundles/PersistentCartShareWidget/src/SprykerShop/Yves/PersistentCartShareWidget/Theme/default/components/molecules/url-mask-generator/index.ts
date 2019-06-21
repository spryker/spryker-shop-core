import './style.scss';
import register from 'ShopUi/app/registry';
export default register('url-mask-generator', () => import(/* webpackMode: "eager" */'./url-mask-generator'));

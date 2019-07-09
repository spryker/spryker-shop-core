import './style.scss';
import register from 'ShopUi/app/registry';
export default register('comment-form', () => import(/* webpackMode: "lazy" */'./comment-form'));

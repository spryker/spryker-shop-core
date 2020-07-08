import './style';
import register from '../../../app/registry';
export default register('cart-counter', () => import(/* webpackMode: "eager" */'./cart-counter'));

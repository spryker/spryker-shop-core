import { register } from 'ShopUI/app';
export default register('layout', () => import(/* webpackMode: "lazy" */'./layout'));

import { register } from 'ShopUI/app';
export default register('main-layout', () => import(/* webpackMode: "lazy" */'./layout'));

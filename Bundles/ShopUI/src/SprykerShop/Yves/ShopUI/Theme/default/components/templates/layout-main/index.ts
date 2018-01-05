import { register } from 'ShopUI/app';

export default register(
    'layout-main',
    () => import(/* webpackMode: "lazy" */'./layout-main')
);

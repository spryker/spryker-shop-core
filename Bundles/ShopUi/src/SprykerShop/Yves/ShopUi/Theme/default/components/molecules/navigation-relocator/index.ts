import register from 'ShopUi/app/registry';
export default register('navigation-relocator', () => import(/* webpackMode: "lazy" */'./navigation-relocator'));

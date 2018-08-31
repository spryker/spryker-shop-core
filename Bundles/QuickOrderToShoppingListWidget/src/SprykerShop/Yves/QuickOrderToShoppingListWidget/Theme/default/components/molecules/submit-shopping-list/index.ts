import register from 'ShopUi/app/registry';
export default register('submit-shopping-list', () => import(/* webpackMode: "lazy" */'./submit-shopping-list'));

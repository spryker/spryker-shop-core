import register from 'ShopUi/app/registry';
export default register('query-creator', () => import(/* webpackMode: "lazy" */'./query-creator'));

import register from 'ShopUi/app/registry';
export default register('measurement-unit', () => import(/* webpackMode: "lazy" */'./measurement-unit'));

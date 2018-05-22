import register from 'ShopUi/app/registry';

export default register('customer-reorder', () => import(/* webpackMode: "lazy" */'./customer-reorder'));

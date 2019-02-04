import register from 'ShopUi/app/registry';

export default register('customer-reorder-form', () => import(/* webpackMode: "lazy" */'./customer-reorder-form'));

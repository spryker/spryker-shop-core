import register from 'ui-shop/app/registry';

export default register('customer-reorder', () => import(/* webpackMode: "lazy" */'./customer-reorder'));

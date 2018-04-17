import register from 'shop-ui/app/registry';

export default register('customer-reorder', () => import(/* webpackMode: "lazy" */'./customer-reorder'));

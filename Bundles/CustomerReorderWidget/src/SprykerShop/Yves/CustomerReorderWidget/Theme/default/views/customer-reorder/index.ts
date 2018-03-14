import { register } from 'shop-ui/app';
export default register('customer-reorder', () => import(/* webpackMode: "lazy" */'./customer-reorder'));

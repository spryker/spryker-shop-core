import register from 'shop-ui/app/registry';
export default register('quick-order-form', () => import(/* webpackMode: "lazy" */'./quick-order-form'));

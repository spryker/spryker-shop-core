import register from 'ShopUi/app/registry';
export default register('order-item-price', () => import(/* webpackMode: "lazy" */'./order-item-price'));

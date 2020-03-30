import register from 'ShopUi/app/registry';
export default register('product-set-cms-content', () => import(/* webpackMode: "lazy" */'../product-set-details/product-set-details'));

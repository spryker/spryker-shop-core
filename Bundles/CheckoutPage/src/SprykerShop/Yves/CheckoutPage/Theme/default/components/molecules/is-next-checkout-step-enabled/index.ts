import register from 'ShopUi/app/registry';
export default register('is-next-checkout-step-enabled', () => import(/* webpackMode: "lazy" */'./is-next-checkout-step-enabled'));

import register from 'ShopUi/app/registry';
export default register('merchant-selector', () => import(/* webpackMode: "eager" */'./merchant-selector'));

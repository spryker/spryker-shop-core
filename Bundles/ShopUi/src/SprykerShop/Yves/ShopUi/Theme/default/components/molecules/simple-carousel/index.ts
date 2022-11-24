import './style.scss';
import register from 'ShopUi/app/registry';
export default register(
    'simple-carousel',
    () =>
        import(
            /* webpackMode: "lazy" */
            /* webpackChunkName: "simple-carousel" */
            './simple-carousel'
        ),
);

import './style.scss';
import register from 'ShopUi/app/registry';
export default register(
    'color-selector',
    () =>
        import(
            /* webpackMode: "lazy" */
            /* webpackChunkName: "color-selector" */
            './color-selector'
        ),
);

import './style.scss';
import register from 'ShopUi/app/registry';
export default register(
    'label-group',
    () =>
        import(
            /* webpackMode: "lazy" */
            /* webpackChunkName: "label-group" */
            './label-group'
        ),
);

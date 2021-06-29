import './style.scss';
import register from 'ShopUi/app/registry';
export default register('side-drawer', () =>
    import(
        /* webpackMode: "lazy" */
        /* webpackChunkName: "side-drawer" */
        './side-drawer'
    ),
);

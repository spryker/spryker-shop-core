import './styles.scss';
import register from 'ShopUi/app/registry';
export default register('collapsible-list', () =>
    import(
        /* webpackMode: "lazy" */
        /* webpackChunkName: "collapsible-list" */
        './collapsible-list'
    ),
);

import './style.scss';
import register from 'ShopUi/app/registry';
export default register(
    'service-point-finder',
    () =>
        import(
            /* webpackMode: "lazy" */
            /* webpackChunkName: "service-point-finder" */
            './service-point-finder'
        ),
);

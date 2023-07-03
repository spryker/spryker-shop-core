import './style.scss';
import register from 'ShopUi/app/registry';
export default register(
    'service-point-finder',
    () =>
        import(
            /* webpackMode: "lazy" */
            './service-point-finder'
        ),
);

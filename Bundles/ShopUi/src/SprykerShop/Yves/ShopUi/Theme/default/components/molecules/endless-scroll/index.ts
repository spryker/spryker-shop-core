import './style.scss';
import register from 'ShopUi/app/registry';
export default register(
    'endless-scroll',
    () =>
        import(
            /* webpackMode: "lazy" */
            /* webpackChunkName: "endless-scroll" */
            './endless-scroll'
        ),
);

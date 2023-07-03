import './style.scss';
import register from 'ShopUi/app/registry';
export default register(
    'endless-scroll',
    () =>
        import(
            /* webpackMode: "lazy" */
            './endless-scroll'
        ),
);

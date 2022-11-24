import './style.scss';
import register from 'ShopUi/app/registry';
export default register(
    'password-field',
    () =>
        import(
            /* webpackMode: "lazy" */
            /* webpackChunkName: "password-field" */
            './password-field'
        ),
);

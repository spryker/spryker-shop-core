import './style.scss';
import register from 'ShopUi/app/registry';
export default register(
    'input-dropzone',
    () =>
        import(
            /* webpackMode: "lazy" */
            /* webpackChunkName: "input-dropzone" */
            './input-dropzone'
        ),
);

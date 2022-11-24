import './style.scss';
import register from 'ShopUi/app/registry';
export default register(
    'image-carousel',
    () =>
        import(
            /* webpackMode: "lazy" */
            /* webpackChunkName: "image-carousel" */
            './image-carousel'
        ),
);

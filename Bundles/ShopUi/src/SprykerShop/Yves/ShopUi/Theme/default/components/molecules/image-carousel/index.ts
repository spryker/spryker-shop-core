import register from '../../../app/registry';
export default register('image-carousel', () => import(/* webpackMode: "lazy" */'./image-carousel'));

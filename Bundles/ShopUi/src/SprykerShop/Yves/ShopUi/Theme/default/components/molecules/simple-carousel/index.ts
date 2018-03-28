import './style';
import register from '../../../app/registry';
export default register('simple-carousel', () => import(/* webpackMode: "lazy" */'./simple-carousel'));

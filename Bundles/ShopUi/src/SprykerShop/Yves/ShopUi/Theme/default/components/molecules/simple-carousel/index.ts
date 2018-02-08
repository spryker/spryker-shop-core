import './style';
import { register } from '../../../app';
export default register('simple-carousel', () => import(/* webpackMode: "lazy" */'./simple-carousel'));

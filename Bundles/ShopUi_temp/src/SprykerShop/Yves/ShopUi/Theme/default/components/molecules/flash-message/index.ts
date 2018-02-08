import './style';
import { register } from '../../../app';
export default register('flash-message', () => import(/* webpackMode: "lazy" */'./flash-message'));

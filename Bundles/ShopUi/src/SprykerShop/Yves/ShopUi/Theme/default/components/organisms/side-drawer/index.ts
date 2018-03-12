import './style';
import { register } from '../../../app';
export default register('side-drawer', () => import(/* webpackMode: "eager" */'./side-drawer'));

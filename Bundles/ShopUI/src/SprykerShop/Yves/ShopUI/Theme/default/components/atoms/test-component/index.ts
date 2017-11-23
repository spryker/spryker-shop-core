import './style';
import { register } from '../../../app';
export default register('test-component', () => import(/* webpackMode: "lazy" */'./test-component'));

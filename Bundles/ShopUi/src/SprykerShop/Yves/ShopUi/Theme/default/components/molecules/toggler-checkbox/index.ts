import './style';
import { register } from '../../../app';
export default register('toggler-checkbox', () => import(/* webpackMode: "lazy" */'./toggler-checkbox'));

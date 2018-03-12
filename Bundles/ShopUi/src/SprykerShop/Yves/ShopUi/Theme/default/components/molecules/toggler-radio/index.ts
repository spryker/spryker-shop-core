import './style';
import { register } from '../../../app';
export default register('toggler-radio', () => import(/* webpackMode: "lazy" */'./toggler-radio'));

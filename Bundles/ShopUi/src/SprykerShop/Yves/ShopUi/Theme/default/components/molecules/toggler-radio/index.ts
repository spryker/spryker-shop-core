import './style';
import register from '../../../app/registry';
export default register('toggler-radio', () => import(/* webpackMode: "lazy" */'./toggler-radio'));

import './style';
import register from '../../../app/registry';
export default register('toggler-checkbox', () => import(/* webpackMode: "lazy" */'./toggler-checkbox'));

import './style';
import register from '../../../app/registry';
export default register('side-drawer', () => import(/* webpackMode: "eager" */'./side-drawer'));

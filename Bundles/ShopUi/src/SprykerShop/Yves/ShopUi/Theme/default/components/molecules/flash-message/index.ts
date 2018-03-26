import './style';
import register from '../../../app/registry';
export default register('flash-message', () => import(/* webpackMode: "lazy" */'./flash-message'));

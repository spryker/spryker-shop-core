import './style';
import register from '../../../app/registry';
export default register('ajax-loader', () => import(/* webpackMode: "eager" */'./ajax-loader'));

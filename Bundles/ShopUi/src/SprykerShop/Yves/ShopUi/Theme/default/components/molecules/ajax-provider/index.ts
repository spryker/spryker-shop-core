import register from '../../../app/registry';
export default register('ajax-provider', () => import(/* webpackMode: "eager" */'./ajax-provider'));

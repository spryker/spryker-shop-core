import register from '../../../app/registry';
export default register('ajax-renderer', () => import(/* webpackMode: "lazy" */'./ajax-renderer'));

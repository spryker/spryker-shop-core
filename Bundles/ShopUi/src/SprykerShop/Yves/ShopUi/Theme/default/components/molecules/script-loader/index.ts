import register from '../../../app/registry';
export default register('script-loader', () => import(/* webpackMode: "lazy" */'./script-loader'));

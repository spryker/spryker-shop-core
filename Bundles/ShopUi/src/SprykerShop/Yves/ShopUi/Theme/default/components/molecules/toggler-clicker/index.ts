import register from '../../../app/registry';
export default register('toggler-clicker', () => import(/* webpackMode: "lazy" */'./toggler-clicker'));

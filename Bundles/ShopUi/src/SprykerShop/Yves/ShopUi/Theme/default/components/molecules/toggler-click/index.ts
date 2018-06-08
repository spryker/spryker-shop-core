import register from '../../../app/registry';
export default register('toggler-click', () => import(/* webpackMode: "lazy" */'./toggler-click'));

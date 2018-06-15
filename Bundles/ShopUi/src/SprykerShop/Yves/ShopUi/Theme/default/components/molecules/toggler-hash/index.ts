import register from '../../../app/registry';
export default register('toggler-hash', () => import(/* webpackMode: "lazy" */'./toggler-hash'));

import { register } from '../../../app';
export default register('class-toggler-selector', () => import(/* webpackMode: "lazy" */'./class-toggler-selector'));

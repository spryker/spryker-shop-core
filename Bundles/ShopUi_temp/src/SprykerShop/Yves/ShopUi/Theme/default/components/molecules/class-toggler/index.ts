import { register } from '../../../app';
export default register('class-toggler', () => import(/* webpackMode: "eager" */'./class-toggler'));

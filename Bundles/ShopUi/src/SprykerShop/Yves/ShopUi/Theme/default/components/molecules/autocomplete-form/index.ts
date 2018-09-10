import './style';

import register from '../../../app/registry';
export default register('autocomplete-form', () => import(/* webpackMode: "lazy" */'./autocomplete-form'));

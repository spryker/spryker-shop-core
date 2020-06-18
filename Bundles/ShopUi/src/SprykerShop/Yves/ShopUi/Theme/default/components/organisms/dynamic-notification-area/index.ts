import register from '../../../app/registry';
export default register('dynamic-notification-area', () => import(/* webpackMode: "eager" */'./dynamic-notification-area'));

import register from '../../../app/registry';
export default register('viewport-intersection-observer', () =>
    import(/* webpackMode: "eager" */'./viewport-intersection-observer'));

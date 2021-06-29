import register from 'ShopUi/app/registry';
export default register('comment-tag-form', () =>
    import(
        /* webpackMode: "lazy" */
        /* webpackChunkName: "comment-tag-form" */
        './comment-tag-form'
    ),
);

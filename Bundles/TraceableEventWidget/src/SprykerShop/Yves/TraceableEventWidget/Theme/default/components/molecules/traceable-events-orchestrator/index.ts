import register from 'ShopUi/app/registry';
export default register(
    'traceable-events-orchestrator',
    () =>
        import(
            /* webpackMode: "lazy" */
            /* webpackChunkName: "traceable-events-orchestrator" */
            './traceable-events-orchestrator'
        ),
);

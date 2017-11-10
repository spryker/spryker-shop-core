import '@webcomponents/custom-elements';

export interface IAppComponentModuleImport {
    (): Promise<{ default: any }>
}

export interface IAppComponentRegistry {
    [key: string]: IAppComponentModuleImport;
}

export interface IAppComponent {
    tag: string,
    logic: any
}

const componentRegistry: IAppComponentRegistry = {};

async function setup(): Promise<void> { 
    const event = new CustomEvent('app.setup');
    document.dispatchEvent(event);
}

async function importComponent(tag): Promise<IAppComponent> {
    const tags = document.getElementsByTagName(tag);
    const component: IAppComponent = {
        tag,
        logic: null
    };

    if (tags.length > 0) {
        const importComponentModule = componentRegistry[tag];
        const componentModule = await importComponentModule();
        component.logic = componentModule.default;
    }

    return component;
}

async function importComponents(): Promise<IAppComponent[]> {
    return Promise.all(Object.keys(componentRegistry).map(importComponent));
}

async function defineComponents(components: IAppComponent[]): Promise<void> {
    components.forEach(component => {
        if (!component.logic) { 
            return;
        }

        const options = {
            extends: component.logic.extends
        };

        customElements.define(component.tag, component.logic, options);
    });
}

function ready(): void {
    const event = new CustomEvent('app.ready');
    document.dispatchEvent(event);
}

function fail(error) { 
    const event = new CustomEvent('app.error', { detail: error });
    document.dispatchEvent(event);

    console.error('UI: bootstrap failed\n', error);
}

export function define(tag: string, importComponentModule: IAppComponentModuleImport): IAppComponentModuleImport {
    componentRegistry[tag] = importComponentModule;
    return importComponentModule;
}

export function bootstrap(): void {
    setup()
        .then(importComponents)
        .then(defineComponents)
        .then(ready)
        .catch(fail);
}

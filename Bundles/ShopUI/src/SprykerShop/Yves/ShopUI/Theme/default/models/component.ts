import config from '../app.config';

export interface IComponentContructor {
    new(...args: any[]): HTMLElement
}

export interface IComponentImporter {
    (): Promise<{ default: IComponentContructor }>
}

export const ComponentMixin = (SuperClass: IComponentContructor) => class extends SuperClass {
    readonly selector: string

    constructor(...args: any[]) { 
        super(...args);
        this.selector = `js-${this.tagName.toLowerCase()}`;
        document.addEventListener(config.events.ready, this.ready.bind(this), false);
    }

    ready(): void { }

    setAttributeSafe(name: string, value?: string): void { 
        if (!value) {
            return this.removeAttribute(name);
        }

        this.setAttribute(name, value);
    }

    getAttributeSafe(name: string): string {
        if (this.hasAttribute(name)) {
            return this.getAttribute(name);
        }

        return '';
    }

    setPropertySafe(name: string, value?: boolean): void {
        if (!value) {
            return this.removeAttribute(name);
        }

        this.setAttribute(name, '');
    }

    getPropertySafe(name: string): boolean {
        return this.hasAttribute(name);
    }
}

export default class Component extends ComponentMixin(HTMLElement) { }

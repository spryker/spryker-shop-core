import config from '../app.config';

export interface IComponentContructor {
    new(): HTMLElement
}

export interface IComponentImporter {
    (): Promise<{ default: IComponentContructor }>
}

export default abstract class Component extends HTMLElement { 
    readonly name: string
    readonly selector: string

    constructor() {
        super();

        this.name = this.tagName.toLowerCase();
        this.selector = `js-${this.name}`;

        document.addEventListener(config.events.ready, (event: Event) => this.ready(event), false);
    }

    abstract ready(event?: Event): void

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

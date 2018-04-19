import Logger from '../app/logger';
import { Config } from '../app/config';

export interface IComponentContructor {
    new(): Component
}

export interface IComponentImporter {
    (): Promise<{ default: IComponentContructor }>
}

export default abstract class Component extends HTMLElement {
    readonly componentName: string
    readonly componentSelector: string
    protected logger: Logger
    protected config: Config

    constructor() {
        super();
        this.componentName = this.tagName.toLowerCase();
        this.componentSelector = `js-${this.componentName}`;
    }

    mountCallback(config: Config, logger: Logger): void {
        this.config = config;
        this.logger = logger;
    }

    abstract readyCallback(): void

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

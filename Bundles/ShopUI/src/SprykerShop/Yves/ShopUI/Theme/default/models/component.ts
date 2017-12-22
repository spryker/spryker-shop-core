import config from '../app.config';

export interface IComponentContructor {
    new(...args: any[]): HTMLElement
}

export interface IComponentImporter {
    (): Promise<{ default: IComponentContructor }>
}

export const ComponentMixin = (SuperClass: IComponentContructor) => class extends SuperClass {

    constructor(...args: any[]) { 
        super(...args);
        document.addEventListener(config.events.ready, this.ready.bind(this), false);
    }

    ready(): void { }

    findOne<T extends HTMLElement>(selector: string): T {
        return this.querySelector(selector);
    }

    findAll<T extends HTMLElement>(selector: string): T[] {
        return Array.prototype.slice.call(this.querySelectorAll(selector));
    }

}

export default class Component extends ComponentMixin(HTMLElement) { }

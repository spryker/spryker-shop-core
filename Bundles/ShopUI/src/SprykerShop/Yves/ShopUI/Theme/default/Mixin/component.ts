export interface IComponent extends Element {
    readonly isComponent: boolean
    find(selector: string): Element[]
}

export type ComponentConstructor<T=Element> = new (...args: any[]) => T;

const ComponentMixin = (SuperClass: ComponentConstructor, elementToExtend: string = null) => class extends SuperClass implements IComponent {
    readonly isComponent: boolean = true

    find(selector: string): Element[] {
        return Array.prototype.slice.call(this.querySelectorAll(selector));
    }

    static get extends(): string { 
        return elementToExtend;
    }
}

export default ComponentMixin;

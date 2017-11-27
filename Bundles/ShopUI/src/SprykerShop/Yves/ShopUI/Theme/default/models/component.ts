export interface IComponentContructor {
    new(selector: string, element: Element): Component
}

export interface IComponentImporter {
    (): Promise<{ default: IComponentContructor }>
}

export default abstract class Component { 
    readonly selector: string
    readonly element: Element
    
    constructor(selector: string, element: Element) { 
        this.selector = selector;
        this.element = element;
        this.mount();
    }

    mount(): void {
    }

    abstract init(): void

    ready(): void {
    }

    findOne(selector: string): Element {
        return Array.prototype.slice.call(this.element.querySelector(selector));
    }

    findAll(selector: string): Element[] {
        return Array.prototype.slice.call(this.element.querySelectorAll(selector));
    }
}

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

    find(selector: string): Element[] {
        return Array.prototype.slice.call(this.element.querySelectorAll(selector));
    }
}

export interface IComponentContructor {
    new(...args: any[]): Element
}

export interface IComponentImporter {
    (): Promise<{ default: IComponentContructor }>
}

const ComponentMixin = (SuperClass: IComponentContructor) => class extends SuperClass {

    findOne(selector: string): Element {
        return Array.prototype.slice.call(this.querySelector(selector));
    }

    findAll(selector: string): Element[] {
        return Array.prototype.slice.call(this.querySelectorAll(selector));
    }

}

export default ComponentMixin;

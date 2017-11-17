import Component, { IComponentContructor, IComponentImporter } from '../models/component';

export default class Candidate {
    readonly selector: string
    readonly importer: IComponentImporter
    readonly elements: Element[]

    constructor(selector: string, importer: IComponentImporter) {
        this.selector = selector;
        this.importer = importer;
        this.elements = Array.prototype.slice.call(document.getElementsByClassName(selector));
    }

    async mountComponents(): Promise<Component[]> {
        const componentModule = await this.importer();
        const componentConstructor = componentModule.default;
        return this.elements.map(element => new componentConstructor(this.selector, element));
    }

    get existsInDOM() {
        return this.elements.length > 0;
    }
}

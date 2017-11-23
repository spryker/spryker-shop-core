import Component, { IComponentContructor, IComponentImporter } from '../models/component';

export default class Candidate {
    readonly selector: string
    readonly importer: IComponentImporter

    constructor(selector: string, importer: IComponentImporter) {
        this.selector = selector;
        this.importer = importer;
    }

    async mountComponents(): Promise<Component[]> {
        const elements = Array.prototype.slice.call(document.getElementsByClassName(this.selector));

        if (elements.length === 0) { 
            return [];
        } 

        const componentModule = await this.importer();
        const componentConstructor = componentModule.default;
        return elements.map(element => new componentConstructor(this.selector, element));
    }
}

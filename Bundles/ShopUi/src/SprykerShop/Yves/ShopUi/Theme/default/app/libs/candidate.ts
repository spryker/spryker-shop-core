import { IComponentImporter, IComponentContructor } from '../../models/component';

export interface ICandidateMountStats { 
    [key: string]: number
}

export default class Candidate {
    readonly tag: string
    readonly importer: IComponentImporter
    readonly count: number

    constructor(tag: string, importer: IComponentImporter) {
        this.tag = tag;
        this.importer = importer;
        this.count = document.getElementsByTagName(tag).length;
    }

    async mount(): Promise<void> {
        if (this.count === 0) { 
            return;
        } 

        const componentModule = await this.importer();
        const componentConstructor = <IComponentContructor>componentModule.default;

        customElements.define(this.tag, componentConstructor);
        return customElements.whenDefined(this.tag);
    }
}

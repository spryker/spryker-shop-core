import { log, error } from '../app/logger';
import { IComponentImporter, IComponentContructor } from '../models/component';

export default class Candidate {
    protected readonly name: string
    protected readonly importer: IComponentImporter

    constructor(name: string, importer: IComponentImporter) {
        this.name = name;
        this.importer = importer;
    }

    async mount(): Promise<void> {
        const elements = Array.from(document.getElementsByTagName(this.name));

        if (elements.length === 0) {
            return;
        }

        log('mounting', elements.length, this.name);

        try {
            const componentModule = await this.importer();
            const componentConstructor = <IComponentContructor>componentModule.default;

            customElements.define(this.name, componentConstructor);
            return customElements.whenDefined(this.name);
        } catch (err) {
            error(this.name, 'mounting aborted:', err);
        }
    }
}

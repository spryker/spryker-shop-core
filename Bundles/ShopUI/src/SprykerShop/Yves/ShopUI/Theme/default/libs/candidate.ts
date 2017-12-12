import Logger from './logger';
import Component, { IComponentImporter } from '../models/component';

export default class Candidate {
    readonly tag: string
    readonly importer: IComponentImporter
    private readonly logger: Logger

    constructor(tag: string, importer: IComponentImporter, logger: Logger) {
        this.tag = tag;
        this.importer = importer;
        this.logger = logger;
    }

    async mount(): Promise<number> {
        const elements = document.getElementsByTagName(this.tag);
        this.logger.debug('mounting', elements.length, this.tag, 'candidates');

        if (elements.length === 0) { 
            return 0;
        } 

        const componentModule = await this.importer();
        customElements.define(this.tag, componentModule.default);

        return elements.length;
    }
}

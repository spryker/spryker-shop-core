import Logger from '../app/logger';
import { Config } from '../app/config';
import Component, { IComponentImporter, IComponentContructor } from '../models/component';

export default class Candidate {
    protected readonly tagName: string
    protected readonly importer: IComponentImporter
    protected readonly elements: Element[]

    constructor(tagName: string, importer: IComponentImporter) {
        this.tagName = tagName;
        this.importer = importer;
        this.elements = Array.from(document.getElementsByTagName(tagName));
    }

    async mountComponents(config: Config, logger: Logger): Promise<Component[]> {
        if (this.elements.length === 0) {
            return [];
        }

        try {
            const componentModule = await this.importer();
            const componentConstructor = <IComponentContructor>componentModule.default;

            customElements.define(this.tagName, componentConstructor);
            await customElements.whenDefined(this.tagName);
            this.elements.forEach((component: Component) => component.mountCallback(config, logger));
            logger.log(this.elements.length, this.tagName, 'mounted');

            return <Component[]>this.elements;
        } catch (error) {
            logger.error(this.tagName, 'mounting aborted:', error);
            return [];
        }
    }
}

import Component from '../models/component';
import { CustomElementImporter, CustomElementContructor } from './registry';
import { debug } from '../app/logger';

export default class Candidate {
    protected readonly tagName: string
    protected readonly customElementImporter: CustomElementImporter

    constructor(tagName: string, customElementImporter: CustomElementImporter) {
        this.tagName = tagName;
        this.customElementImporter = customElementImporter;
    }

    async mount(): Promise<Component[]> {
        try {
            const elements = this.getElements();

            if (elements.length === 0) {
                return [];
            }

            if (this.isDefined) {
                return <Component[]>elements;
            }

            debug('mounting', elements.length, this.tagName);
            const customElementModule = await this.customElementImporter();
            const customElementConstructor = <CustomElementContructor>customElementModule.default;
            customElements.define(this.tagName, customElementConstructor);
            await customElements.whenDefined(this.tagName);

            return <Component[]>elements;
        } catch (err) {
            throw new Error(`${this.tagName} failed to mount\n${err.message}`);
        }
    }

    protected getElements(): HTMLElement[] {
        return <HTMLElement[]>Array.from(document.getElementsByTagName(this.tagName));
    }

    protected get isDefined(): boolean {
        const constructor = document.createElement(this.tagName).constructor;
        return constructor !== HTMLElement && constructor !== HTMLUnknownElement;
    }
}

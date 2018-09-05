import { CustomElementImporter, CustomElementContructor } from './registry';
import { debug } from '../app/logger';

export default class Candidate {
    protected readonly tagName: string
    protected readonly customeElementImporter: CustomElementImporter

    constructor(tagName: string, customeElementImporter: CustomElementImporter) {
        this.tagName = tagName;
        this.customeElementImporter = customeElementImporter;
    }

    async mount(): Promise<void> {
        if (this.isMounted) {
            return;
        }

        const tagCount = this.tagCount;

        if (tagCount === 0) {
            return;
        }

        debug('mounting', tagCount, this.tagName);

        try {
            const customElementModule = await this.customeElementImporter();
            const customElementConstructor = <CustomElementContructor>customElementModule.default;
            customElements.define(this.tagName, customElementConstructor);
            return customElements.whenDefined(this.tagName);
        } catch (err) {
            throw new Error(`${this.tagName} failed to mount\n${err.message}`);
        }
    }

    get isMounted(): boolean {
        const constructor = document.createElement(this.tagName).constructor;
        return constructor !== HTMLElement && constructor !== HTMLUnknownElement;
    }

    get tagCount(): number {
        return document.getElementsByTagName(this.tagName).length;
    }
}

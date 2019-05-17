import { CustomElementContructor, CustomElementModule, CustomElementImporter } from './registry';
import { debug } from '../app/logger';

/**
 * A candidate represents a to-be-defined Spryker component that has been registered.
 * It contains all the information required by the application to define and run a specific component in the DOM.
 */
export default class Candidate {
    protected readonly tagName: string;
    protected readonly customElementImporter: CustomElementImporter;
    protected isCustomElementDefined: boolean;

    /**
     * Creates an instance of Candidate.
     *
     * @param tagName HTML component tagname.
     * @param customElementImporter Function that executes webpack's import() to asyncronously retrieve the component
     * constructor.
     */
    constructor(tagName: string, customElementImporter: CustomElementImporter) {
        this.tagName = tagName;
        this.customElementImporter = customElementImporter;
        this.isCustomElementDefined = false;
    }

    /**
     * Defines the webcomponent on which the current candidate is based.
     * First, the function asyncronously retrieves the component constructor using webpack's import().
     * Then, tagName and contructor are used to define the component using customElements browser API.
     *
     * @returns A promise with all the defined elements as resolve() argument.
     */
    async define(): Promise<Element[]> {
        const elementCollection: HTMLCollectionOf<Element> = document.getElementsByTagName(this.tagName);

        if (elementCollection.length === 0) {
            return [];
        }

        const elements: Element[] = Array.from(elementCollection);

        if (this.isCustomElementDefined) {
            return elements;
        }

        try {
            debug('define', this.tagName, `(${elements.length})`);
            const customElementModule: CustomElementModule = await this.customElementImporter();
            const customElementConstructor: CustomElementContructor = customElementModule.default;
            customElements.define(this.tagName, customElementConstructor);
            await customElements.whenDefined(this.tagName);
        } catch (err) {
            throw new Error(`${this.tagName} failed to be defined\n${err.message}`);
        }

        this.isCustomElementDefined = true;

        return elements;
    }

    /**
     * Same as define().
     *
     * @deprecated Use define() instead.
     */
    async mount(): Promise<Element[]> {
        return this.define();
    }
}

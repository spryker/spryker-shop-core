import Candidate from './candidate';

/* tslint:disable: no-redundant-jsdoc */
/**
 * Defines a registry for all the webcomponents potentially used inside the application.
 *
 * @module Registry
 *
 * @remarks
 * Registry is used directly by the application to know which webcomponents are available and can be defined.
 */
/* tslint:enable */

const registry: Map<string, Candidate> = new Map();

/**
 * Defines the generic custom element contructor signature that must be exported by each webcomponent module.
 */
export interface CustomElementContructor {
    new(): Element;
}

/**
 * Defines the generic custom element module signature that must be implemented by each webcomponent module.
 */
export interface CustomElementModule {
    default: CustomElementContructor;
}

/**
 * Defines the generic custom element importer signature that must be implemented by each webcomponent importer
 * function.
 *
 * @remarks
 * This interface represents an incapsulation of webpack's import() function, as follows:
 *
 * ```
 * () => import('./component-module')
 * ```
 */
export interface CustomElementImporter {
    (): Promise<CustomElementModule>;
}

/**
 * Registers a new custom element to the application registry.
 *
 * @param tagName Custom element tag name.
 * @param customElementImporter Function used to import the webcomponent contructor.
 * @returns A candidate is returned.
 */
export default function register(tagName: string, customElementImporter: CustomElementImporter): Candidate {
    const candidate = new Candidate(tagName, customElementImporter);
    registry.set(tagName, candidate);

    return candidate;
}

/**
 * Unregisters an existing custom element from the application registry.
 *
 * @param tagName Custom element tag name to be removed.
 * @returns True if tagName was found and unregistration was successfull, false otherwise.
 */
export function unregister(tagName: string): boolean {
    return registry.delete(tagName);
}

/**
 * Gets the list of registered custom elements as a list of candidates.
 *
 * @returns A readonly list of candidates.
 */
export function get(): ReadonlyArray<Candidate> {
    return Array.from(registry.values());
}

/**
 * Same as get().
 *
 * @deprecated Use get() instead.
 */
export function candidates(): ReadonlyArray<Candidate> {
    return get();
}

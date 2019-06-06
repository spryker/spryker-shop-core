/**
 * A Component is an extension of an HTMLElement.
 * It is used in Spryker Shop as base class for every components.
 */
export default abstract class Component extends HTMLElement {
    private isComponentMounted: boolean;

    /**
     * The name of the component.
     */
    readonly name: string;

    /**
     * The js-safe name of the component.
     */
    readonly jsName: string;

    /**
     * Creates an instance of Component.
     */
    constructor() {
        super();
        this.name = this.tagName.toLowerCase();
        this.jsName = `js-${this.name}`;
        this.isComponentMounted = false;
    }

    /* tslint:disable: no-any */
    protected dispatchCustomEvent(name: string, detail: any = {}): void {
        const customEvent = new CustomEvent(name, { detail });
        this.dispatchEvent(customEvent);
    }
    /* tslint:enable */

    /**
     * Same as mountCallback().
     *
     * @deprecated Use init() instead.
     */
    protected abstract readyCallback(): void;

    /**
     * Invoked when DOM is loaded and every webcomponent in the page is defined.
     */
    protected init(): void {
        /* tslint:disable: deprecation */
        this.readyCallback();
        /* tslint:enable */
    }

    /**
     * Used by the application to mark the current component as mounted and avoid multiple initialisations.
     */
    markAsMounted(): void {
        this.isComponentMounted = true;
    }

    /**
     * Automatically invoked by the application when component has to be mounted.
     */
    mountCallback(): void {
        this.init();
    }

    /**
     * Gets if the component has beed mounted already.
     */
    get isMounted(): boolean {
        return this.isComponentMounted;
    }
}

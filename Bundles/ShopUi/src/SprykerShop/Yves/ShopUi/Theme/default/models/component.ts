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
        /* tslint:enable */
        const customEvent = new CustomEvent(name, { detail });
        this.dispatchEvent(customEvent);
    }

    /**
     * Same as mountCallback().
     *
     * @deprecated Use init() instead.
     */
    protected abstract readyCallback(): void;

    /**
     * Initialise the component.
     * It's invoked when DOM is completely loaded and every other webcomponent in the page has been defined.
     * @remarks
     * Use this method as initial point for your component, especially if you intend to query the DOM for
     * other webcomponents. If this is not needed, you can still use `connectedCallback()` instead for
     * a faster execution, as described by official documentation for WebComponents here:
     * {@link https://developer.mozilla.org/en-US/docs/Web/Web_Components/
     * Using_custom_elements#Using_the_lifecycle_callbacks}
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

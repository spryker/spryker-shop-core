/**
 * A Component is an extension of an HTMLElement.
 * It is used by Spryker to define its own components.
 */
export default abstract class Component extends HTMLElement {
    private isComponentMounted: boolean

    /**
     * The name of the component.
     */
    readonly name: string

    /**
     * The js-safe name of the component.
     */
    readonly jsName: string

    /**
     * Flag that indicated that this is a Spriker component
     */
    readonly isComponent: boolean

    /**
     * Creates an instance of Component.
     */
    constructor() {
        super();
        this.name = this.tagName.toLowerCase();
        this.jsName = `js-${this.name}`;
        this.isComponent = true;
        this.isComponentMounted = false;
    }

    protected dispatchCustomEvent(name: string, detail: any = {}): void {
        const customEvent = new CustomEvent(name, { detail });
        this.dispatchEvent(customEvent);
    }

    /**
     * Same as mountCallback().
     *
     * @deprecated Use mountCallback() instead.
     */
    protected abstract readyCallback(): void

    /**
     * Used by the application to mark the current component as mounted and avoid multiple initialisations.
     */
    markAsMounted(): void {
        this.isComponentMounted = true;
    }

    /**
     * Invoked when DOM is loaded and every webcomponent in the page is defined.
     * Use this method as initial point for your component.
     *
     * @remarks
     * As this method is invoked only when every webcomponent in the page is defined,
     * this allows the current component to safely query the DOM for other components and access their API.
     * If no DOM query towards other webcomponents is needed, you can use connectedCallback() for a faster execution,
     * as described by official documentation for Web Components:
     * {@link https://developer.mozilla.org/en-US/docs/Web/Web_Components/Using_custom_elements#Using_the_lifecycle_callbacks}
     */
    mountCallback(): void {
        this.readyCallback();
    }

    /**
     * Gets if the component has beed mounted already.
     */
    get isMounted(): boolean {
        return this.isComponentMounted;
    }
}

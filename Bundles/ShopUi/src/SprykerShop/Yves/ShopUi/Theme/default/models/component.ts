import { get as config } from '../app/config';

/**
 * @event customEvent custom event
 */
export default abstract class Component extends HTMLElement {
    readonly name: string
    readonly jsName: string
    private isComponentMounted: boolean

    constructor() {
        super();
        this.name = this.tagName.toLowerCase();
        this.jsName = `js-${this.name}`;
        this.isComponentMounted = false;
    }

    protected dispatchCustomEvent(name: string, detail: any = {}): void {
        const customEvent = new CustomEvent(name, { detail });
        this.dispatchEvent(customEvent);
    }

    /**
     * Marks the component as mounted
     */
    markAsMounted(): void {
        this.isComponentMounted = true;
    }

    /**
     * Invokes the readyCallback method
     */
    mountCallback(): void {
        this.readyCallback();
    }

    protected abstract readyCallback(): void

    /**
     * Gets if the component is mounted
     */
    get isMounted(): boolean {
        return this.isComponentMounted;
    }
}

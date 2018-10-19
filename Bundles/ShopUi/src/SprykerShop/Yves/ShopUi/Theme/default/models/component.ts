import { get as config } from '../app/config';

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
        debugger;
        const customEvent = new CustomEvent(name, { detail });
        this.dispatchEvent(customEvent);
    }

    markAsMounted(): void {
        this.isComponentMounted = true;
    }

    mountCallback(): void {
        this.readyCallback();
    }

    protected abstract readyCallback(): void

    get isMounted(): boolean {
        return this.isComponentMounted;
    }
}

import Component from '../../../models/component';

export default class TogglerHash extends Component {
    readonly targets: HTMLElement[]

    constructor() {
        super();
        this.targets = <HTMLElement[]>Array.from(document.querySelectorAll(this.targetSelector));
    }

    protected readyCallback(): void {
        this.checkHash();
        this.mapEvents();
    }

    protected mapEvents(): void {
        window.addEventListener('hashchange', (event: Event) => this.onHashChange(event));
    }

    protected onHashChange(event: Event): void {
        this.checkHash();
    }

    /**
     * Performs checking the hash and invoke the flexible toogle action
     */
    checkHash(): void {
        if (this.triggerHash === this.hash) {
            this.toggle(this.addClassWhenHashInUrl);
            return;
        }

        this.toggle(!this.addClassWhenHashInUrl);
    }

    /**
     * Perfoms toggling of the class names
     * @param addClass boolean value for more flexible toggling action
     */
    toggle(addClass: boolean): void {
        this.targets.forEach((target: HTMLElement) => target.classList.toggle(this.classToToggle, addClass));
    }

    /**
     * Gets the current page url
     */
    get hash(): string {
        return window.location.hash;
    }

    /**
     * Gets the trigger hash
     */
    get triggerHash(): string {
        return this.getAttribute('trigger-hash');
    }

    /**
     * Gets a querySelector of the target element
     */
    get targetSelector(): string {
        return this.getAttribute('target-selector');
    }

    /**
     * Gets a class name for toggle action
     */
    get classToToggle(): string {
        return this.getAttribute('class-to-toggle');
    }

    /**
     * Gets if the element should add the class when in blur
     */
    get addClassWhenHashInUrl(): boolean {
        return this.hasAttribute('add-class-when-hash-in-url');
    }
}

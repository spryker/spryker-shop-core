import Component from '../../../models/component';

export default class TogglerHash extends Component {
    /**
     * Elements targeted by the toggle action.
     */
    readonly targets: HTMLElement[];

    constructor() {
        super();
        /* tslint:disable: deprecation */
        this.targets = <HTMLElement[]>Array.from(this.targetClassName ?
            document.getElementsByClassName(this.targetClassName) : document.querySelectorAll(this.targetSelector));
        /* tslint:enable: deprecation */
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
     * Checks the hash and triggers the flexible toggle action.
     */
    checkHash(): void {
        if (this.triggerHash === this.hash) {
            this.toggle(this.addClassWhenHashInUrl);

            return;
        }

        this.toggle(!this.addClassWhenHashInUrl);
    }

    /**
     * Toggles the class names in the target elements.
     * @param addClass A boolean value for a more flexible toggling action.
     */
    toggle(addClass: boolean): void {
        this.targets.forEach((target: HTMLElement) => target.classList.toggle(this.classToToggle, addClass));
    }

    /**
     * Gets the current page url.
     */
    get hash(): string {
        return window.location.hash;
    }

    /**
     * Gets the trigger hash.
     */
    get triggerHash(): string {
        return this.getAttribute('trigger-hash');
    }

    /**
     * Gets a querySelector of the target element.
     *
     * @deprecated Use targetClassName() instead.
     */
    get targetSelector(): string {
        return this.getAttribute('target-selector');
    }
    protected get targetClassName(): string {
        return this.getAttribute('target-class-name');
    }

    /**
     * Gets a class name for the toggle action.
     */
    get classToToggle(): string {
        return this.getAttribute('class-to-toggle');
    }

    /**
     * Gets if the element should add the class when in blur.
     */
    get addClassWhenHashInUrl(): boolean {
        return this.hasAttribute('add-class-when-hash-in-url');
    }
}

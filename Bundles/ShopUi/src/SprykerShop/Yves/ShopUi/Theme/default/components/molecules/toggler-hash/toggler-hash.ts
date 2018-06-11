import Component from '../../../models/component';

export default class TogglerHash extends Component {
    readonly targets: HTMLElement[]

    constructor() {
        super();
        this.targets = <HTMLElement[]>Array.from(document.querySelectorAll(this.targetSelector));
    }

    readyCallback(): void {
        this.checkHash();
        this.mapEvents();
    }

    mapEvents(): void {
        window.addEventListener('hashchange', (event: Event) => this.onHashChange(event));
    }

    onHashChange(event: Event): void {
        this.checkHash();
    }

    checkHash() {
        if (this.triggerHash === this.hash) {
            this.toggle(this.addClassWhenHashInUrl);
            return;
        }

        this.toggle(!this.addClassWhenHashInUrl);
    }

    toggle(addClass: boolean): void {
        this.targets.forEach((target: HTMLElement) => target.classList.toggle(this.classToToggle, addClass));
    }

    get hash(): string {
        return window.location.hash;
    }

    get triggerHash(): string {
        return this.getAttribute('trigger-hash');
    }

    get targetSelector(): string {
        return this.getAttribute('target-selector');
    }

    get classToToggle(): string {
        return this.getAttribute('class-to-toggle');
    }

    get addClassWhenHashInUrl(): boolean {
        return this.hasAttribute('add-class-when-hash-in-url');
    }
}

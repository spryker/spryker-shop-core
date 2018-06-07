import Component from '../../../models/component';

export default class TogglerClicker extends Component {
    readonly triggers: HTMLElement[]
    readonly targets: HTMLElement[]

    constructor() {
        super();
        this.triggers = <HTMLElement[]>Array.from(document.querySelectorAll(this.triggerSelector));
        this.targets = <HTMLElement[]>Array.from(document.querySelectorAll(this.targetSelector));
    }

    readyCallback(): void {
        this.mapEvents();
    }

    mapEvents(): void {
        this.triggers.forEach((trigger: HTMLElement) => trigger.addEventListener('click', (event: Event) => this.onTriggerClick(event)));
    }

    onTriggerClick(event: Event): void {
        event.preventDefault();
        this.toggle();
    }

    toggle(): void {
        this.targets.forEach((target: HTMLElement) => {
            const addClass = !target.classList.contains(this.classToToggle);
            target.classList.toggle(this.classToToggle, addClass);
        });
    }

    get triggerSelector(): string {
        return this.getAttribute('trigger-selector');
    }

    get targetSelector(): string {
        return this.getAttribute('target-selector');
    }

    get classToToggle(): string {
        return this.getAttribute('class-to-toggle');
    }
}

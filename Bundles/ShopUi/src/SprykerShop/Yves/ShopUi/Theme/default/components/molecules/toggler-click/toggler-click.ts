import Component from '../../../models/component';

export default class TogglerClick extends Component {
    readonly triggers: HTMLElement[]
    readonly targets: HTMLElement[]

    constructor() {
        super();
        this.triggers = <HTMLElement[]>Array.from(document.querySelectorAll(this.triggerSelector));
        this.targets = <HTMLElement[]>Array.from(document.querySelectorAll(this.targetSelector));
    }

    protected readyCallback(): void {
        this.mapEvents();
    }

    protected mapEvents(): void {
        this.triggers.forEach((trigger: HTMLElement) => trigger.addEventListener('click', (event: Event) => this.onTriggerClick(event)));
    }

    protected onTriggerClick(event: Event): void {
        event.preventDefault();
        this.toggle();
    }

    /**
     * Perfoms toggling of the class names
     */
    toggle(): void {
        this.targets.forEach((target: HTMLElement) => {
            const addClass = !target.classList.contains(this.classToToggle);
            target.classList.toggle(this.classToToggle, addClass);
        });
    }

    /**
     * Gets a querySelector of the trigger element
     */
    get triggerSelector(): string {
        return this.getAttribute('trigger-selector');
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
}

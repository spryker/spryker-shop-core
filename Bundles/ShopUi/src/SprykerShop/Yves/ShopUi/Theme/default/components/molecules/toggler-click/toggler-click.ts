import Component from '../../../models/component';

export default class TogglerClick extends Component {
    /**
     * Elements triggering the toggle action.
     */
    readonly triggers: HTMLElement[];

    /**
     * Elements targeted by the toggle action.
     */
    readonly targets: HTMLElement[];

    constructor() {
        super();
        /* tslint:disable: deprecation */
        this.triggers = <HTMLElement[]>Array.from(this.triggerClassName ?
            document.getElementsByClassName(this.triggerClassName) : document.querySelectorAll(this.triggerSelector));
        this.targets = <HTMLElement[]>Array.from(this.targetClassName ?
            document.getElementsByClassName(this.targetClassName) : document.querySelectorAll(this.targetSelector));
        /* tslint:enable: deprecation */
    }

    protected readyCallback(): void {
        this.mapEvents();
    }

    protected mapEvents(): void {
        this.triggers.forEach((trigger: HTMLElement) => {
            trigger.addEventListener('click', (event: Event) => this.onTriggerClick(event));
        });
    }

    protected onTriggerClick(event: Event): void {
        event.preventDefault();
        this.toggle();
    }

    /**
     * Toggles the class names in the target elements.
     */
    toggle(): void {
        this.targets.forEach((target: HTMLElement) => {
            const addClass = !target.classList.contains(this.classToToggle);
            target.classList.toggle(this.classToToggle, addClass);
        });
    }

    /**
     * Gets a querySelector of the trigger element.
     *
     * @deprecated Use triggerClassName() instead.
     */
    get triggerSelector(): string {
        return this.getAttribute('trigger-selector');
    }
    protected get triggerClassName(): string {
        return this.getAttribute('trigger-class-name');
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
}

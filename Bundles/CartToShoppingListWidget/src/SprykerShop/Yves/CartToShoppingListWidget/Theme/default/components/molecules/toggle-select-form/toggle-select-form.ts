import Component from 'ShopUi/models/component';

export default class ToggleSelectForm extends Component {
    /**
     * Element triggering the toggle action.
     */
    readonly trigger: HTMLSelectElement;

    /**
     * Elements targeted by the toggle action.
     */
    readonly targets: HTMLElement[];

    constructor() {
        super();
        this.trigger = <HTMLSelectElement>this.querySelector('[data-select-trigger]');
        this.targets = <HTMLElement[]>Array.from(document.getElementsByClassName(this.target));
    }

    protected readyCallback(): void {
        this.toggle();
        this.mapEvents();
    }

    /**
     * Makes the trigger element a listener of the change event.
     * @remarks That visibility will be changed next release.
     */
    mapEvents(): void {
        this.trigger.addEventListener('change', (event: Event) => this.onTriggerClick(event));
    }

    /**
     * Disables the default behavior of the change event and invokes the toggle method.
     * @param event Event name.
     * @remarks That visibility will be changed next release.
     */
    onTriggerClick(event: Event): void {
        event.preventDefault();
        this.toggle();
    }

    /**
     * Adds or removes class names from the target elements.
     * @param addClass A value for checking if a class name is to be added or removed from the target.
     */
    toggle(addClass: boolean = this.addClass): void {
        this.targets.forEach((element: HTMLElement) => element.classList.toggle(this.classToToggle, addClass));
    }

    /**
     * Gets if an option in the select element has no empty value.
     */
    get addClass(): boolean {
        return this.trigger.value !== '';
    }

    /**
     * Gets a target class name.
     */
    get target(): string {
        return this.trigger.getAttribute('target');
    }

    /**
     * Gets a class name for the toggle action.
     */
    get classToToggle(): string {
        return this.trigger.getAttribute('class-to-toggle');
    }
}

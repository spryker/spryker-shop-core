import Component from 'ShopUi/models/component';

export default class ToggleSelectForm extends Component {
    readonly trigger: HTMLSelectElement
    readonly targets: HTMLElement[]

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
     * Makes the trigger element a listener of the change event
     */
    mapEvents(): void {
        this.trigger.addEventListener('change', (event: Event) => this.onTriggerClick(event));
    }

    /**
     * Disables a default behavior of the change event and invokes a toggle method
     * @param event event name
     */
    onTriggerClick(event: Event): void { 
        event.preventDefault();
        this.toggle();
    }

    /**
     * Performs add or remove class name for target elements
     * @param addClass value for checking add class name or remove it from the target
     */
    toggle(addClass: boolean = this.addClass): void {
        this.targets.forEach((element: HTMLElement) => element.classList.toggle(this.classToToggle, addClass));
    }

    /**
     * Gets if the option into select element has no empty value
     */
    get addClass(): boolean {
        return this.trigger.value !== '';
    }

    /**
     * Gets a target class name
     */
    get target(): string {
        return this.trigger.getAttribute('target');
    }

    /**
     * Gets a class name for toggle action
     */
    get classToToggle(): string {
        return this.trigger.getAttribute('class-to-toggle');
    }
}

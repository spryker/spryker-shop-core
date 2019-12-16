import Component from '../../../models/component';

/**
 * @event toggle An event which is triggered when the trigger element is changed.
 */
export default class TogglerCheckbox extends Component {
    /**
     * Element triggering the toggle action.
     */
    readonly trigger: HTMLInputElement;

    /**
     * Elements targeted by the toggle action.
     */
    readonly targets: HTMLElement[];
    protected event: CustomEvent;

    constructor() {
        super();
        this.trigger = <HTMLInputElement>this.getElementsByClassName(`${this.jsName}__trigger`)[0];
        /* tslint:disable: deprecation */
        this.targets = <HTMLElement[]>Array.from(this.targetClassName ?
            document.getElementsByClassName(this.targetClassName) : document.querySelectorAll(this.targetSelector));
        /* tslint:enable: deprecation */
    }

    protected readyCallback(): void {
        this.toggle();
        this.fireToggleEvent();
        this.mapEvents();
    }

    protected mapEvents(): void {
        this.trigger.addEventListener('change', (event: Event) => this.onTriggerClick(event));
    }

    protected onTriggerClick(event: Event): void {
        event.preventDefault();
        this.toggle();
        this.fireToggleEvent();
    }

    /**
     * Toggles the class names in the target elements.
     * @param addClass A boolean value which checks if the trigger is checked.
     */
    toggle(addClass: boolean = this.addClass): void {
        this.targets.forEach((element: HTMLElement) => element.classList.toggle(this.classToToggle, addClass));
    }

    /**
     *  Creates an instance of the custom toggle event and triggers it.
     */
    fireToggleEvent(): void {
        this.event = new CustomEvent('toggle');
        this.dispatchEvent(this.event);
    }

    /**
     * Gets if the trigger element is checked.
     */
    get addClass(): boolean {
        return this.addClassWhenChecked ? this.trigger.checked : !this.trigger.checked;
    }

    /**
     * Gets a querySelector of the target element.
     *
     * @deprecated Use targetClassName() instead.
     */
    get targetSelector(): string {
        return this.trigger.getAttribute('target-selector');
    }
    protected get targetClassName(): string {
        return this.trigger.getAttribute('target-class-name');
    }

    /**
     * Gets a class name for the toggle action.
     */
    get classToToggle(): string {
        return this.trigger.getAttribute('class-to-toggle');
    }

    /**
     * Gets if the element should add the class when checked.
     */
    get addClassWhenChecked(): boolean {
        return this.trigger.hasAttribute('add-class-when-checked');
    }
}

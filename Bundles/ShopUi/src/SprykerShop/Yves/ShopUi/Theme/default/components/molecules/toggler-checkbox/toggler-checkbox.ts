import Component from '../../../models/component';

/**
 * @event toggle Event emitted when trigger element is changed
 */
export default class TogglerCheckbox extends Component {
    readonly trigger: HTMLInputElement
    readonly targets: HTMLElement[]

    constructor() {
        super();
        this.trigger = <HTMLInputElement>this.querySelector(`.${this.jsName}__trigger`);
        this.targets = <HTMLElement[]>Array.from(document.querySelectorAll(this.targetSelector));
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
     * Perfoms toggling of the class names
     * @param addClass boolean value wich checks is the trigger checked
     */
    toggle(addClass: boolean = this.addClass): void {
        this.targets.forEach((element: HTMLElement) => element.classList.toggle(this.classToToggle, addClass));
    }

    /**
     *  Creates an instance of the custom toogle event and fires it
     */
    fireToggleEvent(): void {
        const event = new CustomEvent('toggle');
        this.dispatchEvent(event);
    }

    /**
     * Gets if the trigger element is checked
     */
    get addClass(): boolean {
        return this.addClassWhenChecked ? this.trigger.checked : !this.trigger.checked;
    }

    /**
     * Gets a querySelector of the target element
     */
    get targetSelector(): string {
        return this.trigger.getAttribute('target-selector');
    }

    /**
     * Gets a class name for toggle action
     */
    get classToToggle(): string {
        return this.trigger.getAttribute('class-to-toggle');
    }

    /**
     * Gets if the element should add the class when checked
     */
    get addClassWhenChecked(): boolean {
        return this.trigger.hasAttribute('add-class-when-checked');
    }
}

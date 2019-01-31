import Component from '../../../models/component';

/**
 * @event formFieldsClearAfter An event which is triggered after the form fields are cleared.
 */
export default class FormClear extends Component {
    /**
     * The current form.
     */
    form: HTMLElement;
    /**
     * Collection of the triggers elements.
     */
    triggers: HTMLElement[];
    /**
     * Collection of the form elemenets.
     */
    targets: HTMLElement[];
    /**
     * Collection of the targets elements which should be ignored while collection the filters.
     */
    ignoreElements: HTMLElement[];
    /**
     * Collection of the filter elements.
     */
    filterElements: HTMLElement[];
    /**
     * The custom event.
     */
    formFieldsClearAfter: CustomEvent;

    protected readyCallback(): void {
        const formElements = 'select, input[type="text"], input[type="hidden"], input[type="radio"], input[type="checkbox"]';

        this.triggers = <HTMLElement[]>Array.from(document.querySelectorAll(this.triggerSelector));
        this.form = <HTMLElement>document.querySelector(this.formSelector);
        this.targets = <HTMLElement[]>Array.from(this.form.querySelectorAll(formElements));
        this.ignoreElements = <HTMLElement[]>Array.from(this.form.querySelectorAll(this.ignoreSelector));
        this.filterElements = this.targets.filter((element) => !this.ignoreElements.includes(element));

        this.mapEvents();
    }

    protected mapEvents(): void {
        this.createCustomEvents();
        this.triggers.forEach((input) => {
            input.addEventListener('change', () => {
                this.onChange(input);
            });
        });
    }

    protected onChange(input: HTMLElement): void {
        const isChecked = (<HTMLInputElement>input).checked;
        if (isChecked) {
            this.clearFormValues();
        }
    }

    /**
     * Clears an array of the form elements and triggers the custom event formFieldsClearAfter.
     * @param element HTMLFormElement is the element for clear action.
     */
    clearFormValues(): void {
        this.filterElements.forEach((element: HTMLFormElement) => {
            this.clearFormField(element);
        });

        this.dispatchEvent(this.formFieldsClearAfter);
    }

    /**
     * Clears current form field.
     * @param element HTMLFormElement is the element for clear action.
     */
    clearFormField(element: HTMLFormElement): void {
        const inputType = element.type;
        const tagName = this.getTagName(element);

        if (tagName == "INPUT") {
            if (inputType == "text" || inputType == "hidden") {
                element.value = '';
            }
            if (inputType == "checkbox" || inputType == "radio") {
                element.checked = false;
            }
        }

        if (tagName == "SELECT") {
            element.selectedIndex = 0;
        }
    }

    /**
     * Gets a tag name of the current element.
     */
    getTagName(element: HTMLElement): string {
        return element.tagName.toUpperCase();
    }

    protected createCustomEvents(): void {
        this.formFieldsClearAfter = <CustomEvent>new CustomEvent('form-fields-clear-after');
    }

    /**
     * Gets a querySelector name of the form.
     */
    get formSelector(): string {
        return this.getAttribute('form-selector');
    }

    /**
     * Gets a querySelector name of the trigger element.
     */
    get triggerSelector(): string {
        return this.getAttribute('trigger-selector');
    }

    /**
     * Gets a querySelector name of the ignore element.
     */
    get ignoreSelector(): string {
        return this.getAttribute('ignore-selector');
    }
}

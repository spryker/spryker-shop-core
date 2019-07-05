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
        /* tslint:disable: deprecation */
        this.triggers = <HTMLElement[]>Array.from(this.triggerClassName ?
            document.getElementsByClassName(this.triggerClassName) : document.querySelectorAll(this.triggerSelector));
        this.form = <HTMLElement>(this.formClassName ?
            document.getElementsByClassName(this.formClassName)[0] : document.querySelector(this.formSelector));
        this.ignoreElements = <HTMLElement[]>Array.from(this.ignoreClassName ?
            this.form.getElementsByClassName(this.ignoreClassName) : this.form.querySelectorAll(this.ignoreSelector));
        /* tslint:enable: deprecation */
        const formInputs = <HTMLElement[]>Array.from(this.form.getElementsByTagName('input'));
        const formSelects = <HTMLElement[]>Array.from(this.form.getElementsByTagName('select'));
        this.targets = [...formInputs, ...formSelects];
        this.filterElements = this.targets.filter(element => !this.ignoreElements.includes(element));

        this.mapEvents();
    }

    protected mapEvents(): void {
        this.createCustomEvents();
        this.triggers.forEach(input => {
            input.addEventListener('change', () => this.onChange(input));
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
        const tagName = this.getTagName(element);
        if (tagName === 'INPUT') {
            const inputType = element.type;

            if (inputType === 'text' || inputType === 'hidden') {
                element.value = '';
            }
            if (inputType === 'checkbox' || inputType === 'radio') {
                element.checked = false;
            }
        }

        if (tagName === 'SELECT') {
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
     *
     * @deprecated Use formClassName() instead.
     */
    get formSelector(): string {
        return this.getAttribute('form-selector');
    }
    protected get formClassName(): string {
        return this.getAttribute('form-class-name');
    }

    /**
     * Gets a querySelector name of the trigger element.
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
     * Gets a querySelector name of the ignore element.
     *
     * @deprecated Use ignoreClassName() instead.
     */
    get ignoreSelector(): string {
        return this.getAttribute('ignore-selector');
    }
    protected get ignoreClassName(): string {
        return this.getAttribute('ignore-class-name');
    }
}

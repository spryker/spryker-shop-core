import Component from '../../../models/component';

export default class FormInputDefaultValueDisabler extends Component {
    /**
     * Collection of the forms.
     */
    forms: HTMLFormElement[];

    protected readyCallback(): void {
        this.forms = <HTMLFormElement[]>Array.from(document.querySelectorAll(this.formSelector));
        this.mapEvents();
    }

    protected mapEvents(): void {
        this.forms.forEach((form: HTMLElement) => {
            form.addEventListener('submit', (event: Event) => this.onFormSubmit(event));
        });
    }

    protected onFormSubmit(event: Event): void {
        event.preventDefault();
        const form = <HTMLFormElement>event.currentTarget;
        this.disableInputsWithDefaultValues(form);
    }

    /**
     * Toggles the disabled attribute and submits the form.
     * @param form HTMLFormElement is the element for submit event.
     */
    disableInputsWithDefaultValues(form: HTMLFormElement) {
        const inputs = <HTMLInputElement[]>Array.from(form.querySelectorAll(this.inputSelector));

        inputs.forEach((input: HTMLInputElement) => {
            const defaultValue = input.getAttribute(this.defaultValueAttribute);

            if (defaultValue === input.value) {
                input.setAttribute('disabled', 'disabled');

                return;
            }

            input.removeAttribute('disabled');
        });

        form.submit();
    }

    /**
     * Gets a querySelector name of the form element.
     */
    get formSelector(): string {
        return this.getAttribute('form-selector');
    }

    /**
     * Gets a querySelector name of the input element.
     */
    get inputSelector(): string {
        return this.getAttribute('input-selector');
    }

    /**
     * Gets a name of the default value attribute.
     */
    get defaultValueAttribute(): string {
        return this.getAttribute('default-value-attribute');
    }
}

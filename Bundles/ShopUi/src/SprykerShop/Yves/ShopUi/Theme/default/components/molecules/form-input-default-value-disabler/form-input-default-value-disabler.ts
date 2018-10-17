import Component from '../../../models/component';

export default class FormInputDefaultValueDisabler extends Component {
    forms: HTMLFormElement[]
    inputs: HTMLInputElement[]

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

    get formSelector(): string {
        return this.getAttribute('form-selector');
    }

    get inputSelector(): string {
        return this.getAttribute('input-selector');
    }

    get defaultValueAttribute(): string {
        return this.getAttribute('default-value-attribute');
    }
}

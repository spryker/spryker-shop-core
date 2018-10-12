import Component from '../../../models/component';

export default class FormClear extends Component {
    forms: HTMLElement[];
    inputs: HTMLElement[];

    protected readyCallback(): void {
        console.log(this);
        this.forms = <HTMLElement[]>Array.from(document.querySelectorAll(this.formSelector));
        this.inputs = <HTMLElement[]>Array.from(document.querySelectorAll(this.inputSelector));
        this.mapEvents();
    }

    protected mapEvents(): void {
        this.inputs.forEach((input: HTMLElement) => {
            input.addEventListener('change', () => this.onFormClearClick());
        });
    }

    protected onFormClearClick(): void {
        console.log(11);
        const form = <HTMLElement>event.currentTarget;
        this.clearFormValues(form);
    }

    private clearFormValues(form: HTMLElement) {

        // const inputs = <HTMLInputElement[]>Array.from(form.querySelectorAll(this.inputSelector));
        //
        // inputs.forEach((input: HTMLInputElement) => {
        //     const defaultValue = input.getAttribute(this.defaultValueAttribute);
        //
        //     if (defaultValue === input.value) {
        //         input.setAttribute('disabled', 'disabled');
        //         return;
        //     }
        //
        //     input.removeAttribute('disabled');
        // });
        //
        // form.submit();
    }

    get formSelector(): string {
        return this.getAttribute('form-selector');
    }

    get inputSelector(): string {
        return this.getAttribute('input-selector');
    }
}

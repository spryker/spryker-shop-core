import Component from '../../../models/component';

export default class FormClear extends Component {
    form: HTMLElement;
    inputs: HTMLElement[];
    elements: HTMLElement[];
    ignoreElements: HTMLElement[];
    filterElements: HTMLElement[];
    formFieldsReadonlyUpdate: CustomEvent;

    protected readyCallback(): void {
        this.inputs = <HTMLElement[]>Array.from(document.querySelectorAll(this.triggerSelector));
        this.form = <HTMLElement>document.querySelector(this.formSelector);
        this.elements = <HTMLElement[]>Array.from(this.form.querySelectorAll('select, input[type="text"], input[type="radio"], input[type="checkbox"]'));
        this.ignoreElements = <HTMLElement[]>Array.from(this.form.querySelectorAll(this.ignoreSelector));
        this.filterElements = this.elements.filter((el) => !this.ignoreElements.includes(el));

        this.mapEvents();
    }

    protected mapEvents(): void {
        this.createCustomEvents();

        this.inputs.forEach((input: HTMLElement) => {
            input.addEventListener('change', () => {
                const isChecked = (<HTMLInputElement>input).checked;
                if(isChecked) this.clearFormValues(this.filterElements);
            });
        });
    }

    private createCustomEvents(): void {
        this.formFieldsReadonlyUpdate = <CustomEvent>new CustomEvent('form-fields-readonly-update');
    }

    protected clearFormValues(formElements): void {
        for (let i = 0; i < formElements.length; i++) {
            const tagName = formElements[i].tagName.toUpperCase();
            const inputType = formElements[i].type;

            if (tagName == "INPUT") {
                if (inputType == "text") {
                    formElements[i].value = '';
                }
                if (inputType == "checkbox" || inputType == "radio") {
                    formElements[i].checked = false;
                }
            }

            if (tagName == "SELECT") {
                formElements[i].selectedIndex = 0;
            }
        }

        this.dispatchEvent(this.formFieldsReadonlyUpdate);
    }

    get formSelector(): string {
        return this.getAttribute('form-selector');
    }

    get triggerSelector(): string {
        return this.getAttribute('trigger-selector');
    }

    get ignoreSelector(): string {
        return this.getAttribute('ignore-selector');
    }
}

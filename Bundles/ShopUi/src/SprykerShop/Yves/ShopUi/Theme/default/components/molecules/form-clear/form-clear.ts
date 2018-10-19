import Component from '../../../models/component';

export default class FormClear extends Component {
    form: HTMLElement;
    triggers: HTMLElement[];
    targets: HTMLElement[];
    ignoreElements: HTMLElement[];
    filterElements: HTMLElement[];
    formFieldsReadonlyUpdate: CustomEvent;

    protected readyCallback(): void {
        const formElements = 'select, input[type="text"], input[type="radio"], input[type="checkbox"]';

        this.triggers = <HTMLElement[]>Array.from(document.querySelectorAll(this.triggerSelector));
        this.form = <HTMLElement>document.querySelector(this.formSelector);
        this.targets = <HTMLElement[]>Array.from(this.form.querySelectorAll(formElements));
        this.ignoreElements = <HTMLElement[]>Array.from(this.form.querySelectorAll(this.ignoreSelector));
        this.filterElements = this.targets.filter((element) => !this.ignoreElements.includes(element));

        this.mapEvents();
    }

    protected mapEvents(): void {
        this.createCustomEvents();
        this.triggers.forEach((input) => this.onChange(input));
    }

    protected onChange(input): void {
        input.addEventListener('change', () => {
            const isChecked = (<HTMLInputElement>input).checked;
            if(isChecked) {
                this.clearFormValues();
            }
        });
    }

    public clearFormValues(): void {
        this.filterElements.forEach((element: HTMLFormElement) => {
            const tagName = element.tagName.toUpperCase();
            const inputType = element.type;

            if (tagName == "INPUT") {
                if (inputType == "text") {
                    element.value = '';
                }
                if (inputType == "checkbox" || inputType == "radio") {
                    element.checked = false;
                }
            }

            if (tagName == "SELECT") {
                element.selectedIndex = 0;
            }
        });

        this.dispatchEvent(this.formFieldsReadonlyUpdate);
    }

    protected createCustomEvents(): void {
        this.formFieldsReadonlyUpdate = <CustomEvent>new CustomEvent('form-fields-readonly-update');
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

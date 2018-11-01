import Component from '../../../models/component';

export default class FormClear extends Component {
    form: HTMLElement;
    triggers: HTMLElement[];
    targets: HTMLElement[];
    ignoreElements: HTMLElement[];
    filterElements: HTMLElement[];
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

    clearFormValues(): void {
        this.filterElements.forEach((element: HTMLFormElement) => {
            this.clearFormField(element);
        });

        this.dispatchEvent(this.formFieldsClearAfter);
    }

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

    getTagName(element: HTMLElement): string {
        return element.tagName.toUpperCase();
    }

    protected createCustomEvents(): void {
        this.formFieldsClearAfter = <CustomEvent>new CustomEvent('form-fields-clear-after');
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

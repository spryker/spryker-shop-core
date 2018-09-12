import Component from 'ShopUi/models/component';

export default class FormDataInjector extends Component {
    formToEnrich: HTMLFormElement;
    fieldsToInject: HTMLElement[];

    protected readyCallback(): void {
        this.formToEnrich = <HTMLFormElement>document.querySelector(this.formSelector);
        this.fieldsToInject = <HTMLElement[]>Array.from(document.querySelectorAll(this.fieldsSelector));

        this.mapEvents();
    }

    protected mapEvents(): void {
        this.formToEnrich.addEventListener('submit', (event: Event) => this.submitHandle(event), false);
    }

    private submitHandle(event: Event): void {
        event.preventDefault();

        this.preventSubmitButton();
        this.injectData();
        this.formToEnrich.submit();
    }

    private preventSubmitButton(): void {
        this.formToEnrich.querySelector('[type="submit"]').setAttribute('disabled', 'disabled');
    }

    private injectData(): void {
        this.fieldsToInject.forEach((field: HTMLFormElement) => this.addField(field));
    }

    private addField(field: HTMLFormElement): void {
        let insertField: HTMLInputElement = <HTMLInputElement>document.createElement('input');

        insertField.setAttribute('type', 'hidden');
        insertField.setAttribute('name', field.name);
        insertField.setAttribute('value', field.value);

        this.formToEnrich.appendChild(insertField);
    }

    get formSelector(): string {
        return this.getAttribute('destination-form-selector');
    }

    get fieldsSelector(): string {
        return this.getAttribute('fields-selector');
    }
}

import Component from 'ShopUi/models/component';

export default class FormDataInjector extends Component {
    protected destinationForm: HTMLFormElement;
    protected fieldsToInject: HTMLElement[];

    protected readyCallback(): void {}

    protected init(): void {
        this.destinationForm = <HTMLFormElement>document.getElementsByClassName(this.destinationFormClassName)[0];
        this.fieldsToInject = <HTMLElement[]>Array.from(document.querySelectorAll(this.fieldsSelector));

        this.mapEvents();
    }

    protected mapEvents(): void {
        this.destinationForm.addEventListener('submit', (event: Event) => this.onSubmit(event), false);
    }

    protected onSubmit(event: Event): void {
        event.preventDefault();

        this.preventSubmitButton();
        this.injectData();
        this.destinationForm.submit();
    }

    protected preventSubmitButton(): void {
        this.destinationForm.querySelector('[type="submit"]').setAttribute('disabled', 'disabled');
    }

    protected injectData(): void {
        this.fieldsToInject.forEach((field: HTMLFormElement) => this.addField(field));
    }

    protected addField(field: HTMLFormElement): void {
        const insertField: HTMLInputElement = <HTMLInputElement>document.createElement('input');

        insertField.setAttribute('type', 'hidden');
        insertField.setAttribute('name', field.name);
        insertField.setAttribute('value', field.value);

        this.destinationForm.appendChild(insertField);
    }

    protected get destinationFormClassName(): string {
        return this.getAttribute('destination-form-class-name');
    }

    protected get fieldsSelector(): string {
        return this.getAttribute('fields-selector');
    }
}

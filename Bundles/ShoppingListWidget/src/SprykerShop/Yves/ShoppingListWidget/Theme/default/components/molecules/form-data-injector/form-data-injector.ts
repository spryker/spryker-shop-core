import Component from 'ShopUi/models/component';

export default class FormDataInjector extends Component {
    formToEnrich: HTMLFormElement;
    fieldsToInject: HTMLElement[];

    protected readyCallback(): void {
        this.formToEnrich = this.closest('form');
        this.fieldsToInject = <HTMLElement[]>Array.from(document.querySelectorAll(this.fieldsSelector));

        this.mapEvents();
    }

    protected mapEvents(): void {
        this.formToEnrich.addEventListener('submit', (event: Event) => this.submitHandle(event), false);
    }

    private submitHandle(event: Event): void {
        event.preventDefault();

        this.disableButton();
        this.injectData();
        this.formToEnrich.submit();
    }

    private disableButton(): void {
        this.formToEnrich.querySelector('[type="submit"]').setAttribute('disabled', 'disabled');
    }

    private injectData(): void {
        this.fieldsToInject.forEach(field => this.addField(field));
    }

    private addField(field): void {
        let insertField = document.createElement('input');

        insertField.setAttribute('type', 'hidden');
        insertField.setAttribute('name', field.name);
        insertField.setAttribute('value', field.value);

        this.appendChild(insertField);
    }

    get formSelector() {
        return this.getAttribute('form-selector');
    }

    get fieldsSelector() {
        return this.getAttribute('fields-selector');
    }
}

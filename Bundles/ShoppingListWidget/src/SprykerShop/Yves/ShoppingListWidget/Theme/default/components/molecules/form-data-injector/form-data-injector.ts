import Component from 'ShopUi/models/component';

export default class FormDataInjector extends Component {
    injectFields: HTMLElement[];
    formForInject: HTMLFormElement;
    formTakeOutFrom: HTMLFormElement;

    protected readyCallback(): void {
        this.formForInject = this.closest('form');
        this.formTakeOutFrom = document.querySelector(this.getFormSelector);

        this.mapEvents();
    }

    protected mapEvents(): void {
        this.formForInject.addEventListener('submit', event => this.submitHandle(event), false);
    }

    private submitHandle(event): void {
        event.preventDefault();

        this.disableButton();
        this.addHiddenFields();
        this.formForInject.submit();
    }

    private disableButton(): void {
        this.formForInject.querySelector('[type="submit"]').setAttribute('disabled', 'disabled');
    }

    private addHiddenFields(): void {
        const fieldsArray: HTMLFormElement[] = Array.from(this.formTakeOutFrom.querySelectorAll(this.getFieldsSelector));

        this.innerHTML = '';
        fieldsArray.forEach(field => this.addField(field));
    }

    private addField(field): void {
        let insertField = document.createElement('input');

        insertField.setAttribute('type', 'hidden');
        insertField.setAttribute('name', field.name);
        insertField.setAttribute('value', field.value);

        this.appendChild(insertField);
    }

    get getFormSelector() {
        return this.getAttribute('form-class');
    }

    get getFieldsSelector() {
        return this.getAttribute('fields-selector');
    }
}

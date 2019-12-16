import Component from 'ShopUi/models/component';

export default class FormDataInjector extends Component {
    destinationForm: HTMLFormElement;
    fieldsToInject: HTMLElement[];

    protected readyCallback(): void {
        /* tslint:disable: deprecation */
        this.destinationForm = <HTMLFormElement>(this.destinationFormClassName ?
            document.getElementsByClassName(this.destinationFormClassName)[0] :
            document.querySelector(this.destinationFormSelector));
        /* tslint:enable: deprecation */
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

    /**
     * Injects data into the form fields.
     */
    injectData(): void {
        this.fieldsToInject.forEach((field: HTMLFormElement) => this.addField(field));
    }

    protected addField(field: HTMLFormElement): void {
        const insertField: HTMLInputElement = <HTMLInputElement>document.createElement('input');

        insertField.setAttribute('type', 'hidden');
        insertField.setAttribute('name', field.name);
        insertField.setAttribute('value', field.value);

        this.destinationForm.appendChild(insertField);
    }

    /**
     * Gets a querySelector name of the destination form.
     *
     * @deprecated Use destinationFormClassName() instead.
     */
    get destinationFormSelector(): string {
        return this.getAttribute('destination-form-selector');
    }
    protected get destinationFormClassName(): string {
        return this.getAttribute('destination-form-class-name');
    }

    /**
     * Gets a querySelector name of the from fileds.
     */
    get fieldsSelector(): string {
        return this.getAttribute('fields-selector');
    }
}

import Component from 'ShopUi/models/component';
import FormClear from 'ShopUi/components/molecules/form-clear/form-clear';

export default class CompanyBusinessUnitAddressHundler extends Component {
    buttons: HTMLButtonElement[];
    form: HTMLElement;
    elements: HTMLElement[];
    formClearReadonly: FormClear;
    addressesDataObject: any;
    addressesSelects: HTMLSelectElement[];
    currentAddress: String;

    protected readyCallback(): void {
        this.form = <HTMLElement>document.querySelector(this.formSelector);
        this.buttons = <HTMLButtonElement[]>Array.from(this.form.querySelectorAll(this.triggerSelector));
        this.addressesSelects = <HTMLSelectElement[]>Array.from(this.form.querySelectorAll(this.dataSelector));
        this.elements = <HTMLElement[]>Array.from(this.form.querySelectorAll('select, input[type="text"], input[type="radio"], input[type="checkbox"]'));
        this.formClearReadonly = <FormClear>this.form.querySelector('.js-form-clear');

        this.initAddressesData();
        this.mapEvents();
    }

    protected mapEvents(): void {
        this.formClearReadonly.addEventListener('form-fields-readonly-update', () => this.removeFormFieldsReadonly());

        this.buttons.forEach((triggerElement) => {
            triggerElement.addEventListener('click', (event: Event) => {
                if(this.currentAddress) {
                    triggerElement.disabled = false;
                    this.fillFormWithNewAddress();
                    this.setFormFieldsReadonly(event);
                }
            });
        });

        this.addressesSelects.forEach((selectElement) => {
            selectElement.addEventListener('change', () => {
                this.currentAddress = selectElement.value;
            });
        });
    }

    private setFormFieldsReadonly(event: Event): void {
        event.preventDefault();

        this.elements.forEach((formElement: HTMLFormElement) => {
            const select = formElement.tagName.toUpperCase() == 'SELECT';

            if(select) {
                const options = formElement.querySelectorAll('option');

                for (let i = 0; i < options.length; i++) {
                    if(!options[i].selected) options[i].disabled = true;
                }
            }

            formElement.readOnly = true;
        });
    }

    private removeFormFieldsReadonly(): void {
        this.elements.forEach((formElement: HTMLFormElement) => {
            const select = formElement.tagName.toUpperCase() == 'SELECT';

            if(select) {
                const options = formElement.querySelectorAll('option');

                for (let i = 0; i < options.length; i++) {
                    options[i].disabled = false;
                }
            }

            formElement.readOnly = false;
        });
    }

    private fillFormWithNewAddress(): void {

    }

    private initAddressesData(): void {
        const data = this.getAttribute('addresses');

        this.addressesDataObject = JSON.parse(data);
    }

    get formSelector(): string {
        return this.getAttribute('form-selector');
    }

    get triggerSelector(): string {
        return this.getAttribute('trigger-selector');
    }

    get dataSelector(): string {
        return this.getAttribute('data-selector');
    }
}

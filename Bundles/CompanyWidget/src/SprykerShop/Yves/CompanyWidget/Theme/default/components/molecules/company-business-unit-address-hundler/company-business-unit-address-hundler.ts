import Component from 'ShopUi/models/component';
import FormClear from 'ShopUi/components/molecules/form-clear/form-clear';

export default class CompanyBusinessUnitAddressHundler extends Component {
    buttons: HTMLButtonElement[];
    form: HTMLElement;
    elements: HTMLElement[];
    ignoreElements: HTMLElement[];
    filterElements: HTMLElement[];
    formClearReadonly: FormClear;
    addressesDataObject: any;
    addressesSelects: HTMLSelectElement[];
    currentAddress: any;
    hiddenCustomerIdInput: HTMLInputElement;
    hiddenDefaultAddressInput: HTMLInputElement;

    protected readyCallback(): void {
        this.form = <HTMLElement>document.querySelector(this.formSelector);
        this.buttons = <HTMLButtonElement[]>Array.from(this.form.querySelectorAll(this.triggerSelector));
        this.addressesSelects = <HTMLSelectElement[]>Array.from(this.form.querySelectorAll(this.dataSelector));
        this.elements = <HTMLElement[]>Array.from(this.form.querySelectorAll('select, input[type="text"], input[type="radio"], input[type="checkbox"]'));
        this.ignoreElements = <HTMLElement[]>Array.from(this.form.querySelectorAll(this.ignoreSelector));
        this.filterElements = this.elements.filter((el) => !this.ignoreElements.includes(el));
        this.formClearReadonly = <FormClear>this.form.querySelector('.js-form-clear');
        this.hiddenCustomerIdInput = <HTMLInputElement>this.form.querySelector(this.customerIdSelector);
        this.hiddenDefaultAddressInput = <HTMLInputElement>this.form.querySelector(this.defaultAddressSelector);

        this.initAddressesData();
        this.mapEvents();
        this.fillDefaultAddress();
    }

    protected mapEvents(): void {
        this.formClearReadonly.addEventListener('form-fields-readonly-update', () => this.removeFormFieldsReadonly());

        this.buttons.forEach((triggerElement) => {
            triggerElement.addEventListener('click', () => {
                this.fillFormWithNewAddress();
                this.setFormFieldsReadonly();
            });
        });

        this.addressesSelects.forEach((selectElement: HTMLSelectElement) => {
            selectElement.addEventListener('change', () => {
                this.setCurrentAddress(selectElement);
            });
        });
    }

    private setFormFieldsReadonly(): void {
        event.preventDefault();

        this.filterElements.forEach((formElement: HTMLFormElement) => {
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
        this.filterElements.forEach((formElement: HTMLFormElement) => {
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

    private setCurrentAddress(selectElement): void {
        this.currentAddress = {
            id: selectElement.options[selectElement.selectedIndex].getAttribute('data-address-key'),
            label: selectElement.value
        }

        this.buttons.forEach((triggerElement) => {
            triggerElement.disabled = false;
        });
    }

    private fillFormWithNewAddress(): void {
        const currentAddressList = this.addressesDataObject.filter((el) => {
            return el[this.currentAddress.id] == this.currentAddress.label;
        })[0];

        const hiddenCustomerIdInputName = this.hiddenCustomerIdInput.getAttribute('name');

        if(!(hiddenCustomerIdInputName in currentAddressList)) {
            this.hiddenCustomerIdInput.value = '';
        }

        for(let key in currentAddressList) {
            const formElement = this.form.querySelector(`[name="${key}"]`);
            if(formElement !== null) {
                (<HTMLFormElement>formElement).value = '';

                if(currentAddressList[key] !== null) {
                    (<HTMLFormElement>formElement).value = currentAddressList[key];
                }
            }
        }
    }

    private fillDefaultAddress(): void {
        const hiddenDefaultAddressInputName = this.hiddenDefaultAddressInput.getAttribute('name');

        this.addressesDataObject.forEach(address => {
            for(let key in address) {
                if(address[hiddenDefaultAddressInputName] == true) {

                    this.addressesSelects.forEach((selectElement: HTMLSelectElement) => {
                        this.setCurrentAddress(selectElement);
                    });

                    this.fillFormWithNewAddress();
                    this.setFormFieldsReadonly();

                    return;
                }

                if(!address[hiddenDefaultAddressInputName] == true) {
                    const formElement = this.form.querySelector(`[name="${key}"]`);

                    if(formElement !== null) {
                        (<HTMLFormElement>formElement).value = '';
                    }

                    return;
                }
            }
        })
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

    get ignoreSelector(): string {
        return this.getAttribute('ignore-selector');
    }

    get customerIdSelector(): string {
        return this.getAttribute('customer-id-selector');
    }

    get defaultAddressSelector(): string {
        return this.getAttribute('default-address-selector');
    }
}

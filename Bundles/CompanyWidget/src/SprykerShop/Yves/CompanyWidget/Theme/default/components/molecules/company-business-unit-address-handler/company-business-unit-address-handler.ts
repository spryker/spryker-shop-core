import Component from 'ShopUi/models/component';
import FormClear from 'ShopUi/components/molecules/form-clear/form-clear';

export default class CompanyBusinessUnitAddressHandler extends Component {
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
        this.formClearReadonly.addEventListener('form-fields-readonly-update', () => this.toggleFormFieldsReadonly(false));
        this.buttons.forEach((triggerElement: HTMLButtonElement) => this.onClick(triggerElement));
        this.addressesSelects.forEach((selectElement: HTMLSelectElement) => this.onChange(selectElement));
    }

    protected onClick(triggerElement): void {
        triggerElement.addEventListener('click', () => {
            this.fillFormWithNewAddress();
            this.toggleFormFieldsReadonly();
        });
    }

    protected onChange(selectElement): void {
        selectElement.addEventListener('change', () => {
            this.setCurrentAddress(selectElement);
        });
    }

    private toggleFormFieldsReadonly(handler = true): void {
        this.filterElements.forEach((formElement: HTMLFormElement) => {
            const isSelect = formElement.tagName.toUpperCase() == 'SELECT';

            if(isSelect) {
                const options = formElement.querySelectorAll('option');

                for (let i = 0; i < options.length; i++) {
                    if(!options[i].selected && handler) {
                        options[i].disabled = true;
                    } else {
                        options[i].disabled = false;
                    }
                }

                return;
            }

            if(handler) {
                formElement.readOnly = true;
            } else {
                formElement.readOnly = false;
            }
        });
    }

    private setCurrentAddress(selectElement): void {
        this.currentAddress = selectElement.options[selectElement.selectedIndex].getAttribute('data-address-key');
    }

    private fillFormWithNewAddress(): void {
        const currentAddressList = this.addressesDataObject.filter((element) => {
            return element.address_hash == this.currentAddress;
        })[0];
        const hiddenCustomerIdInputName = this.hiddenCustomerIdInput.getAttribute('data-key');

        if(currentAddressList && !(hiddenCustomerIdInputName in currentAddressList)) {
            this.hiddenCustomerIdInput.value = '';
        }

        this.filterElements.forEach((element) => {
            const isSelect = element.tagName.toUpperCase() == "SELECT";

            (<HTMLFormElement>element).value = '';

            if (isSelect) {
                (<HTMLFormElement>element).selectedIndex = 0;
            }
        });

        for(let key in currentAddressList) {
            const formElement = this.form.querySelector(`[data-key="${key}"]`);

            if(formElement !== null && currentAddressList[key] !== null) {
                (<HTMLFormElement>formElement).value = currentAddressList[key];
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
                    this.toggleFormFieldsReadonly();

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

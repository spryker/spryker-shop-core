import Component from 'ShopUi/models/component';
import FormClear from 'ShopUi/components/molecules/form-clear/form-clear';

interface AddressJSON {
    address1: string,
    address2: string,
    address3: string,
    address_hash: string,
    city: string,
    company: string,
    customer_id: number,
    default: boolean,
    first_name: string,
    id_customer_address: number,
    iso2_code: string,
    last_name: string,
    phone: string,
    salutation: string,
    zip_code: number
}

export default class CompanyBusinessUnitAddressHandler extends Component {
    triggers: HTMLElement[];
    form: HTMLElement;
    targets: HTMLElement[];
    ignoreElements: HTMLElement[];
    filterElements: HTMLElement[];
    formClear: FormClear;
    addressesDataObject: AddressJSON[];
    addressesSelects: HTMLSelectElement[];
    currentAddress: String;
    hiddenCustomerIdInput: HTMLInputElement;
    hiddenDefaultAddressInput: HTMLInputElement;

    protected readyCallback(): void {
        const formElements = 'select, input[type="text"], input[type="radio"], input[type="checkbox"]';

        this.form = <HTMLElement>document.querySelector(this.formSelector);
        this.triggers = <HTMLElement[]>Array.from(this.form.querySelectorAll(this.triggerSelector));
        this.addressesSelects = <HTMLSelectElement[]>Array.from(this.form.querySelectorAll(this.dataSelector));
        this.targets = <HTMLElement[]>Array.from(this.form.querySelectorAll(formElements));
        this.ignoreElements = <HTMLElement[]>Array.from(this.form.querySelectorAll(this.ignoreSelector));
        this.filterElements = this.targets.filter((element) => !this.ignoreElements.includes(element));
        this.formClear = <FormClear>this.form.querySelector('.js-form-clear');
        this.hiddenCustomerIdInput = <HTMLInputElement>this.form.querySelector(this.customeridSelector);
        this.hiddenDefaultAddressInput = <HTMLInputElement>this.form.querySelector(this.defaultAddressSelector);

        this.initAddressesData();
        this.mapEvents();
        this.fillDefaultAddress();
    }

    protected mapEvents(): void {
        this.formClear.addEventListener('form-fields-readonly-update', () => this.toggleFormFieldsReadonly(false));
        this.triggers.forEach((triggerElement) => {
            triggerElement.addEventListener('click', () => {
                this.onClick(triggerElement);
            });
        });
        this.addressesSelects.forEach((selectElement) => {
            selectElement.addEventListener('change', () => {
                this.onChange(selectElement);
            });
        });
    }

    protected onClick(triggerElement: HTMLElement): void {
        this.fillFormWithNewAddress();
        if(this.currentAddress) {
            this.toggleFormFieldsReadonly();
        }
    }

    protected onChange(selectElement: HTMLSelectElement): void {
        this.setCurrentAddress(selectElement);
    }

    toggleFormFieldsReadonly(isEnabled: boolean = true): void {
        this.filterElements.forEach((formElement: HTMLFormElement) => {
            const isSelect = this.formClear.getTagName(formElement) == 'SELECT';

            if(isSelect) {
                const options = Array.from(formElement.querySelectorAll('option'));

                options.forEach((element) => {
                    if(!element.selected) {
                        element.disabled = isEnabled;
                    }
                });

                return;
            }

            formElement.readOnly = isEnabled;
        });
    }

    protected setCurrentAddress(selectElement: HTMLSelectElement): void {
        this.currentAddress = selectElement.options[selectElement.selectedIndex].getAttribute('data-address-key');
    }

    protected fillFormWithNewAddress(): void {
        const currentAddressList = this.addressesDataObject.filter((element) => {
            return element['address_hash'] == this.currentAddress;
        })[0];
        const hiddenCustomerIdInputName = this.hiddenCustomerIdInput.getAttribute('data-key');

        if(currentAddressList && !(hiddenCustomerIdInputName in currentAddressList)) {
            this.hiddenCustomerIdInput.value = '';
        }

        this.clearFormFields();
        this.fillFormFields(currentAddressList);
    }

    protected fillDefaultAddress(): void {
        const hiddenDefaultAddressInputName = this.hiddenDefaultAddressInput.getAttribute('name');

        this.addressesDataObject.forEach(address => {
            for(let key in address) {
                if(address[hiddenDefaultAddressInputName]) {

                    this.addressesSelects.forEach((selectElement: HTMLSelectElement) => {
                        this.setCurrentAddress(selectElement);
                    });

                    this.fillFormWithNewAddress();
                    this.toggleFormFieldsReadonly();

                    return;
                }
            }
        });
    }

    clearFormFields(): void {
        this.filterElements.forEach((element) => {
            const isSelect = this.formClear.getTagName(element) == "SELECT";

            (<HTMLFormElement>element).value = '';

            if (isSelect) {
                (<HTMLFormElement>element).selectedIndex = 0;
            }
        });
    }

    fillFormFields(address: object): void {
        for(let key in address) {
            const formElement = this.form.querySelector(`[data-key="${key}"]`);

            if(formElement !== null && address[key] !== null) {
                (<HTMLFormElement>formElement).value = address[key];
            }
        }
    }

    protected initAddressesData(): void {
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

    get customeridSelector(): string {
        return this.getAttribute('customer-id-selector');
    }

    get defaultAddressSelector(): string {
        return this.getAttribute('default-address-selector');
    }
}

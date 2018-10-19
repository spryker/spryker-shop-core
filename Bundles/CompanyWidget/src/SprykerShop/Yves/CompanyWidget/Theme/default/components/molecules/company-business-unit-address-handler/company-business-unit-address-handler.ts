import Component from 'ShopUi/models/component';
import FormClear from 'ShopUi/components/molecules/form-clear/form-clear';

export default class CompanyBusinessUnitAddressHandler extends Component {
    triggers: HTMLElement[];
    form: HTMLElement;
    targets: HTMLElement[];
    ignoreElements: HTMLElement[];
    filterElements: HTMLElement[];
    formClearReadonly: FormClear;
    addressesDataObject: Object[];
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
        this.formClearReadonly = <FormClear>this.form.querySelector('.js-form-clear');
        this.hiddenCustomerIdInput = <HTMLInputElement>this.form.querySelector(this.customeridSelector);
        this.hiddenDefaultAddressInput = <HTMLInputElement>this.form.querySelector(this.defaultAddressSelector);

        this.initAddressesData();
        this.mapEvents();
        this.fillDefaultAddress();
    }

    protected mapEvents(): void {
        this.formClearReadonly.addEventListener('form-fields-readonly-update', () => this.toggleFormFieldsReadonly(false));
        this.triggers.forEach((triggerElement) => this.onClick(triggerElement));
        this.addressesSelects.forEach((selectElement) => this.onChange(selectElement));
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

    public toggleFormFieldsReadonly(isEnabled = true): void {
        this.filterElements.forEach((formElement: HTMLFormElement) => {
            const isSelect = formElement.tagName.toUpperCase() == 'SELECT';

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

    protected setCurrentAddress(selectElement): void {
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

        this.deleteFromFormField();
        this.fillFormField(currentAddressList);
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

    public deleteFromFormField(): void {
        this.filterElements.forEach((element) => {
            const isSelect = element.tagName.toUpperCase() == "SELECT";

            (<HTMLFormElement>element).value = '';

            if (isSelect) {
                (<HTMLFormElement>element).selectedIndex = 0;
            }
        });
    }

    public fillFormField(dataList): void {
        for(let key in dataList) {
            const formElement = this.form.querySelector(`[data-key="${key}"]`);

            if(formElement !== null && dataList[key] !== null) {
                (<HTMLFormElement>formElement).value = dataList[key];
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

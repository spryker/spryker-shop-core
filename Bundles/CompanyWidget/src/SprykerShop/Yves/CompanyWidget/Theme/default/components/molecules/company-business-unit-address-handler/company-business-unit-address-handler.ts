import Component from 'ShopUi/models/component';

const EVENT_HIDDEN_ADDRESS_INPUT_CHANGE = 'hidden-address-input-change';
/**
 * @event hidden-address-input-change An event which is triggered after the new address are selected.
 */
export default class CompanyBusinessUnitAddressHandler extends Component {
    /**
     * The current form.
     */
    form: HTMLFormElement;
    /**
     * Data object of the address list.
     */
    /* tslint:disable:no-any */
    addressesDataObject: any;
    /* tslint:enable:no-any */
    /**
     * Collection of the address select elements.
     */
    addressesSelects: HTMLSelectElement[];
    /**
     * The selected address.
     */
    currentAddress: string;
    /**
     * The hidden input with selected address by default.
     */
    hiddenDefaultAddressInput: HTMLInputElement;
    /**
     * The custom event.
     */
    hiddenAddressInputChangeEvent: CustomEvent;
    /**
     * The shipping address select element.
     */
    shippingAddressToggler: HTMLSelectElement;

    protected readyCallback(): void {
        this.form = <HTMLFormElement>document.querySelector(this.formSelector);
        this.addressesSelects = <HTMLSelectElement[]>Array.from(this.form.querySelectorAll(this.dataSelector));
        this.hiddenDefaultAddressInput = <HTMLInputElement>this.form.querySelector(this.defaultAddressSelector);
        if (this.shippingAddressTogglerSelector) {
            this.shippingAddressToggler = <HTMLSelectElement>document.querySelector(
                this.shippingAddressTogglerSelector
            );
        }
        this.initAddressesData();
        this.mapEvents();
        this.initHiddenAddressInputChangeEvent();
        this.fillDefaultAddress();
        if (this.shippingAddressToggler) {
            this.toggleSplitDeliveryAddressFormValue();
        }
    }

    protected mapEvents(): void {
        this.addressesSelects.forEach((selectElement: HTMLSelectElement) => {
            selectElement.addEventListener('change', () => {
                this.setCurrentAddress(selectElement);
                this.fillHiddenInputsWithNewAddress();
            });
        });
        if (this.shippingAddressToggler) {
            this.shippingAddressToggler.addEventListener('change', () => {
                this.toggleSplitDeliveryAddressFormValue();
            });
        }
    }

    protected initHiddenAddressInputChangeEvent(): void {
        this.hiddenAddressInputChangeEvent = new CustomEvent(EVENT_HIDDEN_ADDRESS_INPUT_CHANGE);
        this.hiddenAddressInputChangeEvent.initEvent('change', true, true);
    }

    protected toggleSplitDeliveryAddressFormValue(): void {
        const hiddenInputIdCustomerShippingAddress = <HTMLInputElement>document.querySelector(
            this.shippingAddressHiddenInputSelector
        );
        const hiddenInputIdCompanyShippingAddress = <HTMLInputElement>document.querySelector(
            this.shippingCompanyAddressHiddenInputSelector
        );
        if (this.shippingAddressToggler.value === this.optionValueDeliverToMultipleAddresses) {
            hiddenInputIdCustomerShippingAddress.value = this.optionValueDeliverToMultipleAddresses;
            hiddenInputIdCompanyShippingAddress.value = this.optionValueDeliverToMultipleAddresses;
        }
    }

    protected setCurrentAddress(selectElement: HTMLSelectElement): void {
        this.currentAddress = selectElement.options[selectElement.selectedIndex].getAttribute('value');
    }

    protected fillHiddenInputsWithNewAddress(): void {
        const currentAddressList = this.addressesDataObject[this.currentAddress];
        const hiddenInputIdCustomerAddress = <HTMLInputElement>this.form.querySelector(this.addressHiddenInputSelector);
        const hiddenInputIdCompanyAddress = <HTMLInputElement>this.form.querySelector(
            this.companyAddressHiddenInputSelector
        );
        this.hiddenDefaultAddressInput.value = this.currentAddress;
        this.fillHiddenInputAddressesFields(currentAddressList, this.addressHiddenInputSelector, 'id_customer_address');
        this.fillHiddenInputAddressesFields(
            currentAddressList, this.companyAddressHiddenInputSelector, 'id_company_unit_address'
        );
        hiddenInputIdCustomerAddress.dispatchEvent(this.hiddenAddressInputChangeEvent);
        hiddenInputIdCompanyAddress.dispatchEvent(this.hiddenAddressInputChangeEvent);
    }

    protected fillDefaultAddress(): void {
        const hiddenDefaultAddressInputValue = this.hiddenDefaultAddressInput.getAttribute('value');
        if (hiddenDefaultAddressInputValue) {
            this.currentAddress = hiddenDefaultAddressInputValue;
            this.fillHiddenInputsWithNewAddress();
        }
    }

    /**
     * Fills the form element's value with an address value.
     * @param address A data object for filling the fields.
     */
    fillHiddenInputAddressesFields(address: object, selector: string, idAddressKey: string): void {
        const hiddenInputIdAddress = <HTMLInputElement>this.form.querySelector(selector);
        hiddenInputIdAddress.value = address ? address[idAddressKey] : '';
    }

    protected initAddressesData(): void {
        const data = this.getAttribute('addresses');
        this.addressesDataObject = JSON.parse(data);
    }

    /**
     * Gets a querySelector name of the form element.
     */
    get formSelector(): string {
        return this.getAttribute('form-selector');
    }

    /**
     * Gets a querySelector name of the address select elements.
     */
    get dataSelector(): string {
        return this.getAttribute('data-selector');
    }

    /**
     * Gets a querySelector name of a hidden default address input.
     */
    get defaultAddressSelector(): string {
        return this.getAttribute('default-address-selector');
    }

    /**
     * Gets a querySelector name of a hidden customer id input.
     */
    get addressHiddenInputSelector(): string {
        return this.getAttribute('address-hidden-input-selector');
    }

    /**
     * Gets a querySelector name of a hidden company unit id input.
     */
    get companyAddressHiddenInputSelector(): string {
        return this.getAttribute('company-address-hidden-input-selector');
    }

    /**
     * Gets a querySelector name of a hidden shipping customer id input.
     */
    get shippingAddressHiddenInputSelector(): string {
        return this.getAttribute('shipping-address-hidden-input-selector');
    }

    /**
     * Gets a querySelector name of a hidden shipping customer id input.
     */
    get shippingCompanyAddressHiddenInputSelector(): string {
        return this.getAttribute('shipping-company-address-hidden-input-selector');
    }

    /**
     * Gets if the split delivery form is defined.
     */
    get optionValueDeliverToMultipleAddresses(): string {
        return this.getAttribute('toggle-option-value');
    }

    /**
     * Gets a querySelector name of the shipping address select element.
     */
    get shippingAddressTogglerSelector(): string {
        return this.getAttribute('shipping-address-toggler-selector');
    }
}

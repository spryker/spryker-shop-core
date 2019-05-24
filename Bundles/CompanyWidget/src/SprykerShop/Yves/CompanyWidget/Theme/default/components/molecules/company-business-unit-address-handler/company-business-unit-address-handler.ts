/* tslint:disable:max-file-line-count */
import Component from 'ShopUi/models/component';

/**
 * @event add-new-address An event which is triggered after the form fields are filled.
 */
export default class CompanyBusinessUnitAddressHandler extends Component {
    /**
     * Collection of the triggers elements.
     */
    triggers: HTMLElement[];
    /**
     * The current form.
     */
    form: HTMLFormElement;
    /**
     * Collection of the form elements.
     */
    targets: HTMLElement[];
    /**
     * Collection of the targets elements which should be ignored while collection the filters.
     */
    ignoreElements: HTMLElement[];
    /**
     * Collection of the filter elements.
     */
    filterElements: HTMLElement[];
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
     * The input address element which triggers toggling of the disabled attribute.
     */
    customAddressTriggerInput: HTMLFormElement;
    /**
     * The custom event.
     */
    resetSelectEvent: CustomEvent;
    /**
     * The custom event.
     */
    addNewAddressEvent: CustomEvent;
    /**
     * The shipping address select element.
     */
    shippingAddressToggler: HTMLSelectElement;

    readonly resetSelectEventName: string = 'reset-select';

    protected readyCallback(): void {
        const formElements = 'select, input[type="text"], input[type="radio"], input[type="checkbox"]';

        this.form = <HTMLFormElement>document.querySelector(this.formSelector);
        this.triggers = <HTMLElement[]>Array.from(this.form.querySelectorAll(this.triggerSelector));
        this.addressesSelects = <HTMLSelectElement[]>Array.from(this.form.querySelectorAll(this.dataSelector));
        this.targets = <HTMLElement[]>Array.from(this.form.querySelectorAll(formElements));
        this.ignoreElements = <HTMLElement[]>Array.from(this.form.querySelectorAll(this.ignoreSelector));
        this.filterElements = this.targets.filter(element => !this.ignoreElements.includes(element));
        this.hiddenDefaultAddressInput = <HTMLInputElement>this.form.querySelector(this.defaultAddressSelector);
        this.customAddressTriggerInput = <HTMLFormElement>this.form.querySelector(this.customAddressTrigger);
        this.shippingAddressToggler = <HTMLSelectElement>document.querySelector(this.shippingAddressTogglerSelector);

        this.initAddressesData();
        this.mapEvents();
        this.initResetSelectEvent();
        this.fillDefaultAddress();
        this.toggleSplitDeliveryAddressFormValue();
    }

    protected mapEvents(): void {
        this.addressesSelects.forEach(selectElement => {
            selectElement.addEventListener('change', () => {
                this.setCurrentAddress(selectElement);
                this.fillFormWithNewAddress();
            });
        });

        this.shippingAddressToggler.addEventListener('change', () => this.toggleSplitDeliveryAddressFormValue());
    }

    protected initResetSelectEvent(): void {
        this.resetSelectEvent = new CustomEvent(this.resetSelectEventName);
        this.resetSelectEvent.initEvent('change', true, true);
    }

    protected toggleSplitDeliveryAddressFormValue(): void {
        const addressHiddenInput = <HTMLInputElement>document.querySelector(
            `[name="${this.shippingAddressHiddenInputSelector}"]`
        );
        const hiddenInputIdCustomerAddressSaver = <HTMLInputElement>document.querySelector(
            this.hiddenInputIdCustomerShippingAddressSaverSelector
        );

        if (this.shippingAddressToggler.value === this.optionValueDeliverToMultipleAddresses) {
            addressHiddenInput.value = this.optionValueDeliverToMultipleAddresses;

            return;
        }

        addressHiddenInput.value = hiddenInputIdCustomerAddressSaver.value;
    }

    protected setCurrentAddress(selectElement: HTMLSelectElement): void {
        this.currentAddress = selectElement.options[selectElement.selectedIndex].getAttribute('value');
    }

    protected fillFormWithNewAddress(): void {
        const currentAddressList = this.addressesDataObject[this.currentAddress.toString()];
        const hiddenInputIdCustomerAddress = <HTMLInputElement>this.form.querySelector(
            `[name="${this.addressHiddenInputSelector}"]`
        );

        this.hiddenDefaultAddressInput.value = this.currentAddress.toString();
        this.fillFormFields(currentAddressList);
        hiddenInputIdCustomerAddress.dispatchEvent(this.resetSelectEvent);
    }

    protected fillDefaultAddress(): void {
        const hiddenDefaultAddressInputName = this.hiddenDefaultAddressInput.getAttribute('value');

        if (hiddenDefaultAddressInputName) {
            this.currentAddress = hiddenDefaultAddressInputName;
            this.fillFormWithNewAddress();
        }
    }

    /**
     * Fills the form element's value with an address value.
     * @param address A data object for filling the fields.
     */
    fillFormFields(address: object): void {
        const hiddenInputIdCustomerAddressSaver = <HTMLInputElement>this.form.querySelector(
            this.hiddenInputIdCustomerAddressSaverSelector
        );
        const hiddenInputIdCustomerAddress = <HTMLInputElement>this.form.querySelector(
            `[name="${this.addressHiddenInputSelector}"]`
        );
        const idCustomerAddress = 'id_customer_address';

        hiddenInputIdCustomerAddressSaver.value = address ? address[idCustomerAddress] : '';
        hiddenInputIdCustomerAddress.value = address ? address[idCustomerAddress] : '';
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
     * Gets a querySelector name of the trigger elements.
     */
    get triggerSelector(): string {
        return this.getAttribute('trigger-selector');
    }

    /**
     * Gets a querySelector name of the address select elements.
     */
    get dataSelector(): string {
        return this.getAttribute('data-selector');
    }

    /**
     * Gets a querySelector name of the ignore elements.
     */
    get ignoreSelector(): string {
        return this.getAttribute('ignore-selector');
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
     * Gets a querySelector name of a hidden customer id input.
     */
    get hiddenInputIdCustomerAddressSaverSelector(): string {
        return this.getAttribute('hidden-input-id-customer-address-saver-selector');
    }

    /**
     * Gets a querySelector name of a hidden shipping customer id input.
     */
    get hiddenInputIdCustomerShippingAddressSaverSelector(): string {
        return this.getAttribute('hidden-input-id-customer-shipping-address-saver-selector');
    }

    /**
     * Gets a querySelector name of a hidden shipping customer id input.
     */
    get shippingAddressHiddenInputSelector(): string {
        return this.getAttribute('shipping-address-hidden-input-selector');
    }

    /**
     * Gets a querySelector name of a custom address trigger input.
     */
    get customAddressTrigger(): string {
        return this.getAttribute('custom-address-trigger');
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

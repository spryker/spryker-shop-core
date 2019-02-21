import Component from 'ShopUi/models/component';
import FormClear from 'ShopUi/components/molecules/form-clear/form-clear';

const EVENT_ADD_NEW_ADDRESS = 'add-new-address';

/**
 * @event add-new-address An event which is triggered after the form fields are filled.
 */
export default class SaveNewAddress extends Component {
    /**
     * The select/input address element which triggers toggling of the shipping address form.
     */
    customerShippingAddresses: HTMLFormElement;

    /**
     * The select/input address element which triggers toggling of the billing address form.
     */
    customerBillingAddresses: HTMLFormElement;

    /**
     * The input checkbox element which shows/hides if specified address need to be save/unsave.
     */
    saveNewAddressToggler: HTMLInputElement;

    /**
     * The input checkbox element which toggle billing address form.
     */
    sameAsShippingToggler: HTMLInputElement;

    /**
     * The button element which fill shipping form fileds on click.
     */
    addNewShippingAddress: HTMLButtonElement;

    /**
     * The button element which fill billing form fileds on click.
     */
    addNewBillingAddress: HTMLButtonElement;

    /**
     * The select business unit address element which toggling the shipping addresses.
     */
    businessUnitShippingAddressToggler: HTMLSelectElement;

    /**
     * Checks if the shipping adress is selected.
     */
    newShippingAddressChecked: boolean = false;

    /**
     * Checks if the billing adress is selected.
     */
    newBillingAddressChecked: boolean = false;

    /**
     * Imported component clears the form.
     */
    formClearShippingAddress: FormClear;

    /**
     * Html class for hides element.
     */
    readonly hideClass: string = 'is-hidden';

    protected readyCallback(): void {
        if (this.shippingAddressTogglerSelector) {
            this.customerShippingAddresses = <HTMLFormElement>document.querySelector(this.shippingAddressTogglerSelector);
        }

        if (this.billingAddressTogglerSelector) {
            this.customerBillingAddresses = <HTMLFormElement>document.querySelector(this.billingAddressTogglerSelector);
        }

        if (this.addNewShippingAddressSelector && this.addNewBillingAddressSelector) {
            this.addNewShippingAddress = <HTMLButtonElement>document.querySelector(this.addNewShippingAddressSelector);
            this.addNewBillingAddress = <HTMLButtonElement>document.querySelector(this.addNewBillingAddressSelector);
        }

        if (this.billingSameAsShippingAddressTogglerSelector) {
            this.sameAsShippingToggler = <HTMLInputElement>document.querySelector(this.billingSameAsShippingAddressTogglerSelector);
        }

        if (this.businessUnitShippingAddressTogglerSelector) {
            this.businessUnitShippingAddressToggler = <HTMLSelectElement>document.querySelector(this.businessUnitShippingAddressTogglerSelector);
        }

        if (this.formClearShippingAddressSelector) {
            this.formClearShippingAddress = <FormClear>document.querySelector(this.formClearShippingAddressSelector);
        }

        if (this.saveAddressTogglerSelector) {
            this.saveNewAddressToggler = <HTMLInputElement>document.querySelector(this.saveAddressTogglerSelector);
        }

        this.customerAddressesExists();
    }

    protected customerAddressesExists(): void {
        if (!this.customerShippingAddresses) {
            this.showSaveNewAddress();

            return;
        }

        this.mapEvents();
        this.initSaveNewAddressState();
    }

    protected mapEvents(): void {
        if (this.addNewShippingAddress && this.addNewBillingAddress) {
            this.addNewShippingAddress.addEventListener(EVENT_ADD_NEW_ADDRESS, () => {
                this.shippingTogglerOnChange();
                this.toggleSplitDeliveryAddressForm();
            });
            this.addNewBillingAddress.addEventListener(EVENT_ADD_NEW_ADDRESS, () => this.billingTogglerOnChange());
        }

        if (this.formClearShippingAddress) {
            this.formClearShippingAddress.addEventListener('form-fields-clear-after', () => this.toggleSplitDeliveryAddressForm());
        }

        this.customerShippingAddresses.addEventListener('change', () => this.shippingTogglerOnChange());

        if (this.customerBillingAddresses) {
            this.customerBillingAddresses.addEventListener('change', () => this.billingTogglerOnChange());
        }

        if (this.sameAsShippingToggler) {
            this.sameAsShippingToggler.addEventListener('change', () => this.toggleSaveNewAddress());
        }

        if (this.businessUnitShippingAddressToggler) {
            this.businessUnitShippingAddressToggler.addEventListener('change', () => this.toggleSplitDeliveryAddressForm());
        }
    }

    protected toggleSplitDeliveryAddressForm(): void {
        const selectedValue = this.businessUnitShippingAddressToggler.value;

        if (selectedValue === this.optionValueDeliverToMultipleAddresses) {
            this.newShippingAddressChecked = !selectedValue;
            this.toggleSaveNewAddress();

            return;
        }

        this.newShippingAddressChecked = this.isSaveNewAddressOptionSelected(this.customerShippingAddresses);
        this.toggleSaveNewAddress();
    }

    protected shippingTogglerOnChange(): void {
        if (!this.customerBillingAddresses && this.customerShippingAddresses.value === this.optionValueDeliverToMultipleAddresses) {
            this.newBillingAddressChecked = true;
        }

        this.newShippingAddressChecked = this.addressTogglerChange(this.customerShippingAddresses);
        this.toggleSaveNewAddress();
    }

    protected billingTogglerOnChange(): void {
        this.newBillingAddressChecked = this.addressTogglerChange(this.customerBillingAddresses);
        this.toggleSaveNewAddress();
    }

    protected initSaveNewAddressState(): void {
        this.newShippingAddressChecked = this.isSaveNewAddressOptionSelected(this.customerShippingAddresses);

        if (this.customerBillingAddresses) {
            this.newBillingAddressChecked = this.isSaveNewAddressOptionSelected(this.customerBillingAddresses);
        }

        this.toggleSaveNewAddress();

        if (this.businessUnitShippingAddressToggler) {
            this.toggleSplitDeliveryAddressForm();
        }
    }

    protected addressTogglerChange(toggler): boolean {
        return this.isSaveNewAddressOptionSelected(toggler);
    }

    protected isSaveNewAddressOptionSelected(toggler: HTMLFormElement): boolean {
        return !toggler.value;
    }

    /**
     * Toggles 'save new address' checkbox.
     */
    toggleSaveNewAddress(): void {
        if (this.newShippingAddressChecked || (this.newBillingAddressChecked && !this.sameAsShippingChecked)) {
            this.showSaveNewAddress();

            return;
        }

        this.hideSaveNewAddress();
    }

    /**
     * Hides 'save new address' checkbox.
     */
    hideSaveNewAddress(): void {
        this.classList.add(this.hideClass);
        this.saveNewAddressToggler.disabled = true;
    }

    /**
     * Shows 'save new address' checkbox.
     */
    showSaveNewAddress(): void {
        this.classList.remove(this.hideClass);
        if (this.saveNewAddressToggler) {
            this.saveNewAddressToggler.disabled = false;
        }
    }

    /**
     * Checks if 'billing same as shipping' checkbox is checked.
     */
    get sameAsShippingChecked(): boolean {
        if (this.sameAsShippingToggler) {
            return this.sameAsShippingToggler.checked;
        }
    }

    /**
     * Gets a querySelector of the shipping address toggler element.
     */
    get shippingAddressTogglerSelector(): string {
        return this.getAttribute('shipping-address-toggler-selector');
    }

    /**
     * Gets a querySelector of the billing address toggler element.
     */
    get billingAddressTogglerSelector(): string {
        return this.getAttribute('billing-address-toggler-selector');
    }

    /**
     * Gets a querySelector of the trigger button element.
     */
    get addNewShippingAddressSelector(): string {
        return this.getAttribute('add-new-shipping-address-selector');
    }

    /**
     * Gets a querySelector of the trigger button element.
     */
    get addNewBillingAddressSelector(): string {
        return this.getAttribute('add-new-billing-address-selector');
    }

    /**
     * Gets a querySelector of the 'billing same as shipping' checkbox element.
     */
    get billingSameAsShippingAddressTogglerSelector(): string {
        return this.getAttribute('billing-same-as-shipping-toggler-selector');
    }

    /**
     * Gets a querySelector of the trigger checkbox element.
     */
    get saveAddressTogglerSelector(): string {
        return this.getAttribute('save-address-toggler-selector');
    }

    /**
     * Gets a querySelector of the business unit shipping address toggler element.
     */
    get businessUnitShippingAddressTogglerSelector(): string {
        return this.getAttribute('business-unit-shipping-address-toggler-selector');
    }

    /**
     * Gets if the split delivery form is defined.
     */
    get optionValueDeliverToMultipleAddresses(): string {
        return this.getAttribute('toggle-option-value');
    }

    /**
     * Gets a querySelector of the clear shipping address form element.
     */
    get formClearShippingAddressSelector(): string {
        return this.getAttribute('form-clear-shipping-address-selector');
    }
}

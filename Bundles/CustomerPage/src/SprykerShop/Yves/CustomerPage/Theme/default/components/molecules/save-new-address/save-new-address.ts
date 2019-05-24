/* tslint:disable:max-file-line-count */
import Component from 'ShopUi/models/component';

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
     * Html class for hides element.
     */
    readonly hideClass: string = 'is-hidden';

    protected readyCallback(): void {
        if (this.shippingAddressTogglerSelector) {
            this.customerShippingAddresses = <HTMLFormElement>document.querySelector(
                this.shippingAddressTogglerSelector
            );
        }

        if (this.billingAddressTogglerSelector) {
            this.customerBillingAddresses = <HTMLFormElement>document.querySelector(this.billingAddressTogglerSelector);
        }

        if (this.billingSameAsShippingAddressTogglerSelector) {
            this.sameAsShippingToggler = <HTMLInputElement>document.querySelector(
                this.billingSameAsShippingAddressTogglerSelector
            );
        }

        if (this.businessUnitShippingAddressTogglerSelector) {
            this.businessUnitShippingAddressToggler = <HTMLSelectElement>document.querySelector(
                this.businessUnitShippingAddressTogglerSelector
            );
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
        this.customerShippingAddresses.addEventListener('change', () => this.shippingTogglerOnChange());

        if (this.customerBillingAddresses) {
            this.customerBillingAddresses.addEventListener('change', () => this.billingTogglerOnChange());
        }

        if (this.sameAsShippingToggler) {
            this.sameAsShippingToggler.addEventListener('change', () => this.toggleSaveNewAddress());
        }

        if (this.businessUnitShippingAddressToggler) {
            this.businessUnitShippingAddressToggler.addEventListener('change', () => {
                this.splitDeliveryTogglerOnChange();
            });
        }
    }

    protected splitDeliveryTogglerOnChange(): void {
        const selectedValue = this.businessUnitShippingAddressToggler.value;

        if (selectedValue === this.optionValueDeliverToMultipleAddresses) {
            this.newShippingAddressChecked = !selectedValue;
            this.toggleSaveNewAddress();

            return;
        }

        this.newShippingAddressChecked = this.isSaveNewAddressOptionSelected(this.customerShippingAddresses);
        this.toggleSaveNewAddress();
    }

    protected initSplitDeliveryToggler(): void {
        const isDeliverToMultipleAddresses =
            this.customerShippingAddresses.value === this.optionValueDeliverToMultipleAddresses;

        if (!this.customerBillingAddresses && isDeliverToMultipleAddresses) {
            this.newBillingAddressChecked = true;
        }
    }

    protected shippingTogglerOnChange(): void {
        this.initSplitDeliveryToggler();

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

        this.initSplitDeliveryToggler();
        this.toggleSaveNewAddress();

        if (this.businessUnitShippingAddressToggler) {
            this.splitDeliveryTogglerOnChange();
        }
    }

    protected addressTogglerChange(toggler: HTMLFormElement): boolean {
        return this.isSaveNewAddressOptionSelected(toggler);
    }

    protected isSaveNewAddressOptionSelected(toggler: HTMLFormElement): boolean {
        console.log(toggler, !toggler.value);
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
}

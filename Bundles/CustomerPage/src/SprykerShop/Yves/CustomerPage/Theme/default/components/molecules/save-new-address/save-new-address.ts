import Component from 'ShopUi/models/component';

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
        if (this.shippingAddressTogglerSelector && this.billingAddressTogglerSelector) {
            this.customerShippingAddresses = <HTMLFormElement>document.querySelector(this.shippingAddressTogglerSelector);
            this.customerBillingAddresses = <HTMLFormElement>document.querySelector(this.billingAddressTogglerSelector);
        }

        if (this.addNewShippingAddressSelector && this.addNewBillingAddressSelector) {
            this.addNewShippingAddress = <HTMLButtonElement>document.querySelector(this.addNewShippingAddressSelector);
            this.addNewBillingAddress = <HTMLButtonElement>document.querySelector(this.addNewBillingAddressSelector);
        }

        if (this.billingSameAsShippingAddressTogglerSelector) {
            this.sameAsShippingToggler = <HTMLInputElement>document.querySelector(this.billingSameAsShippingAddressTogglerSelector);
        }

        this.saveNewAddressToggler = <HTMLInputElement>document.querySelector(this.saveAddressTogglerSelector);

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
            this.addNewShippingAddress.addEventListener(EVENT_ADD_NEW_ADDRESS, () => this.shippingTogglerOnChange());
            this.addNewBillingAddress.addEventListener(EVENT_ADD_NEW_ADDRESS, () => this.billingTogglerOnChange());
        }

        this.customerShippingAddresses.addEventListener('change', () => this.shippingTogglerOnChange());
        this.customerBillingAddresses.addEventListener('change', () => this.billingTogglerOnChange());
        this.sameAsShippingToggler.addEventListener('change', () => this.toggleSaveNewAddress());
    }

    protected shippingTogglerOnChange(): void {
        this.newShippingAddressChecked = this.addressTogglerChange(this.customerShippingAddresses);
        this.toggleSaveNewAddress();
    }

    protected billingTogglerOnChange(): void {
        this.newBillingAddressChecked = this.addressTogglerChange(this.customerBillingAddresses);
        this.toggleSaveNewAddress();
    }

    protected initSaveNewAddressState(): void {
        this.newShippingAddressChecked = this.isSaveNewAddressOptionSelected(this.customerShippingAddresses);
        this.newBillingAddressChecked = this.isSaveNewAddressOptionSelected(this.customerBillingAddresses);
        this.toggleSaveNewAddress();
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
        this.saveNewAddressToggler.disabled = false;
    }

    /**
     * Checks if 'billing same as shipping' checkbox is checked.
     */
    get sameAsShippingChecked(): boolean {
        return this.sameAsShippingToggler.checked;
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
}

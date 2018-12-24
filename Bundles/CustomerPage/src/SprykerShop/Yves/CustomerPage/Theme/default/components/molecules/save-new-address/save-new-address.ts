import Component from 'ShopUi/models/component';

export default class SaveNewAddress extends Component {
    customerShippingAddresses: HTMLSelectElement;
    customerBillingAddresses: HTMLSelectElement;
    saveNewAddressToggler: HTMLInputElement;
    sameAsShippingToggler: HTMLInputElement;

    newShippingAddressChecked: boolean = false;
    newBillingAddressChecked: boolean = false;
    readonly hideClass: string = 'is-hidden';

    protected readyCallback(): void {
        if(this.shippingAddressTogglerSelector && this.billingAddressTogglerSelector) {
            this.customerShippingAddresses = <HTMLSelectElement>document.querySelector(this.shippingAddressTogglerSelector);
            this.customerBillingAddresses = <HTMLSelectElement>document.querySelector(this.billingAddressTogglerSelector);
        }

        this.saveNewAddressToggler = <HTMLInputElement>document.querySelector(this.saveAddressTogglerSelector);
        this.sameAsShippingToggler = <HTMLInputElement>document.querySelector(this.billingSameAsShippingAddressTogglerSelector);

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
        this.customerShippingAddresses.addEventListener('change', (event: Event) => this.shippingTogglerOnChange(event));
        this.customerBillingAddresses.addEventListener('change', (event: Event) => this.billingTogglerOnChange(event));
        this.sameAsShippingToggler.addEventListener('change', () => this.toggleSaveNewAddress());
    }

    protected shippingTogglerOnChange(event: Event): void {
        this.newShippingAddressChecked = this.addressTogglerChange(event);
        this.toggleSaveNewAddress();
    }

    protected billingTogglerOnChange(event: Event): void {
        this.newBillingAddressChecked = this.addressTogglerChange(event);
        this.toggleSaveNewAddress();
    }

    protected initSaveNewAddressState(): void {
        this.newShippingAddressChecked = this.isSaveNewAddressOptionSelected(this.customerShippingAddresses);
        this.newBillingAddressChecked = this.isSaveNewAddressOptionSelected(this.customerBillingAddresses);
        this.toggleSaveNewAddress();
    }

    protected addressTogglerChange(event: Event): boolean {
        const toggler = <HTMLSelectElement>event.srcElement;

        return this.isSaveNewAddressOptionSelected(toggler);
    }

    protected isSaveNewAddressOptionSelected(toggler: HTMLSelectElement): boolean {
        return !toggler.options[toggler.selectedIndex].value;
    }

    toggleSaveNewAddress(): void {
        if (this.newShippingAddressChecked || (this.newBillingAddressChecked && !this.sameAsShippingChecked)) {
            this.showSaveNewAddress();
            return;
        }

        this.hideSaveNewAddress();
    }

    hideSaveNewAddress(): void {
        this.classList.add(this.hideClass);
        this.saveNewAddressToggler.disabled = true;
    }

    showSaveNewAddress(): void {
        this.classList.remove(this.hideClass);
        this.saveNewAddressToggler.disabled = false;
    }

    get sameAsShippingChecked(): boolean {
        return this.sameAsShippingToggler.checked;
    }

    get shippingAddressTogglerSelector(): string {
        return this.getAttribute('shipping-address-toggler-selector');
    }

    get billingAddressTogglerSelector(): string {
        return this.getAttribute('billing-address-toggler-selector');
    }

    get billingSameAsShippingAddressTogglerSelector(): string {
        return this.getAttribute('billing-same-as-shipping-toggler-selector');
    }

    get saveAddressTogglerSelector(): string {
        return this.getAttribute('save-address-toggler-selector');
    }
}

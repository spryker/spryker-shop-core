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
        if(this.shippingAddressToglerSelector && this.billingAddressToglerSelector) {
            this.customerShippingAddresses = <HTMLSelectElement>document.querySelector(this.shippingAddressToglerSelector);
            this.customerBillingAddresses = <HTMLSelectElement>document.querySelector(this.billingAddressToglerSelector);
        }

        this.saveNewAddressToggler = <HTMLInputElement>document.querySelector(this.saveAddressTogglerSelector);
        this.sameAsShippingToggler = <HTMLInputElement>document.querySelector(this.billingSameAsShippingAddressTogglerSelector);

        this.hasCustomerAddresses();
    }

    protected hasCustomerAddresses(): void {
        if (!this.customerShippingAddresses) {
            this.showSaveNewAddress();
            return;
        }

        this.mapEvents();
        this.mapSameAsShippingTogglerEvent();
    }

    protected mapEvents(): void {
        this.mapShippingTogglerEvent();
        this.mapBillingTogglerEvent();
    }

    protected mapShippingTogglerEvent(): void {
        this.customerShippingAddresses.addEventListener('change', (event: Event) => {
            this.newShippingAddressChecked = this.onAddressTogglerChange(event);
            this.toggleSaveNewAddress();
        });

        this.newShippingAddressChecked = this.isSaveNewAddressOptionSelected(this.customerShippingAddresses);
        this.toggleSaveNewAddress();
    }

    protected mapBillingTogglerEvent(): void {
        this.customerBillingAddresses.addEventListener('change', (event: Event) => {
            this.newBillingAddressChecked = this.onAddressTogglerChange(event);
            this.toggleSaveNewAddress();
        });

        this.newBillingAddressChecked = this.isSaveNewAddressOptionSelected(this.customerBillingAddresses);
        this.toggleSaveNewAddress();
    }

    protected mapSameAsShippingTogglerEvent(): void {
        this.sameAsShippingToggler.addEventListener('change', () => this.toggleSaveNewAddress());
    }

    protected onAddressTogglerChange(event: Event): boolean {
        const toggler = <HTMLSelectElement>event.srcElement;

        return this.isSaveNewAddressOptionSelected(toggler);
    }

    protected isSaveNewAddressOptionSelected(toggler: HTMLSelectElement): boolean {
        return !toggler.options[toggler.selectedIndex].value;
    }

    public toggleSaveNewAddress(): void {
        if (this.newShippingAddressChecked || (this.newBillingAddressChecked && !this.sameAsShippingChecked)) {
            this.showSaveNewAddress();
            return;
        }

        this.hideSaveNewAddress();
    }

    public hideSaveNewAddress(): void {
        this.classList.add(this.hideClass);
        this.saveNewAddressToggler.disabled = true;
    }

    public showSaveNewAddress(): void {
        this.classList.remove(this.hideClass);
        this.saveNewAddressToggler.disabled = false;
    }

    get sameAsShippingChecked(): boolean {
        return this.sameAsShippingToggler.checked;
    }

    get shippingAddressToglerSelector(): string {
        return this.getAttribute('shipping-address-toggler-selector');
    }

    get billingAddressToglerSelector(): string {
        return this.getAttribute('billing-address-toggler-selector');
    }

    get billingSameAsShippingAddressTogglerSelector(): string {
        return this.getAttribute('billing-same-as-shipping-toggler-selector');
    }

    get saveAddressTogglerSelector(): string {
        return this.getAttribute('save-address-toggler-selector');
    }
}

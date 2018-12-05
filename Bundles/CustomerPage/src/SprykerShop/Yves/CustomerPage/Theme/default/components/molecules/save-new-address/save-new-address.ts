import Component from 'ShopUi/models/component';

export default class SaveNewAddress extends Component {
    customerShippingAddresses: HTMLInputElement[];
    customerBillingAddresses: HTMLInputElement[];
    saveNewAddressToggler: HTMLInputElement;
    sameAsShippingToggler: HTMLInputElement;

    newShippingAddressChecked: boolean = false;
    newBillingAddressChecked: boolean = false;
    readonly hideClass: string = 'is-hidden';

    constructor() {
        super();
    }

    protected readyCallback(): void {
        if (this.shippingAddressToglerSelector) {
            this.customerShippingAddresses = <HTMLInputElement[]>Array.from(document.querySelectorAll(this.shippingAddressToglerSelector));
            this.customerBillingAddresses = <HTMLInputElement[]>Array.from(document.querySelectorAll(this.billingAddressToglerSelector));
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

        this.mapTogglers();
        this.mapSameAsShippingTogglerEvent();
    }

    protected mapTogglers(): void {
        this.customerShippingAddresses.forEach((toggler: HTMLInputElement) => {
            this.mapShippingTogglerEvent(toggler);
        });

        this.customerBillingAddresses.forEach((toggler: HTMLInputElement) => {
            this.mapBillingTogglerEvent(toggler);
        });
    }

    protected mapShippingTogglerEvent(toggler: HTMLInputElement): void {
        toggler.addEventListener('change', (e: Event) => {
            this.newShippingAddressChecked = this.onAddressTogglerChange(e);
            this.toggleSaveNewAddress();
        });

        this.newShippingAddressChecked = this.hasSaveNewAddress(toggler);
        this.toggleSaveNewAddress();
    }

    protected mapBillingTogglerEvent(toggler: HTMLInputElement): void {
        toggler.addEventListener('change', (e: Event) => {
            this.newBillingAddressChecked = this.onAddressTogglerChange(e);
            this.toggleSaveNewAddress();
        });

        this.newBillingAddressChecked = this.hasSaveNewAddress(toggler);
        this.toggleSaveNewAddress();
    }

    protected mapSameAsShippingTogglerEvent(): void {
        this.sameAsShippingToggler.addEventListener('change', (e: Event) => this.toggleSaveNewAddress());
    }

    protected onAddressTogglerChange(e: Event): boolean {
        const toggler = <HTMLInputElement>e.srcElement;

        return this.hasSaveNewAddress(toggler);
    }

    protected hasShowSaveNewAddressAttribute(toggler: HTMLInputElement): boolean {
        return toggler.hasAttribute('data-show-save-new-address');
    }

    public toggleSaveNewAddress(): void {
        if (this.newShippingAddressChecked || (this.newBillingAddressChecked && !this.sameAsShippingChecked)) {
            this.showSaveNewAddress();
            return;
        }

        this.hideSaveNewAddress();
    }

    public hasSaveNewAddress(toggler: HTMLInputElement): boolean {
        return (this.hasShowSaveNewAddressAttribute(toggler) && toggler.checked);
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

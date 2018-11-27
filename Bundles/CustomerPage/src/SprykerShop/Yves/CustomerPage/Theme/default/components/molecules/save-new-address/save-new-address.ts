import Component from 'ShopUi/models/component';

export default class SaveNewAddress extends Component {
    customerShippingAddresses: HTMLInputElement[];
    customerBillingAddresses: HTMLInputElement[];
    saveNewAddressToggler: HTMLInputElement;
    sameAsShippingToggler: HTMLInputElement;

    sameIsShippingChecked: boolean;
    newShippingAddressChecked: boolean = false;
    newBillingAddressChecked: boolean = false;
    readonly hiddenClass: string = 'is-hidden';

    protected readyCallback(): void {
        if (this.shippingAddressToglerSelector) {
            this.customerShippingAddresses = <HTMLInputElement[]>Array.from(document.querySelectorAll(this.shippingAddressToglerSelector));
            this.customerBillingAddresses = <HTMLInputElement[]>Array.from(document.querySelectorAll(this.billingAddressToglerSelector));
        }

        this.saveNewAddressToggler = <HTMLInputElement>document.querySelector(this.saveAddressTogglerSelector);
        this.sameAsShippingToggler = <HTMLInputElement>document.querySelector(this.billingSameAsShippingAddressToglerSelector);
        this.sameIsShippingChecked = this.sameAsShippingToggler.checked;

        this.hasCustomerAddresses();
    }

    protected hasCustomerAddresses(): void {
        if(!this.customerShippingAddresses) {
            this.showSaveNewAddress();
            return;
        }

        this.mapTogglers(this.customerShippingAddresses);
        this.mapSameAsShippingTogglerEvent();
    }

    protected mapTogglers(togglersList: HTMLInputElement[]): void {
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
    }

    protected mapBillingTogglerEvent(toggler: HTMLInputElement): void {
        toggler.addEventListener('change', (e: Event) => {
            this.newBillingAddressChecked = this.onAddressTogglerChange(e);
            this.toggleSaveNewAddress();
        });
    }

    protected mapSameAsShippingTogglerEvent(): void {
        this.sameAsShippingToggler.addEventListener('change', (e: Event) => {
            this.onSameAsShippingTogglerChange(e);
            this.toggleSaveNewAddress();
        });
    }

    protected onAddressTogglerChange(e: Event): boolean {
        const toggler = <HTMLInputElement>e.srcElement;

        return this.checkSaveNewAddress(toggler);
    }

    protected onSameAsShippingTogglerChange(e: Event): boolean {
        const toggler = <HTMLInputElement>e.srcElement;
        if(toggler.checked) {
            this.sameIsShippingChecked = true;
            return
        }
        this.sameIsShippingChecked = false;
    }

    protected checkSaveNewAddress(toggler: HTMLInputElement): boolean {
        if(!this.togglerHasAddClassAttribute(toggler) && toggler.checked) {
            return true;
        }

        return false;
    }

    protected toggleSaveNewAddress(): void {
        if(this.newShippingAddressChecked || (this.newBillingAddressChecked && !this.sameIsShippingChecked)) {
            this.showSaveNewAddress();
            return;
        }

        this.hideSaveNewAddress();
    }

    protected hideSaveNewAddress(): void {
        this.classList.add(this.hiddenClass);
        this.saveNewAddressToggler.disabled = true;
    }

    protected showSaveNewAddress(): void {
        this.classList.remove(this.hiddenClass);
        this.saveNewAddressToggler.disabled = false;
    }

    protected togglerHasAddClassAttribute(toggler: HTMLInputElement): boolean {
        return toggler.hasAttribute('add-class-when-checked');
    }

    get shippingAddressToglerSelector(): string {
        return this.getAttribute('shipping-address-toggler');
    }

    get billingAddressToglerSelector(): string {
        return this.getAttribute('billing-address-toggler');
    }

    get billingSameAsShippingAddressToglerSelector(): string {
        return this.getAttribute('billing-same-as-shipping-toggler');
    }

    get saveAddressTogglerSelector(): string {
        return this.getAttribute('save-address-toggler');
    }
}

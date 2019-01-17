import Component from 'ShopUi/models/component';

const EVENT_USE_SELECTED_ADDRESS_TRIGGER = 'use-selected-address-trigger';

export default class SaveNewAddress extends Component {
    customerShippingAddresses: HTMLFormElement;
    customerBillingAddresses: HTMLFormElement;
    saveNewAddressToggler: HTMLInputElement;
    sameAsShippingToggler: HTMLInputElement;
    useShippingAddressTrigger: HTMLButtonElement;
    useBillingAddressTrigger: HTMLButtonElement;

    newShippingAddressChecked: boolean = false;
    newBillingAddressChecked: boolean = false;
    readonly hideClass: string = 'is-hidden';

    protected readyCallback(): void {
        if(this.shippingAddressTogglerSelector && this.billingAddressTogglerSelector) {
            this.customerShippingAddresses = <HTMLFormElement>document.querySelector(this.shippingAddressTogglerSelector);
            this.customerBillingAddresses = <HTMLFormElement>document.querySelector(this.billingAddressTogglerSelector);
        }

        if(this.shippingAddressTriggerSelector && this.billingAddressTriggerSelector) {
            this.useShippingAddressTrigger = <HTMLButtonElement>document.querySelector(this.shippingAddressTriggerSelector);
            this.useBillingAddressTrigger = <HTMLButtonElement>document.querySelector(this.billingAddressTriggerSelector);
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
        console.log(this.useShippingAddressTrigger);
        this.useShippingAddressTrigger.addEventListener(EVENT_USE_SELECTED_ADDRESS_TRIGGER, () => {
            console.log(11);
        });

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
        console.log(toggler.value);
        return !toggler.value;
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

    get shippingAddressTriggerSelector(): string {
        return this.getAttribute('shipping-address-trigger-selector');
    }

    get billingAddressTriggerSelector(): string {
        return this.getAttribute('billing-address-trigger-selector');
    }

    get billingSameAsShippingAddressTogglerSelector(): string {
        return this.getAttribute('billing-same-as-shipping-toggler-selector');
    }

    get saveAddressTogglerSelector(): string {
        return this.getAttribute('save-address-toggler-selector');
    }
}

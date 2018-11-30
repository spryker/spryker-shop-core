import SaveNewAddress from '../save-new-address/save-new-address';

export default class SaveNewBusinessUnitAddress extends SaveNewAddress {
    shippingDifferentDeliveryAddressCheckbox: HTMLInputElement;
    billingDifferentDeliveryAddressCheckbox: HTMLInputElement;

    protected readyCallback(): void {
        this.shippingDifferentDeliveryAddressCheckbox = <HTMLInputElement>document.querySelector(this.shippingAddressToglerSelector);
        this.billingDifferentDeliveryAddressCheckbox = <HTMLInputElement>document.querySelector(this.billingAddressToglerSelector);

        super.readyCallback();
        this.defaultSaveNewAddressBehaviour();
    }

    protected mapTogglers(): void {
        this.mapShippingTogglerEvent();
        this.mapBillingTogglerEvent();
    }

    protected mapShippingTogglerEvent(): void {
        this.shippingDifferentDeliveryAddressCheckbox.addEventListener('change', (e: Event) => {
            this.newShippingAddressChecked = this.onAddressTogglerChange(e);
            this.toggleSaveNewAddress();
        });
    }

    protected mapBillingTogglerEvent(): void {
        this.billingDifferentDeliveryAddressCheckbox.addEventListener('change', (e: Event) => {
            this.newBillingAddressChecked = this.onAddressTogglerChange(e);
            this.toggleSaveNewAddress();
        });
    }

    protected defaultSaveNewAddressBehaviour(): void {
        this.newShippingAddressChecked = this.hasSaveNewAddress(this.shippingDifferentDeliveryAddressCheckbox);
        this.newBillingAddressChecked = this.hasSaveNewAddress(this.billingDifferentDeliveryAddressCheckbox);
        this.toggleSaveNewAddress();
    }
}

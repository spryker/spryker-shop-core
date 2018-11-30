import SaveNewAddress from '../save-new-address/save-new-address';
import {log} from "ShopUi/app/logger";

export default class SaveNewBusinessUnitAddress extends SaveNewAddress {
    shippingDifferentDeliveryAddressCheckbox: HTMLInputElement;
    billingDifferentDeliveryAddressCheckbox: HTMLInputElement;
    shippingSelectedAddressButton: HTMLButtonElement;
    billingSelectedAddressButton: HTMLButtonElement;

    protected readyCallback(): void {
        this.shippingDifferentDeliveryAddressCheckbox = <HTMLInputElement>document.querySelector(this.shippingAddressToglerSelector);
        this.billingDifferentDeliveryAddressCheckbox = <HTMLInputElement>document.querySelector(this.billingAddressToglerSelector);
        this.shippingSelectedAddressButton = <HTMLButtonElement>document.querySelector(this.shippingSelectedAddressButtonSelector);
        this.billingSelectedAddressButton = <HTMLButtonElement>document.querySelector(this.billingSelectedAddressButtonSelector);

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

        this.shippingSelectedAddressButton.addEventListener('click', () => {
            this.newShippingAddressChecked = this.hasSaveNewAddress(this.shippingDifferentDeliveryAddressCheckbox);
            this.toggleSaveNewAddress();
        });
    }

    protected mapBillingTogglerEvent(): void {
        this.billingDifferentDeliveryAddressCheckbox.addEventListener('change', (e: Event) => {
            this.newBillingAddressChecked = this.onAddressTogglerChange(e);
            this.toggleSaveNewAddress();
        });

        this.billingSelectedAddressButton.addEventListener('click', () => {
            this.newBillingAddressChecked = this.hasSaveNewAddress(this.billingDifferentDeliveryAddressCheckbox);
            this.toggleSaveNewAddress();
        });
    }

    protected defaultSaveNewAddressBehaviour(): void {
        this.newShippingAddressChecked = this.hasSaveNewAddress(this.shippingDifferentDeliveryAddressCheckbox);
        this.newBillingAddressChecked = this.hasSaveNewAddress(this.billingDifferentDeliveryAddressCheckbox);
        this.toggleSaveNewAddress();
    }

    get shippingSelectedAddressButtonSelector(): string {
        return this.getAttribute('shipping-selected-address-button-selector');
    }

    get billingSelectedAddressButtonSelector(): string {
        return this.getAttribute('billing-selected-address-button-selector');
    }
}

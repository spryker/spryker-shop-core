import Component from 'ShopUi/models/component';

export default class SaveNewAddress extends Component {
    customerShippingAddresses: HTMLInputElement[];
    customerBillingAddresses: HTMLInputElement[];
    billingSameAsShipping: HTMLInputElement;
    saveNewAddress: HTMLInputElement;
    addNewShippingAddress: HTMLInputElement;
    addNewBillingAddress: HTMLInputElement;

    protected readyCallback(): void {
        this.customerShippingAddresses = <HTMLInputElement[]>Array.from(document.querySelectorAll('[name="addressesForm[shippingAddress][id_customer_address]"]'));
        this.customerBillingAddresses = <HTMLInputElement[]>Array.from(document.querySelectorAll('[name="addressesForm[billingAddress][id_customer_address]"]'));
        this.billingSameAsShipping = <HTMLInputElement>document.querySelector('[name="addressesForm[billingSameAsShipping]"]');
        this.saveNewAddress = <HTMLInputElement>document.querySelector('[name="addressesForm[skipAddressSaving]"]');
        this.addNewShippingAddress = this.customerShippingAddresses[this.customerShippingAddresses.length - 1];
        this.addNewBillingAddress = this.customerBillingAddresses[this.customerBillingAddresses.length - 1];

        if(!this.customerBillingAddresses.length && !this.customerBillingAddresses.length) {
            this.showSaveNewAddress();
        } else {
            this.toggleSaveNewAddress();
            this.mapEvents();
        }
    }

    protected mapEvents(): void {
        this.customerShippingAddresses.forEach((input: HTMLInputElement) => {
            input.addEventListener('change', () => this.toggleSaveNewAddress());
        });

        this.customerBillingAddresses.forEach((input: HTMLInputElement) => {
            input.addEventListener('change', () => this.toggleSaveNewAddress());
        });

        this.billingSameAsShipping.addEventListener('change', () => this.toggleSaveNewAddress());
    }

    protected toggleSaveNewAddress(): void {
        if(this.addNewShippingAddress.checked || (this.addNewBillingAddress.checked && !this.billingSameAsShipping.checked)) {
            this.showSaveNewAddress();
        } else {
            this.hideSaveNewAddress();
        }
    }

    protected hideSaveNewAddress(): void {
        this.classList.add('is-hidden');
        this.saveNewAddress.disabled = true;
    }

    protected showSaveNewAddress(): void {
        this.classList.remove('is-hidden');
        this.saveNewAddress.disabled = false;
    }
}

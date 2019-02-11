import Component from 'ShopUi/models/component';

export default class AddressFormToggler extends Component {
    /**
     * Element triggering the toggle action.
     */
    toggler: HTMLSelectElement;

    /**
     * Elements targeted by the toggle action.
     */
    form: HTMLFormElement;
    protected subForm: HTMLFormElement;
    protected formClear: HTMLInputElement;
    protected addNewAddress: HTMLButtonElement;
    protected formBillingAddress: HTMLElement;
    protected billingSameAsShipping: HTMLElement;
    protected billingSameAsShippingToggler: HTMLInputElement;

    protected readyCallback(): void {
        if(this.triggerSelector) {
            this.toggler = <HTMLSelectElement>document.querySelector(this.triggerSelector);
            this.form = <HTMLFormElement>document.querySelector(this.targetSelector);
            this.formBillingAddress = <HTMLElement>document.querySelector(this.formBillingAddressSelector);
            this.billingSameAsShipping = <HTMLElement>document.querySelector(this.billingSameAsShippingSelector);
            this.billingSameAsShippingToggler = <HTMLInputElement>document.querySelector(this.billingSameAsShippingTogglerSelector);

            if (this.subTargetSelector) {
                this.subForm = <HTMLFormElement>document.querySelector(this.subTargetSelector);
            }

            if (this.formClearSelector) {
                this.formClear = <HTMLInputElement>document.querySelector(this.formClearSelector);
            }

            if (this.formAddNewAddressSelector) {
                this.addNewAddress = <HTMLButtonElement>document.querySelector(this.formAddNewAddressSelector);
            }

            this.onTogglerChange();
            this.mapEvents();
        }
    }

    protected mapEvents(): void {
        this.toggler.addEventListener('change', () => this.onTogglerChange());
    }

    protected onTogglerChange(): void {
        const selectedOption = <string>this.toggler.options[this.toggler.selectedIndex].value;

        if (selectedOption === this.optionValueDeliverToMultipleAddresses) {
            this.toggleSubForm();
        } else {
            this.toggleForm(!!selectedOption);
        }
    }

    protected toggleSubForm(): void {
        this.form.classList.add(this.classToToggle);

        if (this.subForm) {
            this.subForm.classList.remove(this.classToToggle);
            this.formBillingAddress.classList.remove(this.classToToggle);
            this.billingSameAsShipping.classList.add(this.classToToggle);
            this.billingSameAsShippingToggler.checked = false;

            if (this.formClear && this.addNewAddress) {
                this.formClear.classList.add(this.classToToggle);
                this.addNewAddress.classList.add(this.classToToggle);
            }
        }
    }

    protected toggleForm(isShown: boolean): void {
        const hasCompanyBusinessUnitAddress = (this.hasCompanyBusinessUnitAddress == 'true');

        this.form.classList.toggle(this.classToToggle, hasCompanyBusinessUnitAddress ? false : isShown);

        if (this.subForm) {
            this.subForm.classList.add(this.classToToggle);
            this.billingSameAsShipping.classList.remove(this.classToToggle);

            if (this.formClear && this.addNewAddress) {
                this.formClear.classList.remove(this.classToToggle);
                this.addNewAddress.classList.remove(this.classToToggle);
            }
        }
    }

    /**
     * Gets a querySelector of the trigger element.
     */
    get triggerSelector(): string {
        return this.getAttribute('trigger-selector');
    }

    /**
     * Gets a querySelector of the target element.
     */
    get targetSelector(): string {
        return this.getAttribute('target-selector');
    }

    /**
     * Gets a querySelector of the sub target element.
     */
    get subTargetSelector(): string {
        return this.getAttribute('sub-target-selector');
    }

    /**
     * Gets a querySelector of the 'form clear' custom element.
     */
    get formClearSelector(): string {
        return this.getAttribute('form-clear-selector');
    }

    /**
     * Gets a querySelector of the trigger button element.
     */
    get formAddNewAddressSelector(): string {
        return this.getAttribute('form-add-new-address-selector');
    }

    /**
     * Gets a querySelector of the billing address form element.
     */
    get formBillingAddressSelector(): string {
        return this.getAttribute('form-billing-address-selector');
    }

    /**
     * Gets a querySelector of the 'billing same as shipping' element.
     */
    get billingSameAsShippingSelector(): string {
        return this.getAttribute('billing-same-as-shipping-selector');
    }

    /**
     * Gets a querySelector of the 'billing same as shipping' checkbox element.
     */
    get billingSameAsShippingTogglerSelector(): string {
        return this.getAttribute('billing-same-as-shipping-toggler-selector');
    }

    /**
     * Gets a class name for the toggle action.
     */
    get classToToggle(): string {
        return this.getAttribute('class-to-toggle');
    }

    /**
     * Gets if the company business unit address is defined.
     */
    get hasCompanyBusinessUnitAddress(): string {
        return this.getAttribute('has-company-business-unit-address');
    }

    /**
     * Gets if the split delivery form is defined.
     */
    get optionValueDeliverToMultipleAddresses(): string {
        return this.getAttribute('toggle-option-value');
    }
}

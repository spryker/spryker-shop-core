import Component from 'ShopUi/models/component';

const EVENT_TOGGLE_FORM = 'toggleForm';

/**
 * @event toggleForm An event emitted when the component performs a toggle of form container.
 */
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
    protected containerBillingAddress: HTMLElement;
    protected billingSameAsShipping: HTMLElement;
    protected billingSameAsShippingToggler: HTMLInputElement;

    protected readyCallback(): void {
        if (this.triggerSelector) {
            this.toggler = <HTMLSelectElement>document.querySelector(this.triggerSelector);
            this.form = <HTMLFormElement>document.querySelector(this.targetSelector);
            this.containerBillingAddress = <HTMLElement>document.querySelector(this.containerBillingAddressSelector);
            this.billingSameAsShipping = <HTMLElement>document.querySelector(this.billingSameAsShippingSelector);
            this.billingSameAsShippingToggler = <HTMLInputElement>document.querySelector(
                this.billingSameAsShippingTogglerSelector
            );

            if (this.subTargetSelector) {
                this.subForm = <HTMLFormElement>document.querySelector(this.subTargetSelector);
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
            this.containerBillingAddress.classList.remove(this.classToToggle);
            this.billingSameAsShipping.classList.add(this.classToToggle);
            this.billingSameAsShippingToggler.checked = false;
        }
    }

    protected toggleForm(isShown: boolean): void {
        this.form.classList.toggle(this.classToToggle, isShown);

        if (this.subForm) {
            this.subForm.classList.add(this.classToToggle);
            this.billingSameAsShipping.classList.remove(this.classToToggle);
        }

        this.dispatchCustomEvent(EVENT_TOGGLE_FORM);
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
     * Gets a querySelector of the billing address form element.
     */
    get containerBillingAddressSelector(): string {
        return this.getAttribute('container-billing-address-selector');
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
     * Gets if the split delivery form is defined.
     */
    get optionValueDeliverToMultipleAddresses(): string {
        return this.getAttribute('toggle-option-value');
    }
}

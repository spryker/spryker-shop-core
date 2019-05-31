import Component from 'ShopUi/models/component';

export default class ValidateNextCheckoutStep extends Component {
    protected target: HTMLButtonElement;
    protected triggers: HTMLSelectElement[];
    protected shippingAddressToggler: HTMLSelectElement;

    protected readyCallback(): void {
        this.target = <HTMLButtonElement>document.querySelector(this.targetSelector);

        if (this.triggerSelector) {
            this.triggers = <HTMLSelectElement[]>Array.from(document.querySelectorAll(this.triggerSelector));
        }

        this.shippingAddressToggler = <HTMLSelectElement>document.querySelector(this.shippingAddressTogglerSelector);

        if (this.isSplitDeliveryFormEnabled) {
            this.initFormFieldsState();
        }
        this.mapEvents();
    }

    protected mapEvents(): void {
        this.triggers.forEach((element: HTMLSelectElement) => {
            element.addEventListener('change', () => this.onTriggerChange(element));
        });

        this.shippingAddressToggler.addEventListener('change', () => {
            this.onShippingAddressTogglerChange();
        });
    }

    protected onTriggerChange(element: HTMLSelectElement): void {
        this.checkFormFieldsState(element);
    }

    protected onShippingAddressTogglerChange(): void {
        if (this.isSplitDeliveryFormEnabled) {
            this.initFormFieldsState();
        }
    }

    protected initFormFieldsState(): void {
        this.triggers.forEach((element: HTMLSelectElement) => {
            this.checkFormFieldsState(element);
        });
    }

    protected checkFormFieldsState(element: HTMLSelectElement): void {
        const currentValue = element.options[element.selectedIndex].value;
        const splitDeliveryFormSelector = <string>element.getAttribute('form-target-selector');
        const splitDeliveryForm = <HTMLElement>document.querySelector(splitDeliveryFormSelector);
        const requiredFormFieldSelectors = `select[required], input[required]`;
        const requiredFormFields = <HTMLElement[]>Array.from(splitDeliveryForm.querySelectorAll(
            requiredFormFieldSelectors
        ));

        if (currentValue.length > 0) {
            return;
        }

        console.log(element);

        requiredFormFields.forEach((element: HTMLFormElement) => {
            // console.log(element.value);
        });
    }

    get isSplitDeliveryFormEnabled(): boolean {
        const currentValue = <string>this.shippingAddressToggler.options[this.shippingAddressToggler.selectedIndex].value;

        return currentValue === this.toggleOptionValue;
    }

    get triggerSelector(): string {
        return this.getAttribute('trigger-selector');
    }

    get targetSelector(): string {
        return this.getAttribute('target-selector');
    }

    get shippingAddressTogglerSelector(): string {
        return this.getAttribute('shipping-address-toggler-selector');
    }

    get toggleOptionValue(): string {
        return this.getAttribute('toggle-option-value');
    }
}

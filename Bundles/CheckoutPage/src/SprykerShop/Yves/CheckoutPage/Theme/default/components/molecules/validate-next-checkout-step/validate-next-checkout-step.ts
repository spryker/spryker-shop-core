import Component from 'ShopUi/models/component';

export default class ValidateNextCheckoutStep extends Component {
    protected target: HTMLButtonElement;
    protected triggers: HTMLSelectElement[];
    protected shippingAddressToggler: HTMLSelectElement;
    protected readonly requiredFormFieldSelectors: string = `select[required], input[required]`;

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
        if (!this.triggers.length) {
            const splitDeliveryForm = <HTMLElement>document.querySelector(this.splitDeliveryFormSelector);
            const requiredFormFields = <HTMLFormElement[]>Array.from(splitDeliveryForm.querySelectorAll(
                this.requiredFormFieldSelectors
            ));

            requiredFormFields.forEach((element: HTMLFormElement) => {
                this.toggleVisibilityNextStepButton(element);
                console.log(element);

                element.addEventListener('change', () => {
                    console.log(element);

                    this.toggleVisibilityNextStepButton(element);
                });
            });

            return;
        }

        this.triggers.forEach((element: HTMLSelectElement) => {
            this.checkFormFieldsState(element);
        });
    }

    protected checkFormFieldsState(element: HTMLSelectElement): void {
        const currentValue = element.options[element.selectedIndex].value;
        const splitDeliveryFormSelector = <string>element.getAttribute('form-target-selector');
        const splitDeliveryForm = <HTMLElement>document.querySelector(splitDeliveryFormSelector);
        const requiredFormFields = <HTMLFormElement[]>Array.from(splitDeliveryForm.querySelectorAll(
            this.requiredFormFieldSelectors
        ));

        if (currentValue.length > 0) {
            this.target.disabled = false;

            return;
        }

        // requiredFormFields.forEach((element: HTMLFormElement) => {
        //     this.toggleVisibilityNextStepButton(element);
        //
        //     element.addEventListener('change', () => {
        //         this.toggleVisibilityNextStepButton(element);
        //     });
        // });
    }

    protected toggleVisibilityNextStepButton(element: HTMLFormElement): void {
        console.log(element);
        if (element.value) {
            this.target.disabled = false;

            return;
        }

        this.target.disabled = true;
    }

    get isSplitDeliveryFormEnabled(): boolean {
        const currentValue = <string>this.shippingAddressToggler.options[this.shippingAddressToggler.selectedIndex].value;

        return currentValue === this.toggleOptionValue;
    }

    get splitDeliveryFormSelector(): string {
        return this.getAttribute('split-delivery-form-selector');
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

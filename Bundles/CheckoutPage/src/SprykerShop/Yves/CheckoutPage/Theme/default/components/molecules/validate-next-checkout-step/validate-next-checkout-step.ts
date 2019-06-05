import Component from 'ShopUi/models/component';

export default class ValidateNextCheckoutStep extends Component {
    protected forms: HTMLElement[];
    protected triggers: HTMLFormElement[];
    // protected target: HTMLButtonElement;
    // protected triggers: HTMLSelectElement[];
    // protected shippingAddressToggler: HTMLSelectElement;
    protected readonly requiredFormFieldSelectors: string = `select[required], input[required]`;

    protected readyCallback(): void {
        this.forms = <HTMLElement[]>Array.from(document.querySelectorAll(this.formSelector));
        // this.target = <HTMLButtonElement>document.querySelector(this.targetSelector);
        //
        // if (this.triggerSelector) {
        //     this.triggers = <HTMLSelectElement[]>Array.from(document.querySelectorAll(this.triggerSelector));
        // }
        //
        // this.shippingAddressToggler = <HTMLSelectElement>document.querySelector(this.shippingAddressTogglerSelector);
        //
        // if (this.isSplitDeliveryFormEnabled) {
        //     this.initFormFieldsState();
        // }
        if (this.isTriggerEnable) {
            this.enableTrigger();
        }
    }

    protected mapEvents(): void {
        if (this.triggers) {
            this.triggers.forEach((element: HTMLFormElement) => {
                element.addEventListener('change', () => this.onTriggerChange(element));
            });
        }
        //
        // this.shippingAddressToggler.addEventListener('change', () => {
        //     this.onShippingAddressTogglerChange();
        // });
    }

    enableTrigger(): void {
        this.fillFormFieldsCollection();
        this.mapEvents();
    }

    protected fillFormFieldsCollection(): void {
        console.log(this.forms);
        this.forms.forEach((element: HTMLElement) => {
            if (element.offsetHeight) {
                this.triggers = <HTMLFormElement[]>Array.from(element.querySelectorAll(this.requiredFormFieldSelectors));
            }
        });
    }

    protected isFormFieldsFilled(): boolean {
        return this.triggers.some((element: HTMLFormElement) => !element.value);
    }

    protected onTriggerChange(element: HTMLFormElement): void {
        this.fillFormFieldsCollection();
        this.isFormFieldsFilled();

        console.log(1234);
    }
    //
    // protected onShippingAddressTogglerChange(): void {
    //     if (this.isSplitDeliveryFormEnabled) {
    //         this.initFormFieldsState();
    //     }
    // }

    // protected initFormFieldsState(): void {
    //     if (!this.triggers.length) {
    //         const splitDeliveryForm = <HTMLElement>document.querySelector(this.splitDeliveryFormSelector);
    //         const requiredFormFields = <HTMLFormElement[]>Array.from(splitDeliveryForm.querySelectorAll(
    //             this.requiredFormFieldSelectors
    //         ));
    //
    //         requiredFormFields.forEach((element: HTMLFormElement) => {
    //             this.toggleVisibilityNextStepButton(element);
    //             console.log(element);
    //
    //             element.addEventListener('change', () => {
    //                 console.log(element);
    //
    //                 this.toggleVisibilityNextStepButton(element);
    //             });
    //         });
    //
    //         return;
    //     }
    //
    //     this.triggers.forEach((element: HTMLSelectElement) => {
    //         this.checkFormFieldsState(element);
    //     });
    // }

    // protected checkFormFieldsState(element: HTMLSelectElement): void {
    //     const currentValue = element.options[element.selectedIndex].value;
    //     const splitDeliveryFormSelector = <string>element.getAttribute('form-target-selector');
    //     const splitDeliveryForm = <HTMLElement>document.querySelector(splitDeliveryFormSelector);
    //     const requiredFormFields = <HTMLFormElement[]>Array.from(splitDeliveryForm.querySelectorAll(
    //         this.requiredFormFieldSelectors
    //     ));
    //
    //     if (currentValue.length > 0) {
    //         this.target.disabled = false;
    //
    //         return;
    //     }
    //
    //     requiredFormFields.forEach((element: HTMLFormElement) => {
    //         this.toggleVisibilityNextStepButton(element);
    //
    //         element.addEventListener('change', () => {
    //             this.toggleVisibilityNextStepButton(element);
    //         });
    //     });
    // }

    // protected toggleVisibilityNextStepButton(element: HTMLFormElement): void {
    //     console.log(element);
    //     if (element.value) {
    //         this.target.disabled = false;
    //
    //         return;
    //     }
    //
    //     this.target.disabled = true;
    // }
    //
    // get isSplitDeliveryFormEnabled(): boolean {
    //     const currentValue = <string>this.shippingAddressToggler.options[this.shippingAddressToggler.selectedIndex].value;
    //
    //     return currentValue === this.toggleOptionValue;
    // }
    //
    // get splitDeliveryFormSelector(): string {
    //     return this.getAttribute('split-delivery-form-selector');
    // }
    //
    // get triggerSelector(): string {
    //     return this.getAttribute('trigger-selector');
    // }
    //
    // get targetSelector(): string {
    //     return this.getAttribute('target-selector');
    // }
    //
    // get shippingAddressTogglerSelector(): string {
    //     return this.getAttribute('shipping-address-toggler-selector');
    // }
    //
    // get toggleOptionValue(): string {
    //     return this.getAttribute('toggle-option-value');
    // }

    get formSelector(): string {
        return this.getAttribute('form-selector');
    }

    get isTriggerEnable(): boolean {
        return this.hasAttribute('is-enable');
    }
}

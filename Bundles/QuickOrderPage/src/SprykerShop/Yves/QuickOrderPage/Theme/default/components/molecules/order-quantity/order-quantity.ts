import Component from 'ShopUi/models/component';
import QuickOrderFormField from '../quick-order-form-field/quick-order-form-field';
import debounce from 'lodash-es/debounce';

export default class OrderQuantity extends Component {
    quantityInput: HTMLInputElement;
    currentFieldComponent: QuickOrderFormField;
    quantityInputUpdate: CustomEvent;
    errorMessage: HTMLElement;
    errorMessageTimerId: number;

    protected readyCallback(): void {
        this.currentFieldComponent = <QuickOrderFormField>this.closest('quick-order-form-field');
        this.quantityInput = <HTMLInputElement>this.querySelector(`.${this.jsName}__input`);
        this.errorMessage = <HTMLInputElement>this.querySelector(`.${this.jsName}__error`);

        this.mapEvents();
    }

    private mapEvents(): void {
        this.createCustomEvents();
        this.currentFieldComponent.addEventListener('product-loaded-event', () => this.onLoad());
        this.currentFieldComponent.addEventListener('product-delete-event', () => this.resetAttrValues(''));
        this.quantityInput.addEventListener('input', debounce(() => this.validateInputValue(), 500));
    }

    private createCustomEvents(): void {
        this.quantityInputUpdate = <CustomEvent>new CustomEvent("quantity-input-update", {
            detail: {
                username: "quantity-input-update"
            }
        });
    }

    private onLoad(): void {
        const productQuantityStorage = this.currentFieldComponent.productData.productQuantityStorage;

        if(productQuantityStorage) {
            this.quantityInputAttributes(
                String(productQuantityStorage.quantityMin),
                String(productQuantityStorage.quantityMin),
                String(productQuantityStorage.quantityMax),
                String(productQuantityStorage.quantityInterval)
            );
            return;
        }

        this.resetAttrValues('1');
    }

    private resetAttrValues(inputValue: string): void {
        this.quantityInputAttributes('1', inputValue,'','1');
    }

    private quantityInputAttributes(min: string, value: string, max: string, step: string): void {
        this.setAttrValue('min', min);
        this.setAttrValue('max', max);
        this.setAttrValue('step', step);
        this.quantityInput.value = value;
        this.dispatchEvent(this.quantityInputUpdate);
    }

    private setAttrValue(attr: string, value: string): void {
        this.quantityInput.setAttribute(attr, value == "null" ? '' : value);
    }

    private validateInputValue(): void {
        if(this.currentInputValue % this.stepAttrValue !== 0) {
            this.roundOfQuantityInputValue();
            this.showErrorMessage();
        }

        this.dispatchEvent(this.quantityInputUpdate);
    }

    private roundOfQuantityInputValue(): void {
        let inputValue: number = <number>this.currentInputValue;

        while(inputValue % this.stepAttrValue !== 0) {
            inputValue++;
        }

        this.quantityInput.value = <string>String(inputValue);
    }

    private showErrorMessage(): void {
        clearTimeout(<number>this.errorMessageTimerId);

        if(!this.errorMessage.matches(`.${this.showErrorMessageClass}`)) {
            this.errorMessage.classList.add(this.showErrorMessageClass);
        }

        this.errorMessageTimerId = <number>setTimeout(() => this.errorMessage.classList.remove(this.showErrorMessageClass), 5000);
    }

    get currentInputValue(): number {
        return <number>Number(this.quantityInput.value);
    }

    get stepAttrValue(): number {
        return <number>Number(this.quantityInput.getAttribute('step'));
    }

    get showErrorMessageClass(): string {
        return <string>`${ this.name }__error--show`;
    }
}

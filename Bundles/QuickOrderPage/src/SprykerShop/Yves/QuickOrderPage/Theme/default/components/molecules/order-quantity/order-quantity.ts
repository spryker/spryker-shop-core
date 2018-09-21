import Component from 'ShopUi/models/component';
import QuickOrderFormField from '../quick-order-form-field/quick-order-form-field';
import debounce from 'lodash-es/debounce';

export default class OrderQuantity extends Component {
    quantityInput: HTMLInputElement;
    currentFieldComponent: QuickOrderFormField;
    quantityInputUpdate: CustomEvent;
    errorMessage: HTMLElement;
    errorMessageTimerId: number;
    inputAttributes: string[];
    inputAttributesDefault: string[];

    protected readyCallback(): void {
        this.currentFieldComponent = <QuickOrderFormField>this.closest('quick-order-form-field');
        this.quantityInput = <HTMLInputElement>this.querySelector(`.${this.jsName}__input`);
        this.errorMessage = <HTMLInputElement>this.querySelector(`.${this.jsName}__error`);
        this.inputAttributesDefault = ['1', '', '1', ''];

        this.mapEvents();
    }

    private mapEvents(): void {
        this.createCustomEvents();
        this.currentFieldComponent.addEventListener('product-loaded-event', () => this.onLoad());
        this.currentFieldComponent.addEventListener('product-delete-event', () => this.defaultAttributesValues());
        this.quantityInput.addEventListener('input', debounce(() => this.validateInputValue(), 500));
    }

    private createCustomEvents(): void {
        this.quantityInputUpdate = <CustomEvent>new CustomEvent("quantity-input-update");
    }

    private onLoad(): void {
        const productQuantityStorage = this.currentFieldComponent.productData.productQuantityStorage;

        if(productQuantityStorage) {
            this.quantityInputAttributes([
                String(productQuantityStorage.quantityMin),
                String(productQuantityStorage.quantityMax),
                String(productQuantityStorage.quantityInterval),
                String(productQuantityStorage.quantityMin)
            ]);
            return;
        }

        this.defaultAttributesValues();
    }

    private defaultAttributesValues(): void {
        this.quantityInputAttributes(this.inputAttributesDefault);
    }

    private quantityInputAttributes([min, max, step, value]: string[]): void {
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
        if(this.currentInputValue % this.stepAttributeValue !== 0) {
            this.roundOfQuantityInputValue();
            this.showErrorMessage();
        }

        this.dispatchEvent(this.quantityInputUpdate);
    }

    private roundOfQuantityInputValue(): void {
        let inputValue: number = <number>this.currentInputValue;

        while(inputValue % this.stepAttributeValue !== 0) {
            inputValue++;
        }

        this.quantityInput.value = String(inputValue);
    }

    private showErrorMessage(): void {
        clearTimeout(<number>this.errorMessageTimerId);

        if(!this.errorMessage.matches(`.${this.showErrorMessageClass}`)) {
            this.errorMessage.classList.add(this.showErrorMessageClass);
        }

        this.errorMessageTimerId = <number>setTimeout(() => this.errorMessage.classList.remove(this.showErrorMessageClass), 5000);
    }

    get currentInputValue(): number {
        return Number(this.quantityInput.value);
    }

    get stepAttributeValue(): number {
        return Number(this.quantityInput.getAttribute('step'));
    }

    get showErrorMessageClass(): string {
        return `${ this.name }__error--show`;
    }
}

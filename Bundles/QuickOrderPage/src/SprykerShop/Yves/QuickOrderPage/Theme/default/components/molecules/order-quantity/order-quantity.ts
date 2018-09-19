import Component from 'ShopUi/models/component';
import QuickOrderFormField from '../quick-order-form-field/quick-order-form-field';

export default class OrderQuantity extends Component {
    quantityInput: HTMLInputElement;
    currentFieldComponent: QuickOrderFormField;
    quantityInputUpdate: CustomEvent;
    errorMessage: HTMLElement;

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
        this.quantityInput.addEventListener('input', () => this.validateInputValue());
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
    }

    private resetAttrValues(inputValue: string): void {
        this.quantityInputAttributes('1',inputValue,'','1');
    }

    private quantityInputAttributes(min: string, value: string, max: string, step: string): void {
        this.setAttrValue('min', min);
        this.setAttrValue('value', value);
        this.setAttrValue('max', max);
        this.setAttrValue('step', step);
        this.dispatchEvent(this.quantityInputUpdate);
    }

    private setAttrValue(attr: string, value: string): void {
        this.quantityInput.setAttribute(attr, value == "null" ? '' : value);
    }

    private validateInputValue(): void {
        if(this.currentInputValue % this.stepAttrValue !== 0) {

        }

        this.dispatchEvent(this.quantityInputUpdate)
    }

    get currentInputValue(): number {
        return Number(this.quantityInput.value);
    }

    get stepAttrValue(): number {
        return Number(this.quantityInput.getAttribute('step'));
    }
}

import Component from 'ShopUi/models/component';

export default class SourcePriceForm extends Component {
    protected input: HTMLInputElement;
    protected inputContainer: HTMLElement;
    protected checkbox: HTMLInputElement;

    protected readyCallback(): void {
        this.input = <HTMLInputElement>this.querySelector(`.${this.jsName}__input-container .input`);
        this.inputContainer = <HTMLElement>this.getElementsByClassName(`${this.jsName}__input-container`)[0];
        this.checkbox = <HTMLInputElement>this.getElementsByClassName(`${this.jsName}__checkbox-container`)[0];
        this.setSourcePrice();
        this.mapEvents();
    }

    protected mapEvents(): void {
        this.input.addEventListener('input', (event: Event) => this.onInputType(event));
        this.checkbox.addEventListener('change', (event: Event) => this.onCheckboxChange(event));
    }

    protected setSourcePrice(): void {
        if (!this.sourcePrice.length) {
            return;
        }

        this.inputValue = String(this.sourcePrice);
        this.inputContainer.classList.remove('is-hidden');
    }

    protected onInputType(event: Event): void {
        this.checkboxChecked = this.inputValueLength === 0;
        if (this.checkboxChecked) {
            this.inputContainer.classList.add('is-hidden');
        }
    }

    protected onCheckboxChange(event: Event): void {
        if (this.checkboxChecked) {
            this.inputValue = '';
            this.inputContainer.classList.add('is-hidden');

            return;
        }

        this.setSourcePrice();
        this.inputContainer.classList.remove('is-hidden');
        this.input.focus();
    }

    /**
     * Gets an input value length.
     */
    get inputValueLength(): number {
        return this.input.value.length;
    }

    /**
     * Gets the source price value of the product.
     */
    get sourcePrice(): string {
        return this.getAttribute('price');
    }

    /**
     * Gets if the checkbox is checked.
     */
    get checkboxChecked(): boolean {
        return this.checkbox.checked;
    }

    /**
     * Sets an input value.
     * @param value A typed text in the input field.
     */
    set inputValue(value: string) {
        this.input.value = value;
    }

    /**
     * Sets an checkbox checked attribute.
     * @param value A boolean value of the checkecd attribute.
     */
    set checkboxChecked(value: boolean) {
        this.checkbox.checked = value;
    }
}

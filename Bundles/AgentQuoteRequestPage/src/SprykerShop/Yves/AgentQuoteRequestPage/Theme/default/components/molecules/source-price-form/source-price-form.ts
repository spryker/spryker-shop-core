import Component from 'ShopUi/models/component';

export default class SourcePriceForm extends Component {
    protected input: HTMLInputElement;
    protected checkbox: HTMLInputElement;

    protected readyCallback(): void {
        this.input = <HTMLInputElement>this.querySelector(`.${this.jsName}__input-container .input`);
        this.checkbox = <HTMLInputElement>this.querySelector(`.${this.jsName}__checkbox-container .checkbox__input`);
        this.mapEvents();
    }

    protected mapEvents(): void {
        this.input.addEventListener('input', (event: Event) => this.onInputType(event));
        this.checkbox.addEventListener('change', (event: Event) => this.onCheckboxChange(event));
    }

    protected onInputType(event: Event): void {
        this.checkboxChecked = this.inputValueLength === 0;
    }

    protected onCheckboxChange(event: Event): void {
        if (this.checkboxChecked) {
            this.inputValue = '';
        }
    }

    /**
     * Gets an input value length.
     */
    get inputValueLength(): number {
        return this.input.value.length;
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

import Component from '../../../models/component';
import AutoNumeric from 'autonumeric';

export const EVENT_FORMATTED_NUMBER = 'formattedNumber';

export default class FormattedNumberInput extends Component {
    protected input: HTMLInputElement;
    protected hiddenInput: HTMLInputElement;
    protected formattedInput: AutoNumeric;
    protected autoNumericConfig: AutoNumeric.Options;
    protected inputChangeEvent: CustomEvent = new CustomEvent(EVENT_FORMATTED_NUMBER);

    protected readyCallback(): void {}

    protected init(): void {
        this.input = <HTMLInputElement>this.getElementsByClassName(`${this.jsName}__input`)[0];
        this.hiddenInput = <HTMLInputElement>this.getElementsByClassName(`${this.jsName}__hidden-input`)[0];

        this.autoNumericConfig = {
            digitGroupSeparator: this.digitGroupSeparator,
            decimalCharacter: this.decimalCharacter,
            decimalPlaces: this.decimalPlaces,
            allowDecimalPadding: this.allowDecimalPadding,
            digitalGroupSpacing: '3',
            modifyValueOnWheel: false,
            watchExternalChanges: this.watchExternalChanges,
        };

        this.initInputFormatter();
        this.mapEvents();
    }

    protected mapEvents(): void {
        this.mapInputEvents();
    }

    protected mapInputEvents(): void {
        this.input.addEventListener('input', () => this.onInput());
        this.input.addEventListener('change', () => this.onChange());
    }

    protected onInput(): void {
        this.updateHiddenInput();
    }

    protected onChange(): void {
        this.validate();
        this.input.dispatchEvent(this.inputChangeEvent);
    }

    protected validate(): void {
        if (this.unformattedValue < this.min) {
            this.formattedInput.set(this.min);
            this.updateHiddenInput();
        }

        if (this.unformattedValue > this.max) {
            this.formattedInput.set(this.max);
            this.updateHiddenInput();
        }
    }

    protected initInputFormatter(): void {
        this.formattedInput = new AutoNumeric(this.input, this.autoNumericConfig);
    }

    protected updateHiddenInput(value: number = this.unformattedValue): void {
        this.hiddenInput.value = String(value);
    }

    protected get digitGroupSeparator(): string {
        return this.getAttribute('grouping-separator');
    }

    protected get decimalCharacter(): string {
        return this.getAttribute('decimal-separator');
    }

    protected get decimalPlaces(): number {
        return Number(this.getAttribute('decimal-rounding'));
    }

    protected get allowDecimalPadding(): boolean {
        return this.hasAttribute('decimal-filling');
    }

    /**
     * Gets the current unformated value of the component.
     */
    get unformattedValue(): number {
        return Number(this.formattedInput.getNumericString());
    }

    /**
     * Gets the number format configuration.
     */
    get numberFormatConfig(): AutoNumeric.Options {
        return this.autoNumericConfig;
    }

    protected get min(): number {
        return Number(this.getAttribute('min') ?? NaN);
    }

    protected get max(): number {
        return Number(this.getAttribute('max') ?? NaN);
    }

    protected get watchExternalChanges(): boolean {
        return this.hasAttribute('watch-external-changes');
    }
}

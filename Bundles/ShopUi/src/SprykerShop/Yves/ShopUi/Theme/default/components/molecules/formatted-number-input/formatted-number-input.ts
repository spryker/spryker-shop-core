import Component from '../../../models/component';
import AutoNumeric from 'autonumeric';

export default class FormattedNumberInput extends Component {
    protected input: HTMLInputElement;
    protected hiddenInput: HTMLInputElement;
    protected formattedInput: AutoNumeric;
    protected autoNumericConfig: object;

    protected readyCallback(): void {}

    protected init(): void {
        this.input = <HTMLInputElement>this.getElementsByClassName(`${this.jsName}__input`)[0];
        this.hiddenInput = <HTMLInputElement>this.getElementsByClassName(`${this.jsName}__hidden-input`)[0];
        this.autoNumericConfig = {
            digitGroupSeparator: this.digitGroupSeparator,
            decimalCharacter: this.decimalCharacter,
            decimalPlaces: this.decimalPlaces,
            allowDecimalPadding: this.allowDecimalPadding,
            digitalGroupSpacing: 3,
            modifyValueOnWheel: false,
            watchExternalChanges: this.watchExternalChanges,
        };

        this.initInputFormatter();
        this.mapEvents();
    }

    protected mapEvents(): void {
        this.mapInputEvent();
    }

    protected mapInputEvent(): void {
        this.input.addEventListener('change', () => this.onChange());
    }

    protected onChange(): void {
        this.validate();
        this.updateHiddenInput(this.getUnformattedValue);
    }

    protected validate(): void {
        if (this.getUnformattedValue < this.min) {
            this.formattedInput.set(this.min);
        }

        if (this.getUnformattedValue > this.max) {
            this.formattedInput.set(this.max);
        }
    }

    protected initInputFormatter(): void {
        this.formattedInput = new AutoNumeric(this.input, this.autoNumericConfig);
    }

    protected updateHiddenInput(value: number): void {
        this.hiddenInput.value = String(value);
    }

    protected get digitGroupSeparator(): string {
        return this.getAttribute('grouping-separator');
    }

    protected get decimalCharacter(): string {
        return this.getAttribute('decimal-separator');
    }

    protected get decimalPlaces(): string {
        return this.getAttribute('decimal-rounding');
    }

    protected get allowDecimalPadding(): boolean {
        return this.hasAttribute('decimal-filling');
    }

    protected get getUnformattedValue(): number {
        return Number(this.formattedInput.rawValue);
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

import Component from '../../../models/component';
import debounce from 'lodash-es/debounce';
import PasswordValidator from 'password-validator';

enum MarksGradation {
    lowercase = 10,
    digits = 15,
    uppercase = 20,
    symbols = 30,
    min = 25,
}

enum ComplexityGradiation {
    week = 25,
    medium = 50,
    strong = 75,
    percantage = 100,
}

export default class PasswordComplexityIndicator extends Component {
    protected currentValidationStatus = '';
    protected maxPasswordComplexity = 0;
    protected inputElement: HTMLInputElement;
    protected additionalMessageElement: HTMLElement;
    protected indicatorListElement: HTMLElement;
    protected schema: PasswordValidator;

    protected readyCallback(): void {}

    protected init(): void {
        this.inputElement = <HTMLInputElement>document.getElementsByClassName(this.inputClass)[0];
        this.indicatorListElement = <HTMLElement>this.getElementsByClassName(this.indicatorListClass)[0];
        this.additionalMessageElement = <HTMLElement>this.getElementsByClassName(this.additionalMessageClass)[0];
        this.schema = new PasswordValidator();

        this.initValidatorWithDefaultConfig();
        this.mapEvents();
    }

    protected mapEvents(): void {
        this.inputElement.addEventListener('keyup', debounce(() => this.onInputKeyUp(), this.debounceDelay));
    }

    protected initValidatorWithDefaultConfig(): void {
        if (this.minLength) {
            this.setValidation('min', this.minLength, true);
        }

        if (this.minLowercase) {
            this.setValidation('lowercase', this.minLowercase);
        }

        if (this.minUppercase) {
            this.setValidation('uppercase', this.minUppercase);
        }

        if (this.minSymbols) {
            this.setValidation('symbols', this.minSymbols);
        }

        if (this.minNumbers) {
            this.setValidation('digits', this.minNumbers);
        }
    }

    protected setValidation(property: string, value: number, is: boolean = false): void {
        this.schema[is ? 'is' : 'has']()[property](value);
        this.maxPasswordComplexity += MarksGradation[property];
    }

    protected onInputKeyUp(): void {
        const inputValue = this.inputElement.value;
        const failsList = this.schema.validate(inputValue, { list: true });
        let passwordValidatorMark = this.maxPasswordComplexity;

        failsList.forEach((mark: string) => {
            passwordValidatorMark -= MarksGradation[mark];
        });

        this.validatePassword(passwordValidatorMark);
    }

    protected validatePassword(passwordValidatorMark: number): void {
        const currentStatus = (passwordValidatorMark / this.maxPasswordComplexity) * ComplexityGradiation.percantage;

        if (currentStatus <= ComplexityGradiation.week) {
            this.updateValidation('week');

            return;
        }

        if (currentStatus > ComplexityGradiation.week && currentStatus <= ComplexityGradiation.medium) {
            this.updateValidation('medium');

            return;
        }

        if (currentStatus > ComplexityGradiation.medium && currentStatus <= ComplexityGradiation.strong) {
            this.updateValidation('strong');

            return;
        }

        this.updateValidation('very-strong');
    }

    protected updateValidation(complexityModifier?: string): void {
        this.updateModifier(this.indicatorListElement, this.indicatorListClass, complexityModifier);
        this.updateModifier(this.additionalMessageElement, this.additionalMessageClass, complexityModifier);

        this.currentValidationStatus = complexityModifier;
    }

    protected updateModifier(element: Element, className: string, complexityModifier?: string): void {
        const classList = element.classList;

        classList.remove(`${className}--${this.currentValidationStatus}`);

        if (complexityModifier) {
            classList.add(`${className}--${complexityModifier}`);
        }
    }

    protected get inputClass(): string {
        return this.getAttribute('input-class-name');
    }

    protected get indicatorListClass(): string {
        return `${this.name}__indicator-list`;
    }

    protected get additionalMessageClass(): string {
        return `${this.name}__additional-message`;
    }

    protected get debounceDelay(): number {
        return Number(this.getAttribute('debounce-delay'));
    }

    protected get minLength(): number {
        return Number(this.getAttribute('min-length'));
    }

    protected get minLowercase(): number {
        return Number(this.getAttribute('min-lowercase'));
    }

    protected get minUppercase(): number {
        return Number(this.getAttribute('min-uppercase'));
    }

    protected get minNumbers(): number {
        return Number(this.getAttribute('min-numbers'));
    }

    protected get minSymbols(): number {
        return Number(this.getAttribute('min-symbols'));
    }
}

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
    medium = 25,
    strong = 50,
    veryStrong = 75,
    percantage = 100,
}

export default class PasswordComplexityIndicator extends Component {
    protected availableProperties = ['min', 'lowercase', 'uppercase', 'digits', 'symbols'];
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
        this.availableProperties.forEach((proterty: string) => {
            this.setValidation(proterty);
        });
    }

    protected setValidation(proterty: string): void {
        const propertyValue = this[proterty];

        if (proterty === 'min') {
            this.setValidationWithIs(proterty, propertyValue);

            return;
        }

        this.setValidationWithHas(proterty, propertyValue);
    }

    protected setValidationWithHas(property: string, value: number): void {
        this.schema.has()[property](value);
        this.maxPasswordComplexity += MarksGradation[property];
    }

    protected setValidationWithIs(property: string, value: number): void {
        this.schema.is()[property](value);
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

        if (currentStatus > ComplexityGradiation.veryStrong) {
            this.updateValidation('very-strong');

            return;
        }

        if (currentStatus > ComplexityGradiation.strong) {
            this.updateValidation('strong');

            return;
        }

        if (currentStatus > ComplexityGradiation.medium) {
            this.updateValidation('medium');

            return;
        }

        this.updateValidation('weak');
    }

    protected updateValidation(complexityModifier: string): void {
        this.updateModifier(this.indicatorListElement, this.indicatorListClass, complexityModifier);
        this.updateModifier(this.additionalMessageElement, this.additionalMessageClass, complexityModifier);

        this.currentValidationStatus = complexityModifier;
    }

    protected updateModifier(element: Element, className: string, complexityModifier: string): void {
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

    protected get min(): number {
        return Number(this.getAttribute('min'));
    }

    protected get lowercase(): number {
        return Number(this.getAttribute('lowercase'));
    }

    protected get uppercase(): number {
        return Number(this.getAttribute('uppercase'));
    }

    protected get digits(): number {
        return Number(this.getAttribute('digits'));
    }

    protected get symbols(): number {
        return Number(this.getAttribute('symbols'));
    }
}

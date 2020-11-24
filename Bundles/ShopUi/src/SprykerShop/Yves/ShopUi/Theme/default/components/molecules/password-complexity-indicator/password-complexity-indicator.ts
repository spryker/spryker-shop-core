import Component from 'ShopUi/models/component';
import debounce from 'lodash-es/debounce';
import PasswordValidator from 'password-validator';

enum ComplexityWeight {
    lowercase = 10,
    digits = 15,
    uppercase = 20,
    symbols = 30,
    min = 25,
}

export default class PasswordComplexityIndicator extends Component {
    protected availableProperties = ['min', 'lowercase', 'uppercase', 'digits', 'symbols'];
    /* tslint:disable: no-magic-numbers */
    protected complexityGradation = new Map([['weak', 0], ['medium', 25], ['strong', 50], ['very-strong', 75]]);
    /* tslint:enable */
    protected currentComplexity = '';
    protected maxPasswordComplexity = 0;
    protected factor = 100;
    protected inputElement: HTMLInputElement;
    protected notificationElement: HTMLElement;
    protected indicatorListElement: HTMLElement;
    protected schema: PasswordValidator;

    protected readyCallback(): void {}

    protected init(): void {
        this.inputElement = <HTMLInputElement>document.getElementsByClassName(this.inputClassName)[0];
        this.indicatorListElement = <HTMLElement>this.getElementsByClassName(this.indicatorListClassName)[0];
        this.notificationElement = <HTMLElement>this.getElementsByClassName(this.additionalMessageClassName)[0];
        this.schema = new PasswordValidator();

        this.initValidator();
        this.mapEvents();
    }

    protected mapEvents(): void {
        this.mapInputElementKeyUpEvent();
    }

    protected mapInputElementKeyUpEvent(): void {
        this.inputElement.addEventListener('keyup', debounce(() => this.onInputKeyUp(), this.debounceDelay));
    }

    protected initValidator(): void {
        this.availableProperties.forEach((property: string) => {
            this.setValidation(property);
            this.increaseMaxPasswordComplexity(property);
        });
    }

    protected increaseMaxPasswordComplexity(property: string): void {
        this.maxPasswordComplexity += ComplexityWeight[property];
    }

    protected setValidation(property: string): void {
        const propertyValue = this[property];

        if (property === 'min') {
            this.setIsValidationType(property, propertyValue);

            return;
        }

        this.setHasValidationType(property, propertyValue);
    }

    protected setHasValidationType(property: string, value: number): void {
        this.schema.has()[property](value);
    }

    protected setIsValidationType(property: string, value: number): void {
        this.schema.is()[property](value);
    }

    protected onInputKeyUp(): void {
        const inputValue = this.inputElement.value;
        const failsList = this.schema.validate(inputValue, { list: true });
        let passwordValidatorMark = this.maxPasswordComplexity;

        failsList.forEach((property: string) => {
            passwordValidatorMark -= ComplexityWeight[property];
        });

        this.validatePassword(passwordValidatorMark);
    }

    protected validatePassword(passwordValidatorMark: number): void {
        const passwordComplexity = (passwordValidatorMark / this.maxPasswordComplexity) * this.factor;
        const veryStrong = this.complexityGradation.get('very-strong');
        const strong = this.complexityGradation.get('strong');
        const medium = this.complexityGradation.get('medium');
        const weak = this.complexityGradation.get('weak');

        if (passwordComplexity > veryStrong) {
            this.updateValidation(this.getKey(veryStrong));

            return;
        }

        if (passwordComplexity > strong) {
            this.updateValidation(this.getKey(strong));

            return;
        }

        if (passwordComplexity > medium) {
            this.updateValidation(this.getKey(medium));

            return;
        }

        this.updateValidation(this.getKey(weak));
    }

    protected updateValidation(complexityModifier: string): void {
        this.updateModifier(this.indicatorListElement, this.indicatorListClassName, complexityModifier);
        this.updateModifier(this.notificationElement, this.additionalMessageClassName, complexityModifier);

        this.currentComplexity = complexityModifier;
    }

    protected updateModifier(element: Element, className: string, complexityModifier: string): void {
        const classList = element.classList;

        classList.remove(`${className}--${this.currentComplexity}`);
        classList.add(`${className}--${complexityModifier}`);
    }

    protected getKey(currentValue: number): string {
        return [...this.complexityGradation].find(([key, value]) => currentValue === value)[0];
    }

    protected get inputClassName(): string {
        return this.getAttribute('input-class-name');
    }

    protected get indicatorListClassName(): string {
        return `${this.name}__indicator-list`;
    }

    protected get additionalMessageClassName(): string {
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

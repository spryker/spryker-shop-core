import Component from 'ShopUi/models/component';

export default class RatingSelector extends Component {
    input: HTMLInputElement
    steps: HTMLElement[]

    protected readyCallback(): void {
        this.input = <HTMLInputElement>this.querySelector(`.${this.jsName}__input`);
        this.steps = <HTMLElement[]>Array.from(this.getElementsByClassName(`${this.jsName}__step`));

        if (!this.readOnly) {
            this.checkInput(this.value);
            this.mapEvents();
        }
    }

    protected mapEvents(): void {
        this.steps.forEach((step: HTMLElement) => step.addEventListener('click', (event: Event) => this.onStepClick(event)));
    }

    protected onStepClick(event: Event): void {
        event.preventDefault();

        const step = <HTMLElement>event.currentTarget;
        const newValue = parseFloat(step.getAttribute('data-step-value'));

        this.checkInput(newValue);
        this.updateRating(newValue);
    }

    /**
     * Toggles the disable attribute on the input element
     * @param value number for checking set the attribue or remove it from the input element
     */
    checkInput(value: number): void {
        if (!this.disableIfEmptyValue) {
            return;
        }

        if (!value) {
            this.input.setAttribute('disabled', 'disabled');
            return;
        }

        this.input.removeAttribute('disabled');
    }


    /**
     * Performs setting the value attibute and toogle special class name
     * @param value number for setting the atribute
     */
    updateRating(value: number): void {
        this.input.setAttribute('value', `${value}`);

        this.steps.forEach((step: HTMLElement) => {
            const stepValue = parseFloat(step.getAttribute('data-step-value'));

            if (value >= stepValue) {
                step.classList.add(`${this.name}__step--active`);
                return;
            }

            step.classList.remove(`${this.name}__step--active`);
        });
    }

    /**
     * Gets an input value
     */
    get value(): number {
        return parseFloat(this.input.value);
    }

    /**
     * Gets if an eleme nt is readonly
     */
    get readOnly(): boolean {
        return this.hasAttribute('readonly');
    }

    /**
     * Gets if an element has an epmty value
     */
    get disableIfEmptyValue(): boolean {
        return this.hasAttribute('disable-if-empty-value');
    }
}

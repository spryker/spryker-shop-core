import Component from 'ShopUi/models/component';

export default class RatingSelector extends Component {
    /**
     * The input element.
     */
    input: HTMLInputElement;

    /**
     * Collection of the elements which toggle the steps of the product review.
     */
    steps: HTMLElement[];

    protected readyCallback(): void {
        this.input = <HTMLInputElement>this.getElementsByClassName(`${this.jsName}__input`)[0];
        this.steps = <HTMLElement[]>Array.from(this.getElementsByClassName(`${this.jsName}__step`));

        if (!this.readOnly) {
            this.checkInput(this.value);
            this.mapEvents();
        }
    }

    protected mapEvents(): void {
        this.steps.forEach((step: HTMLElement) => {
            step.addEventListener('click', (event: Event) => this.onStepClick(event));
        });
    }

    protected onStepClick(event: Event): void {
        const step = <HTMLElement>event.currentTarget;
        const newValue = parseFloat(step.getAttribute('data-step-value'));

        this.checkInput(newValue);
        this.updateRating(newValue);
    }

    /**
     * Toggles the disabled attribute of the input element.
     * @param value A number for checking if the attribute is to be set or removed from the input element.
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
     * Sets the value attribute and toggles the special class name.
     * @param value A number for setting the attribute.
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
     * Gets an input value.
     */
    get value(): number {
        return parseFloat(this.input.value);
    }

    /**
     * Gets if the element is read-only.
     */
    get readOnly(): boolean {
        return this.hasAttribute('readonly');
    }

    /**
     * Gets if the element has an empty value.
     */
    get disableIfEmptyValue(): boolean {
        return this.hasAttribute('disable-if-empty-value');
    }
}

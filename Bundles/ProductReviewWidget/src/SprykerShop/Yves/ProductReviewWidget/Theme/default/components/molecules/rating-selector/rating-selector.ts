import Component from 'ShopUi/models/component';
import ProductItem, { EVENT_UPDATE_RATING } from 'ShopUi/components/molecules/product-item/product-item';

export default class RatingSelector extends Component {
    /**
     * The input element.
     */
    input: HTMLInputElement;

    /**
     * Collection of the elements which toggle the steps of the product review.
     */
    steps: HTMLElement[];
    protected productItem: ProductItem;

    protected readyCallback(): void {
        this.input = <HTMLInputElement>this.getElementsByClassName(`${this.jsName}__input`)[0];
        this.steps = <HTMLElement[]>Array.from(this.getElementsByClassName(`${this.jsName}__step`));
        if (this.productItemClassName) {
            this.productItem = <ProductItem>this.closest(`.${this.productItemClassName}`);
        }

        if (!this.readOnly) {
            this.checkInput(this.value);
            this.mapEvents();
        }

        this.mapCustomEvents();
    }

    protected mapEvents(): void {
        this.steps.forEach((step: HTMLElement) => {
            step.addEventListener('click', (event: Event) => this.onStepClick(event));
        });
    }

    protected mapCustomEvents(): void {
        if (this.productItem) {
            this.productItem.addEventListener(EVENT_UPDATE_RATING, (event: Event) => {
                this.updateRating((<CustomEvent>event).detail.rating);
            });
        }
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

    protected get productItemClassName(): string {
        return this.getAttribute('product-item-class-name');
    }
}

import Component from 'ShopUi/models/component';

export default class RatingSelector extends Component {
    input: HTMLElement
    steps: HTMLElement[]

    readyCallback() {
        this.input = <HTMLElement>this.querySelector(`.${this.componentSelector}__input`);
        this.steps = <HTMLElement[]>Array.from(this.getElementsByClassName(`${this.componentSelector}__step`));

        if (!this.readOnly) {
            this.mapEvents();
        }
    }

    mapEvents() {
        this.steps.forEach((step: HTMLElement) => step.addEventListener('click', (event: Event) => this.onStepClick(event)));
    }

    onStepClick(event: Event) {
        event.preventDefault();
        const step = <HTMLElement>event.currentTarget;
        const value = parseFloat(step.getAttribute('data-step-value'));
        this.updateRating(value);
    }

    updateRating(value: number): void {
        this.input.setAttribute('value', `${value}`);

        this.steps.forEach((step: HTMLElement) => {
            const stepValue = parseFloat(step.getAttribute('data-step-value'));

            if (value >= stepValue) {
                step.classList.add(`${this.componentName}__step--active`);
                return;
            }

            step.classList.remove(`${this.componentName}__step--active`);
        });
    }

    get readOnly(): boolean {
        return this.hasAttribute('readonly');
    }
}

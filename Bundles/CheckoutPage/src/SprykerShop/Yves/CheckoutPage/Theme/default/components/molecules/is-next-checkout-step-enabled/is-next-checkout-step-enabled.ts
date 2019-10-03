import Component from 'ShopUi/models/component';
import ValidateNextCheckoutStep, { EVENT_INIT } from '../validate-next-checkout-step/validate-next-checkout-step';

export default class IsNextCheckoutStepEnabled extends Component {
    protected trigger: HTMLSelectElement;
    protected target: ValidateNextCheckoutStep;

    protected readyCallback(): void {
        if (this.triggerSelector) {
            this.trigger = <HTMLSelectElement>document.querySelector(this.triggerSelector);
        }
        this.target = <ValidateNextCheckoutStep>document.querySelector(this.targetSelector);

        if (this.trigger) {
            this.mapEvents();
        }
    }

    protected mapEvents(): void {
        this.trigger.addEventListener('change', () => this.onTriggerChange());
        this.target.addEventListener(EVENT_INIT, () => this.initTriggerState());
    }

    protected initTriggerState(): void {
        this.onTriggerChange();
    }

    protected onTriggerChange(): void {
        const currentValue = this.trigger.options[this.trigger.selectedIndex].value;

        if (currentValue === this.toggleOptionValue && this.target) {
            this.target.initTriggerState();

            return;
        }

        this.target.disableNextStepButton(false);
    }

    /**
     * Gets a querySelector name of the trigger element.
     */
    get triggerSelector(): string {
        return this.getAttribute('trigger-selector');
    }

    /**
     * Gets a querySelector name of the target element.
     */
    get targetSelector(): string {
        return this.getAttribute('target-selector');
    }

    /**
     * Gets an option value for comparison with chosen value from dropdown to enable 'validate-next-checkout-step'
     * component.
     */
    get toggleOptionValue(): string {
        return this.getAttribute('toggle-option-value');
    }
}

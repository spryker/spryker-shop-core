import Component from 'ShopUi/models/component';
import ValidateNextCheckoutStep from '../validate-next-checkout-step/validate-next-checkout-step';

export default class IsNextCheckoutStepEnabled extends Component {
    protected trigger: HTMLSelectElement;
    protected target: ValidateNextCheckoutStep;

    protected readyCallback(): void {
        this.trigger = <HTMLSelectElement>document.querySelector(this.triggerSelector);
        this.target = <ValidateNextCheckoutStep>document.querySelector(this.targetSelector);

        this.initTriggerState();
        this.mapEvents();
    }

    protected mapEvents(): void {
        this.trigger.addEventListener('change', () => this.onTriggerChange());
    }

    protected initTriggerState(): void {
        this.onTriggerChange();
    }

    protected onTriggerChange(): void {
        const currentValue = this.trigger.options[this.trigger.selectedIndex].value;

        if (currentValue === this.toggleOptionValue && this.target) {
            this.target.enableTrigger();
        }
    }

    get triggerSelector(): string {
        return this.getAttribute('trigger-selector');
    }

    get targetSelector(): string {
        return this.getAttribute('target-selector');
    }

    get toggleOptionValue(): string {
        return this.getAttribute('toggle-option-value');
    }
}

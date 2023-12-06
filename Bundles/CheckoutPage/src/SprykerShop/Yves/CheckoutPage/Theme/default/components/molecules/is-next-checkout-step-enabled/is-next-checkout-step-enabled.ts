import Component from 'ShopUi/models/component';
import ValidateNextCheckoutStep, { EVENT_INIT } from '../validate-next-checkout-step/validate-next-checkout-step';

export default class IsNextCheckoutStepEnabled extends Component {
    protected trigger: HTMLSelectElement;
    protected extraTriggers: HTMLInputElement[];
    protected target: ValidateNextCheckoutStep;
    protected extraTarget: ValidateNextCheckoutStep;

    protected readyCallback(): void {}

    protected init(): void {
        if (this.triggerSelector) {
            this.trigger = <HTMLSelectElement>document.querySelector(this.triggerSelector);
        }

        if (this.extraTriggersClassName) {
            this.extraTriggers = <HTMLInputElement[]>(
                Array.from(document.getElementsByClassName(this.extraTriggersClassName))
            );
        }

        this.target = <ValidateNextCheckoutStep>document.querySelector(this.targetSelector);

        if (this.extraTargetSelector) {
            this.extraTarget = <ValidateNextCheckoutStep>document.querySelector(this.extraTargetSelector);
        }

        if (this.trigger) {
            this.mapEvents();
        }
    }

    protected mapEvents(): void {
        this.mapTriggerChangeEvent();
        this.mapExtraTriggersToggleEvent();
        this.mapTargetInitEvent();
    }

    protected mapTriggerChangeEvent(): void {
        this.trigger.addEventListener('change', () => this.onTriggerChange());
    }

    protected mapExtraTriggersToggleEvent(): void {
        if (!this.extraTriggers) {
            return;
        }

        this.extraTriggers.forEach((extraTrigger: HTMLInputElement) => {
            extraTrigger.addEventListener('toggle', () => this.onTriggerChange());
        });
    }

    protected mapTargetInitEvent(): void {
        const target = this.extraTarget ? this.extraTarget : this.target;

        target.addEventListener(EVENT_INIT, () => this.initTriggerState());
    }

    protected initTriggerState(): void {
        this.onTriggerChange();
    }

    protected onTriggerChange(): void {
        const currentValue = this.trigger.options[this.trigger.selectedIndex].value;

        if (currentValue === this.toggleOptionValue && this.target) {
            this.resetTargetEvents(this.extraTarget);
            this.target.initTriggerState();

            return;
        }

        if (currentValue !== this.toggleOptionValue && this.extraTarget) {
            this.resetTargetEvents(this.target);
            this.extraTarget.initTriggerState();

            return;
        }

        this.target.disableNextStepButton(false);
    }

    protected resetTargetEvents(target: ValidateNextCheckoutStep): void {
        if (!target) {
            return;
        }

        target.resetEvents();
    }

    /**
     * Gets a querySelector name of the trigger element.
     */
    get triggerSelector(): string {
        return this.getAttribute('trigger-selector');
    }

    protected get extraTriggersClassName(): string {
        return this.getAttribute('extra-triggers-class-name');
    }

    /**
     * Gets a querySelector name of the target element.
     */
    get targetSelector(): string {
        return this.getAttribute('target-selector');
    }

    protected get extraTargetSelector(): string {
        return this.getAttribute('extra-target-selector');
    }

    /**
     * Gets an option value for comparison with chosen value from dropdown to enable 'validate-next-checkout-step'
     * component.
     */
    get toggleOptionValue(): string {
        return this.getAttribute('toggle-option-value');
    }
}

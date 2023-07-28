import Component from 'ShopUi/models/component';

export const EVENT_INIT = 'afterInit';

/**
 * @event afterInit An event emitted when the component has been initialized.
 */
export default class ValidateNextCheckoutStep extends Component {
    protected containers: HTMLElement[];
    protected triggers: HTMLFormElement[];
    protected extraTriggers: HTMLFormElement[];
    protected target: HTMLButtonElement;
    protected dropdownTriggers: HTMLSelectElement[];
    protected parentTarget: HTMLElement;
    protected readonly requiredFormFieldSelectors: string = 'select[required], input[required]';

    protected readyCallback(): void {}

    protected init(): void {
        this.containers = <HTMLElement[]>Array.from(document.querySelectorAll(this.containerSelector));
        this.target = <HTMLButtonElement>document.querySelector(this.targetSelector);

        if (this.parentTargetClassName) {
            this.parentTarget = <HTMLElement>document.getElementsByClassName(this.parentTargetClassName)[0];
        }

        if (this.extraTriggersClassName) {
            this.extraTriggers = <HTMLFormElement[]>(
                Array.from(document.getElementsByClassName(this.extraTriggersClassName))
            );
        }

        if (this.isTriggerEnabled) {
            this.initTriggerState();
        }

        this.dispatchCustomEvent(EVENT_INIT);
    }

    protected mapEvents(): void {
        this.mapTriggerEvents();

        this.dropdownTriggers.forEach((element: HTMLSelectElement) => {
            element.addEventListener('change', () => this.onDropdownTriggerChange());
        });

        if (this.parentTarget) {
            this.parentTarget.addEventListener('toggleForm', () => this.onDropdownTriggerChange());
        }

        if (this.extraTriggers) {
            this.extraTriggers.forEach((extraTrigger: HTMLFormElement) => {
                extraTrigger.addEventListener('change', () => this.onExtraTriggerChange());
            });
        }
    }

    protected mapTriggerEvents(): void {
        if (this.triggers) {
            this.triggers.forEach((element: HTMLFormElement) => {
                element.addEventListener('input', () => this.onTriggerInput());
            });
        }
    }

    /**
     * Init the methods, which fill the collection of form fields and toggle disabling of button.
     */
    initTriggerState(): void {
        this.fillDropdownTriggersCollection();
        this.fillFormFieldsCollection();
        this.toggleDisablingNextStepButton();
        this.mapEvents();
    }

    protected fillDropdownTriggersCollection(): void {
        this.dropdownTriggers = <HTMLSelectElement[]>(
            Array.from(document.querySelectorAll(this.dropdownTriggerSelector))
        );
    }

    protected fillFormFieldsCollection(): void {
        this.triggers = [];

        if (!this.containers) {
            return;
        }

        this.triggers = <HTMLFormElement[]>this.containers.reduce((collection: HTMLElement[], element: HTMLElement) => {
            if (!element.classList.contains(this.classToToggle)) {
                collection.push(
                    ...(<HTMLFormElement[]>Array.from(element.querySelectorAll(this.requiredFormFieldSelectors))),
                );
            }

            return collection;
        }, []);
    }

    protected onTriggerInput(): void {
        this.fillFormFieldsCollection();
        this.toggleDisablingNextStepButton();
    }

    protected onDropdownTriggerChange(): void {
        this.onTriggerInput();
        this.mapTriggerEvents();
    }

    protected onExtraTriggerChange(): void {
        this.initTriggerState();
    }

    protected toggleDisablingNextStepButton(): void {
        if (!this.target) {
            return;
        }

        if (this.isFormFieldsEmpty || this.isDropdownTriggerPreSelected) {
            this.disableNextStepButton(true);

            return;
        }

        this.disableNextStepButton(false);
    }

    /**
     * Removes/Sets the disabled attribute for target element.
     */
    disableNextStepButton(isDisabled: boolean): void {
        if (this.target) {
            this.target.disabled = isDisabled;
        }
    }

    protected get isDropdownTriggerPreSelected(): boolean {
        if (!this.dropdownTriggers) {
            return false;
        }

        return this.dropdownTriggers.some((element: HTMLSelectElement) => !element.value);
    }

    /**
     * Checks if the form fields are empty.
     */
    get isFormFieldsEmpty(): boolean {
        return this.triggers.some((element: HTMLFormElement) => !element.value);
    }

    /**
     * Gets a querySelector name of the target element.
     */
    get targetSelector(): string {
        return this.getAttribute('target-selector');
    }

    /**
     * Gets a querySelector name of the form element.
     */
    get containerSelector(): string {
        return this.getAttribute('container-selector');
    }

    /**
     * Gets a querySelector name of the dropdown trigger element.
     */
    get dropdownTriggerSelector(): string {
        return this.getAttribute('dropdown-trigger-selector');
    }

    /**
     * Checks if the trigger element is enabled.
     */
    get isTriggerEnabled(): boolean {
        return this.hasAttribute('is-enable');
    }

    /**
     * Gets a class name for the toggle action.
     */
    get classToToggle(): string {
        return this.getAttribute('class-to-toggle');
    }

    protected get parentTargetClassName(): string {
        return this.getAttribute('parent-target-class-name');
    }

    protected get extraTriggersClassName(): string {
        return this.getAttribute('extra-triggers-class-name');
    }
}

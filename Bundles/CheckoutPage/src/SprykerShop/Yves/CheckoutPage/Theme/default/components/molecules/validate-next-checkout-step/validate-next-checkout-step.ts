import Component from 'ShopUi/models/component';

export default class ValidateNextCheckoutStep extends Component {
    protected forms: HTMLElement[];
    protected triggers: HTMLFormElement[];
    protected target: HTMLButtonElement;
    protected dropdownTriggers: HTMLSelectElement[];
    protected readonly requiredFormFieldSelectors: string = 'select[required], input[required]';

    protected readyCallback(): void {}

    /**
     * Default callback, which is called when all web components are ready for use.
     */
    mountCallback(): void {
        this.forms = <HTMLElement[]>Array.from(document.querySelectorAll(this.formSelector));
        this.target = <HTMLButtonElement>document.querySelector(this.targetSelector);
        if (this.dropdownTriggerSelector) {
            this.dropdownTriggers = <HTMLSelectElement[]>Array.from(document.querySelectorAll(
                this.dropdownTriggerSelector
            ));
        }
        if (this.isTriggerEnabled) {
            this.initTriggerState();
        }
    }

    protected mapEvents(): void {
        this.mapTriggerEvents();

        if (this.dropdownTriggers) {
            this.dropdownTriggers.forEach((element: HTMLSelectElement) => {
                element.addEventListener('change', () => this.onDropdownTriggerChange());
            });
        }
    }

    protected mapTriggerEvents(): void {
        if (this.triggers) {
            this.triggers.forEach((element: HTMLFormElement) => {
                element.addEventListener('change', () => this.onTriggerChange());
            });
        }
    }

    /**
     * Calls the functions and map events.
     */
    initTriggerState(): void {
        this.fillFormFieldsCollection();
        this.toggleDisablingNextStepButton();
        this.mapEvents();
    }

    protected fillFormFieldsCollection(): void {
        this.triggers = [];

        if (!this.forms) {
            return;
        }

        this.triggers = <HTMLFormElement[]>this.forms.reduce((collection: HTMLElement[], element: HTMLElement) => {
            if (!element.classList.contains(this.classToToggle)) {
                collection.push(...<HTMLFormElement[]>Array.from(element.querySelectorAll(
                    this.requiredFormFieldSelectors
                )));
            }

            return collection;
        }, []);
    }

    protected onTriggerChange(): void {
        this.fillFormFieldsCollection();
        this.toggleDisablingNextStepButton();
    }

    protected onDropdownTriggerChange(): void {
        this.onTriggerChange();
        this.mapTriggerEvents();
    }

    protected toggleDisablingNextStepButton(): void {
        if (!this.target) {
            return;
        }

        if (this.isFormFieldsEmpty) {
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
    get formSelector(): string {
        return this.getAttribute('form-selector');
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
}

import Component from 'ShopUi/models/component';

export default class ValidateNextCheckoutStep extends Component {
    protected forms: HTMLElement[];
    protected triggers: HTMLFormElement[];
    protected target: HTMLButtonElement;
    protected dropdownTriggers: HTMLSelectElement[];
    protected readonly requiredFormFieldSelectors: string = `select[required], input[required]`;

    protected readyCallback(): void {
        this.forms = <HTMLElement[]>Array.from(document.querySelectorAll(this.formSelector));
        this.target = <HTMLButtonElement>document.querySelector(this.targetSelector);
        if (this.dropdownTriggerSelector) {
            this.dropdownTriggers = <HTMLSelectElement[]>Array.from(document.querySelectorAll(
                this.dropdownTriggerSelector
            ));
        }
        if (this.isTriggerEnabled) {
            this.enableTrigger();
        }
    }

    protected mapEvents(): void {
        if (this.triggers) {
            this.triggers.forEach((element: HTMLFormElement) => {
                element.addEventListener('change', () => this.onTriggerChange());
            });
        }

        if (this.dropdownTriggers) {
            this.dropdownTriggers.forEach((element: HTMLSelectElement) => {
                element.addEventListener('change', () => this.onTriggerChange());
            });
        }
    }

    /**
     * Calls the functions and map events.
     */
    enableTrigger(): void {
        this.fillFormFieldsCollection();
        this.toggleDisablingNextStepButton();
        this.mapEvents();
    }

    protected fillFormFieldsCollection(): void {
        this.triggers = [];

        if (this.forms) {
            this.forms.forEach((element: HTMLElement) => {
                if (!element.classList.contains(this.classToToggle)) {
                    this.triggers.push(...<HTMLFormElement[]>Array.from(element.querySelectorAll(
                        this.requiredFormFieldSelectors
                    )));
                }
            });
        }
    }

    protected onTriggerChange(): void {
        this.fillFormFieldsCollection();
        this.toggleDisablingNextStepButton();
    }

    protected toggleDisablingNextStepButton(): void {
        if (this.target) {
            if (this.isFormFieldsEmpty) {
                this.disableNextStepButton();

                return;
            }

            this.enableNextStepButton();
        }
    }

    /**
     * Sets the disabled attribute for target element.
     */
    disableNextStepButton(): void {
        if (this.target) {
            this.target.disabled = true;
        }
    }

    /**
     * Removes the disabled attribute for target element.
     */
    enableNextStepButton(): void {
        if (this.target) {
            this.target.disabled = false;
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

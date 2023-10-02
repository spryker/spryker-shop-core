import Component from 'ShopUi/models/component';

/**
 * @event afterInit An event emitted when the component has been initialized.
 */
export const EVENT_INIT = 'afterInit';

type FormFieldElement = HTMLInputElement | HTMLSelectElement | HTMLTextAreaElement;

export default class ValidateNextCheckoutStep extends Component {
    protected containers: HTMLElement[];
    protected triggers: FormFieldElement[];
    protected extraTriggers: HTMLInputElement[];
    protected target: HTMLButtonElement;
    protected dropdownTriggers: HTMLSelectElement[];
    protected parentTarget: HTMLElement;
    protected readonly requiredFormFieldSelectors: string = 'select[required], input[required]';
    protected dropdownTriggersChangeHandler: () => void;
    protected parentTargetToggleFormHandler: () => void;
    protected extraTriggerChangeHandler: () => void;
    protected triggerInputHandler: () => void;

    protected readyCallback(): void {}

    protected init(): void {
        this.target = <HTMLButtonElement>document.querySelector(this.targetSelector);

        if (this.parentTargetClassName) {
            this.parentTarget = <HTMLElement>document.getElementsByClassName(this.parentTargetClassName)[0];
        }

        if (this.isTriggerEnabled) {
            this.initTriggerState();
        }

        this.dispatchCustomEvent(EVENT_INIT);
    }

    protected mapEvents(): void {
        this.mapTriggerEvents();

        this.dropdownTriggersChangeHandler = () => this.onDropdownTriggerChange();
        this.dropdownTriggers.forEach((element: HTMLSelectElement) => {
            element.addEventListener('change', this.dropdownTriggersChangeHandler);
        });

        if (this.parentTarget) {
            this.parentTargetToggleFormHandler = () => this.onDropdownTriggerChange();
            this.parentTarget.addEventListener('toggleForm', this.parentTargetToggleFormHandler);
        }

        if (this.extraTriggers) {
            this.extraTriggerChangeHandler = () => this.onExtraTriggerChange();
            this.extraTriggers.forEach((extraTrigger: HTMLInputElement) => {
                extraTrigger.addEventListener('change', this.extraTriggerChangeHandler);
            });
        }
    }

    /**
     * Resets events that were subscribed in the `mapEvents` method.
     */
    resetEvents(): void {
        if (this.triggers) {
            this.triggers.forEach((element: FormFieldElement) => {
                element.removeEventListener('input', this.triggerInputHandler);
            });
        }

        if (this.dropdownTriggers) {
            this.dropdownTriggers.forEach((element: HTMLSelectElement) => {
                element.removeEventListener('change', this.dropdownTriggersChangeHandler);
            });
        }

        if (this.parentTarget) {
            this.parentTarget.removeEventListener('toggleForm', this.parentTargetToggleFormHandler);
        }

        if (this.extraTriggers) {
            this.extraTriggers.forEach((extraTrigger: HTMLInputElement) => {
                extraTrigger.removeEventListener('change', this.extraTriggerChangeHandler);
            });
        }
    }

    protected mapTriggerEvents(): void {
        if (this.triggers) {
            this.triggerInputHandler = () => this.onTriggerInput();
            this.triggers.forEach((element: FormFieldElement) => {
                element.addEventListener('input', this.triggerInputHandler);
            });
        }
    }

    /**
     * Init the methods, which fill the collection of form fields and toggle disabling of button.
     */
    async initTriggerState(): Promise<void> {
        this.containers = <HTMLElement[]>Array.from(document.querySelectorAll(this.containerSelector));

        this.fillDropdownTriggersCollection();
        this.fillFormFieldsCollection();
        await this.fillExtraTriggersCollection();
        this.toggleDisablingNextStepButton();
        this.resetEvents();
        this.mapEvents();
    }

    protected fillDropdownTriggersCollection(): void {
        this.dropdownTriggers = <HTMLSelectElement[]>(
            Array.from(document.querySelectorAll(this.dropdownTriggerSelector))
        );
    }

    protected async fillFormFieldsCollection(): Promise<void> {
        this.triggers = [];

        if (!this.containers) {
            return;
        }

        this.triggers = <FormFieldElement[]>this.containers.reduce(
            (collection: HTMLElement[], element: HTMLElement) => {
                const extraContainer = this.extraContainerSelector
                    ? element.closest(this.extraContainerSelector)
                    : null;

                if (
                    !element.classList.contains(this.classToToggle) &&
                    !extraContainer?.classList.contains(this.classToToggle)
                ) {
                    collection.push(
                        ...(<FormFieldElement[]>Array.from(element.querySelectorAll(this.requiredFormFieldSelectors))),
                    );
                }

                return collection;
            },
            [],
        );
    }

    protected fillExtraTriggersCollection(): void {
        if (!this.extraTriggersClassName) {
            return;
        }

        this.extraTriggers = <HTMLInputElement[]>(
            Array.from(document.getElementsByClassName(this.extraTriggersClassName))
        );
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

        const isFormInvalid =
            this.isFormFieldsEmpty || this.isDropdownTriggerPreSelected || this.isExtraTriggersUnchecked;
        this.disableNextStepButton(isFormInvalid);
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

    protected get isExtraTriggersUnchecked(): boolean {
        if (!this.extraTriggers) {
            return false;
        }

        const groupExtraTriggers = {};
        const checkedGroup = [];

        this.extraTriggers.forEach((extraTrigger: HTMLInputElement) => {
            const triggerName = extraTrigger.name;

            if (groupExtraTriggers[triggerName] !== extraTrigger.checked) {
                groupExtraTriggers[triggerName] = extraTrigger.checked;
            }

            if (groupExtraTriggers[triggerName]) {
                checkedGroup.push(triggerName);
                groupExtraTriggers[triggerName] = false;
            }
        });

        return Object.keys(groupExtraTriggers).length !== checkedGroup.length;
    }

    /**
     * Checks if the form fields are empty.
     */
    get isFormFieldsEmpty(): boolean {
        return this.triggers.some((element: FormFieldElement) => !element.value);
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

    protected get extraContainerSelector(): string {
        return this.getAttribute('extra-container-selector');
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

import Component from 'ShopUi/models/component';

export default class SameBillingToggler extends Component {
    protected toggler: HTMLSelectElement;
    protected checkboxContainer: HTMLElement;
    protected isCheckboxVisible: boolean = true;
    readonly hideClass: string = 'is-hidden';

    protected readyCallback(): void {
        this.toggler = <HTMLSelectElement>document.querySelector(this.triggerSelector);
        this.checkboxContainer = <HTMLInputElement>document.querySelector(this.targetContainerSelector);

        this.mapEvents();
    }

    protected mapEvents(): void {
        this.toggler.addEventListener('change', (event: Event) => this.onTogglerChange(event))
    }

    protected onTogglerChange(event: Event): void {
        const togglerElement = <HTMLSelectElement>event.srcElement;
        const selectedOptionValue = <string>togglerElement.options[togglerElement.selectedIndex].value;

        this.toggle(selectedOptionValue);
    }

    protected toggle(selectedOption: string): void {
        if (!this.shouldToggle(selectedOption)) {
            return;
        }

        this.toggleCheckbox();
        this.checkboxContainer.classList.toggle(this.hideClass);
    }

    protected shouldToggle(selectedOptionValue: string): boolean {
        return (this.matchTriggerOptionValue(selectedOptionValue) && this.isCheckboxVisible)
            || (!this.matchTriggerOptionValue(selectedOptionValue) && !this.isCheckboxVisible)
    }

    protected matchTriggerOptionValue(selectedOption): boolean {
        return selectedOption == this.toggleOptionValue;
    }

    protected toggleCheckbox(): void {
        this.isCheckboxVisible = !this.isCheckboxVisible;
        this.checkboxContainer.classList.toggle(this.hideClass, this.isCheckboxVisible);
        const billingCheckbox = <HTMLInputElement|null>this.checkboxContainer.querySelector('input[type="checkbox"]');
        billingCheckbox.click();
    }

    get triggerSelector(): string {
        return this.getAttribute('trigger-selector');
    }

    get targetContainerSelector(): string {
        return this.getAttribute('target-container-selector');
    }

    get toggleOptionValue(): string {
        return this.getAttribute('toggle-option-value');
    }
}
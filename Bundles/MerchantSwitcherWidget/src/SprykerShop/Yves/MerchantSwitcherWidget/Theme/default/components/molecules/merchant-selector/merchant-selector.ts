import Component from 'ShopUi/models/component';

export default class MerchantSelector extends Component {
    protected form: HTMLFormElement;
    protected select: HTMLSelectElement;
    protected selectedIndex: number;

    protected readyCallback(): void {}

    protected init(): void {
        this.form = <HTMLFormElement>this.getElementsByClassName(`${this.jsName}__form`)[0];
        this.select = <HTMLSelectElement>this.form.getElementsByClassName(`${this.jsName}__select`)[0];
        this.selectedIndex = this.select.selectedIndex;
        this.mapEvents();
    }

    protected mapEvents(): void {
        this.select.addEventListener('change', () => this.onChangeHandler());
    }

    protected onChangeHandler(): void {
        const previousOption: HTMLOptionElement = this.select.options[this.selectedIndex];
        const message: string = this.getCurrentMessage();
        const isFormSubmitConfirmed: boolean = confirm(message);

        if (isConfirmSubmittingForm) {
            this.form.submit();

            return;
        }

        previousOption.selected = true;
    }

    protected getCurrentMessage(): string {
        const selectedIndex: number = this.select.selectedIndex;
        const selectedOptionText: string = this.select.options[selectedIndex].text;

        return this.messageTemplate.replace(this.selectedMerchantNameTemplate, selectedOptionText);
    }

    protected get messageTemplate(): string {
        return this.getAttribute('message-template');
    }

    protected get selectedMerchantNameTemplate(): string {
        return this.getAttribute('selected-merchant-name-template');
    }
}

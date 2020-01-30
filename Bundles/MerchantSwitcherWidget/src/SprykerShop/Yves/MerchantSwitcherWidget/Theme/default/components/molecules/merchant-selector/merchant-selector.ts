import Component from 'ShopUi/models/component';

export default class MerchantSelector extends Component {
    protected form: HTMLFormElement;
    protected select: HTMLSelectElement;
    protected message: string;
    protected initiallySelectedIndex: number;

    protected readyCallback(): void {}

    protected init(): void {
        this.form = <HTMLFormElement>this.getElementsByClassName(`${this.jsName}__form`)[0];
        this.select = <HTMLSelectElement>this.form.getElementsByClassName(`${this.jsName}__select`)[0];
        this.initiallySelectedIndex = this.select.selectedIndex;
        this.mapEvents();
    }

    protected mapEvents(): void {
        this.select.addEventListener('change', () => this.onChangeHandler());
    }

    protected onChangeHandler(): void {
        this.updateMessageText();
        this.setMerchant();
    }

    /**
     * Updates a text of the confirmation question.
     */
    updateMessageText(): void  {
        const currentMerchantOptionText: string = this.select.options[this.initiallySelectedIndex].text;
        const newMerchantOptionText: string = this.select.options[this.select.selectedIndex].text;

        this.message = this.messageTemplate;
        this.message = this.message.replace(this.currentMerchantNameTemplate, currentMerchantOptionText);
        this.message = this.message.replace(this.newMerchantNameTemplate, newMerchantOptionText);
    }

    /**
     * Sets merchant after according answer of confirmation question.
     */
    setMerchant(): void {
        const isFormSubmitConfirmed: boolean = confirm(this.message);

        if (isFormSubmitConfirmed) {
            this.form.submit();

            return;
        }

        this.selectInitialOption();
    }

    protected selectInitialOption(): void {
        const initialMerchantOption: HTMLOptionElement = this.select.options[this.initiallySelectedIndex];
        initialMerchantOption.selected = true;
    }

    protected get messageTemplate(): string {
        return this.getAttribute('message-template');
    }

    protected get currentMerchantNameTemplate(): string {
        return this.getAttribute('current-merchant-name-template');
    }

    protected get newMerchantNameTemplate(): string {
        return this.getAttribute('new-merchant-name-template');
    }
}

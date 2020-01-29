import Component from 'ShopUi/models/component';

export default class MerchantSelector extends Component {
    protected form: HTMLFormElement;
    protected select: HTMLSelectElement;
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
        const message: string = this.getCurrentMessage();
        const isFormSubmitConfirmed: boolean = confirm(message);

        if (isFormSubmitConfirmed) {
            this.form.submit();

            return;
        }

        this.setInitialOption();
    }

    protected getCurrentMessage(): string {
        const newMerchantIndex: number = this.select.selectedIndex;
        const newMerchantOptionText: string = this.select.options[newMerchantIndex].text;

        return this.messageTemplate.replace(this.newMerchantNameTemplate, newMerchantOptionText);
    }

    protected setInitialOption(): void {
        const initialMerchantOption: HTMLOptionElement = this.select.options[this.initiallySelectedIndex];
        initialMerchantOption.selected = true;
    }

    protected get messageTemplate(): string {
        return this.getAttribute('message-template');
    }

    protected get newMerchantNameTemplate(): string {
        return this.getAttribute('new-merchant-name-template');
    }
}

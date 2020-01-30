import Component from 'ShopUi/models/component';

interface MerchantName {
    subString: string;
    newName: string;
}

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
        this.message = this.messageTemplate;
        this.mapEvents();
    }

    protected mapEvents(): void {
        this.select.addEventListener('change', () => this.onChangeHandler());
    }

    protected onChangeHandler(): void {
        this.createMessageText();
        this.setMerchant();
    }

    /**
     * Creates a text of the confirmation question.
     */
    createMessageText(): void  {
        const currentMerchantOptionText: string = this.select.options[this.initiallySelectedIndex].text;
        const newMerchantOptionText: string = this.select.options[this.select.selectedIndex].text;
        const currentMerchant: MerchantName = {
            subString: this.currentMerchantNameTemplate,
            newSubString: currentMerchantOptionText
        };
        const newMerchant: MerchantName = {
            subString: this.newMerchantNameTemplate,
            newSubString: newMerchantOptionText
        };
        const merchantList: MerchantName[] = [currentMerchant, newMerchant];

        this.setMerchantNames(merchantList);
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

        this.setInitialOption();
    }

    protected selectInitialOption(): void {
        const initialMerchantOption: HTMLOptionElement = this.select.options[this.initiallySelectedIndex];
        initialMerchantOption.selected = true;
    }

    protected setMerchantNames(merchantList: MerchantName[]): void {
        merchantList.forEach((merchant: MerchantName) => {
            this.messageText = this.message.replace(merchant.subString, merchant.newSubString);
        });
    }

    protected set messageText(newMessage: string) {
        this.message = newMessage;
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

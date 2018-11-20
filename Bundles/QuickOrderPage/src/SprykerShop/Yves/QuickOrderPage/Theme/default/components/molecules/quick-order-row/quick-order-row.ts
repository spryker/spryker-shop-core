import Component from 'ShopUi/models/component';
import AutocompleteForm, { Events as AutocompleteEvents } from 'ShopUi/components/molecules/autocomplete-form/autocomplete-form';
import AjaxProvider from 'ShopUi/components/molecules/ajax-provider/ajax-provider';
import debounce from 'lodash-es/debounce';

export default class QuickOrderRow extends Component {
    ajaxProvider: AjaxProvider;
    autocompleteInput: AutocompleteForm;
    quantityInput: HTMLInputElement;
    errorMessage: HTMLElement;

    protected readyCallback(): void {
        this.ajaxProvider = <AjaxProvider>this.querySelector(`.${this.jsName}__provider`);
        this.autocompleteInput = <AutocompleteForm>this.querySelector('autocomplete-form');
        this.registerQuantityInput();
        this.mapEvents();
    }

    protected registerQuantityInput(): void {
        this.quantityInput = <HTMLInputElement>this.querySelector(`.${this.jsName}__quantity, .${this.jsName}-partial__quantity`);
        this.errorMessage = <HTMLElement>this.querySelector(`.${this.name}__error, .${this.name}-partial__error`);
    }

    protected mapEvents(): void {
        this.autocompleteInput.addEventListener(AutocompleteEvents.SET, (e: CustomEvent) => this.onAutocompleteSet(e));
        this.autocompleteInput.addEventListener(AutocompleteEvents.UNSET, (e: CustomEvent) => this.onAutocompleteUnset(e));
        this.autocompleteInput.addEventListener(AutocompleteEvents.SELECTITEM, debounce(this.onAutocompleteSelectItem.bind(this), this.autocompleteInput.debounceDelay));
        this.quantityInput.addEventListener('input', debounce((e: Event) => this.onQuantityChange(e), this.autocompleteInput.debounceDelay));
    }

    protected onAutocompleteSet(e: CustomEvent) {
        this.reloadField(this.autocompleteInput.inputValue);
    }

    protected onAutocompleteUnset(e: CustomEvent) {
        this.reloadField();
    }

    protected onAutocompleteSelectItem() {
        this.quantityInput.focus();
    }

    protected onQuantityChange(e: Event) {
        const isSetMinimumValue = (this.quantityValue !== '' && Number(this.quantityValue) < this.quantityMin) ? true : false;

        this.setMinimumValue(isSetMinimumValue);
        this.reloadField(this.autocompleteInput.inputValue);
    }

    protected toggleErrorMessage(isShow: boolean): void {
        if (isShow)  {
            const errorMessageClass = this.errorMessage.classList[0] + '--show';

            this.errorMessage.classList.add(errorMessageClass);
            setTimeout(() => this.errorMessage.classList.remove(errorMessageClass), 5000);
        }
    }

    protected setMinimumValue(isSet: boolean = false): void {
        if(isSet) {
            this.quantityInput.value = String(this.quantityMin);
        }
    }

    protected checkQuantityValidation(): boolean {
        const quantityInputValue = Number(this.quantityValue);
        const result = (this.quantityMax !== 0 && quantityInputValue > this.quantityMax
                        || this.quantityValue !== '' && quantityInputValue < this.quantityMin) ? true : false;

        return result;
    }

    async reloadField(sku: string = '') {
        const isShowErrorMessage = this.checkQuantityValidation(),
            quantityInputValue = parseInt(this.quantityValue);

        if (!!sku) {
            this.ajaxProvider.queryParams.set('sku', sku);
            this.ajaxProvider.queryParams.set('index', this.ajaxProvider.getAttribute('class').split('-').pop().trim());
        }

        if (!!quantityInputValue) {
            this.ajaxProvider.queryParams.set('quantity', `${quantityInputValue}`);
        }

        await this.ajaxProvider.fetch();
        this.registerQuantityInput();
        this.toggleErrorMessage(isShowErrorMessage);
        this.mapEvents();
    }

    get quantityValue(): string {
        return this.quantityInput.value;
    }

    get quantityMin(): number {
        return Number(this.quantityInput.getAttribute('min'));
    }

    get quantityMax(): number {
        return Number(this.quantityInput.getAttribute('max'));
    }
}

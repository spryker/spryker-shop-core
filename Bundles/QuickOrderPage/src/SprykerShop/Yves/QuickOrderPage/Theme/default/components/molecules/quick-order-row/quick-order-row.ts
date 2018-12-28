import Component from 'ShopUi/models/component';
import AutocompleteForm, {Events as AutocompleteEvents} from 'ShopUi/components/molecules/autocomplete-form/autocomplete-form';
import AjaxProvider from 'ShopUi/components/molecules/ajax-provider/ajax-provider';
import debounce from 'lodash-es/debounce';

const ERROR_MESSAGE_CLASS = 'quick-order-row__error--show';
const ERROR_PARTIAL_MESSAGE_CLASS = 'quick-order-row-partial__error--show';

export default class QuickOrderRow extends Component {
    ajaxProvider: AjaxProvider;
    autocompleteInput: AutocompleteForm;
    quantityInput: HTMLInputElement;
    errorMessage: HTMLElement;
    timer: number;
    timeout: number = 3000;

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
        this.autocompleteInput.addEventListener(AutocompleteEvents.SET, () => this.onAutocompleteSet());
        this.autocompleteInput.addEventListener(AutocompleteEvents.UNSET, () => this.onAutocompleteUnset());
        this.mapQuantityInputChange();
    }

    protected mapQuantityInputChange(): void {
        this.quantityInput.addEventListener('input', debounce(() => this.onQuantityChange(), this.autocompleteInput.debounceDelay));
    }

    protected onAutocompleteSet(): void {
        this.reloadField(this.autocompleteInput.inputValue);
    }

    protected onAutocompleteUnset(): void {
        this.reloadField();
    }

    protected onQuantityChange(): void {
        this.reloadField(this.autocompleteInput.inputValue);
    }

    protected hideErrorMessage(): void {
        if (!this.errorMessage) {
            return;
        }

        this.errorMessage.classList.remove(ERROR_MESSAGE_CLASS, ERROR_PARTIAL_MESSAGE_CLASS);
    }

    /**
     * Performs settings for the ajaxProvider and send an ajax request to the server.
     * @param sku string value to setting of ajaxProvider
     */
    async reloadField(sku: string = '') {
        clearTimeout(this.timer);
        const quantityInputValue = parseInt(this.quantityValue);

        this.ajaxProvider.queryParams.set('sku', sku);
        this.ajaxProvider.queryParams.set('index', this.ajaxProvider.getAttribute('class').split('-').pop().trim());

        if (!!quantityInputValue) {
            this.ajaxProvider.queryParams.set('quantity', `${quantityInputValue}`);
        }

        await this.ajaxProvider.fetch();
        this.registerQuantityInput();
        this.mapQuantityInputChange();

        this.timer = setTimeout(() => this.hideErrorMessage(), this.timeout);

        if (!!sku) {
            this.quantityInput.focus();
        }
    }

    /**
     * Gets a quantity value
     */
    get quantityValue(): string {
        return this.quantityInput.value;
    }
}

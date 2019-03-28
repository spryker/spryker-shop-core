import Component from 'ShopUi/models/component';
import AutocompleteForm, {Events as AutocompleteEvents} from 'ShopUi/components/molecules/autocomplete-form/autocomplete-form';
import AjaxProvider from 'ShopUi/components/molecules/ajax-provider/ajax-provider';
import debounce from 'lodash-es/debounce';

export default class QuickOrderRow extends Component {
    /**
     * Performs the Ajax operations.
     */
    ajaxProvider: AjaxProvider;

    /**
     * The autocomplete text input element.
     */
    autocompleteInput: AutocompleteForm;

    /**
     * The quantity number input element.
     */
    quantityInput: HTMLInputElement;

    /**
     * The default timeout.
     */
    timeout: number = 3000; // deprecated

    protected readyCallback(): void {
        this.ajaxProvider = <AjaxProvider>this.querySelector(`.${this.jsName}__provider`);
        this.autocompleteInput = <AutocompleteForm>this.querySelector('autocomplete-form');
        this.registerQuantityInput();
        this.mapEvents();
    }

    protected registerQuantityInput(): void {
        this.quantityInput = <HTMLInputElement>this.querySelector(
            `.${this.jsName}__quantity, .${this.jsName}-partial__quantity`
        );
    }

    protected mapEvents(): void {
        this.autocompleteInput.addEventListener(AutocompleteEvents.SET, () => this.onAutocompleteSet());
        this.autocompleteInput.addEventListener(AutocompleteEvents.UNSET, () => this.onAutocompleteUnset());
        this.mapQuantityInputChange();
    }

    protected mapQuantityInputChange(): void {
        this.quantityInput.addEventListener('input', debounce(() => {
            this.onQuantityChange();
        }, this.autocompleteInput.debounceDelay));
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

    /**
     * Sends an ajax request to the server and renders the response on the page.
     * @param sku A product SKU used for reloading autocomplete field.
     */
    async reloadField(sku: string = '') {
        const quantityInputValue = this.quantityValue;

        this.ajaxProvider.queryParams.set('sku', sku);
        this.ajaxProvider.queryParams.set('index', this.ajaxProvider.getAttribute('class')
            .split('-').pop().trim());

        if (!!quantityInputValue) {
            this.ajaxProvider.queryParams.set('quantity', `${quantityInputValue}`);
        }

        await this.ajaxProvider.fetch();
        this.registerQuantityInput();
        this.mapQuantityInputChange();

        if (!!sku) {
            this.quantityInput.focus();
        }
    }

    /**
     * Gets the value attribute from the quantity input field.
     */
    get quantityValue(): string {
        return this.quantityInput.value;
    }
}

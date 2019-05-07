import Component from 'ShopUi/models/component';
import AutocompleteForm, {Events as AutocompleteEvents} from 'ShopUi/components/molecules/autocomplete-form/autocomplete-form';
import AjaxProvider from 'ShopUi/components/molecules/ajax-provider/ajax-provider';

export default class ProductQuickAddFields extends Component {
    protected ajaxProvider: AjaxProvider;
    protected autocompleteInput: AutocompleteForm;
    protected quantityInput: HTMLInputElement;

    protected readyCallback(): void {
        this.ajaxProvider = <AjaxProvider>this.querySelector(`.${this.jsName}__provider`);
        this.autocompleteInput = <AutocompleteForm>this.querySelector('autocomplete-form');

        this.registerQuantityInput();
        this.mapEvents();
    }

    protected registerQuantityInput(): void {
        this.quantityInput = <HTMLInputElement>this.querySelector(
            `.${this.jsName}__quantity, ${this.partialQuantitySelector}`
        );
    }

    protected mapEvents(): void {
        this.autocompleteInput.addEventListener(AutocompleteEvents.SET, () => this.onAutocompleteSet());
        this.autocompleteInput.addEventListener(AutocompleteEvents.UNSET, () => this.onAutocompleteUnset());
    }

    protected onAutocompleteSet(): void {
        this.reloadField(this.autocompleteInput.inputValue);
    }

    protected onAutocompleteUnset(): void {
        this.reloadField();
    }

    /**
     * Sends an ajax request to the server and renders the response on the page.
     * @param sku A product SKU used for reloading autocomplete field.
     */
    async reloadField(sku: string = ''): Promise<void> {
        this.ajaxProvider.queryParams.set('sku', sku);

        await this.ajaxProvider.fetch();
        this.registerQuantityInput();

        if (!!sku) {
            this.quantityInput.focus();
        }
    }

    /**
     * Gets a querySelector name of the partial quantity field element.
     */
    get partialQuantitySelector(): string {
        return this.getAttribute('partial-quantity-selector');
    }
}

import Component from 'ShopUi/models/component';
import AutocompleteForm, { Events as AutocompleteEvents } from 'ShopUi/components/molecules/autocomplete-form/autocomplete-form';
import AjaxProvider from 'ShopUi/components/molecules/ajax-provider/ajax-provider';
import debounce from 'lodash-es/debounce';

export default class QuickOrderRow extends Component {
    ajaxProvider: AjaxProvider;
    autocompleteInput: AutocompleteForm;
    quantityInput: HTMLInputElement;

    readyCallback(): void {
        this.ajaxProvider = <AjaxProvider>this.querySelector(`.${this.jsName}__provider`);
        this.autocompleteInput = <AutocompleteForm>this.querySelector('autocomplete-form');

        this.registerQuantityInput();
        this.mapEvents();
    }

    protected registerQuantityInput(): void {
        this.quantityInput = <HTMLInputElement>this.querySelector(`.${this.jsName}__quantity, .${this.jsName}-partial__quantity`);
    }

    protected mapEvents(): void {
        this.autocompleteInput.addEventListener(AutocompleteEvents.SET, (e: CustomEvent) => this.onAutocompleteSet(e));
        this.autocompleteInput.addEventListener(AutocompleteEvents.UNSET, (e: CustomEvent) => this.onAutocompleteUnset(e));
        this.quantityInput.addEventListener('input', debounce((e: Event) => this.onQuantityChange(e), this.autocompleteInput.debounceDelay));
    }

    protected onAutocompleteSet(e: CustomEvent) {
        this.reloadField(this.autocompleteInput.inputValue);
    }

    protected onAutocompleteUnset(e: CustomEvent) {
        this.reloadField();
    }

    protected onQuantityChange(e: Event) {
        const quantity = parseInt(this.quantityInput.value);
        this.reloadField(this.autocompleteInput.inputValue, quantity);
    }

    async reloadField(sku: string = '', quantity: number = null) {
        if (!!sku) {
            this.ajaxProvider.queryParams.set('sku', sku);
        }

        if (!!quantity) {
            this.ajaxProvider.queryParams.set('quantity', `${quantity}`);
        }

        await this.ajaxProvider.fetch();
        this.registerQuantityInput();
        this.mapEvents();
    }
}

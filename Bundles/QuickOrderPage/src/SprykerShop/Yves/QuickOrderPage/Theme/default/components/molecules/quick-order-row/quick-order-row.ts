import Component from 'ShopUi/models/component';
import AutocompleteForm, {
    Events as AutocompleteEvents,
} from 'ShopUi/components/molecules/autocomplete-form/autocomplete-form';
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

    protected quantityHiddenInput: HTMLInputElement;

    /**
     * The quantity number input element.
     */
    quantityInput: HTMLInputElement;
    protected additionalFormElements: HTMLSelectElement[] | HTMLInputElement[];
    protected extraQueryParams: Map<string, string>;

    protected readyCallback(): void {
        this.ajaxProvider = <AjaxProvider>this.getElementsByClassName(`${this.jsName}__provider`)[0];
        this.autocompleteInput = <AutocompleteForm>this.getElementsByTagName('autocomplete-form')[0];

        this.registerQuantityInput();
        this.registerAdditionalFormElements();
        this.mapEvents();
    }

    protected registerQuantityInput(): void {
        this.quantityInput = <HTMLInputElement>(
            (this.getElementsByClassName(`${this.jsName}__quantity-input`)[0] ||
                this.getElementsByClassName(`${this.jsName}-partial__quantity-input`)[0])
        );
        this.quantityHiddenInput = <HTMLInputElement>(
            (this.getElementsByClassName(`${this.jsName}__hidden-input`)[0] ||
                this.getElementsByClassName(`${this.jsName}-partial__hidden-input`)[0])
        );
    }

    protected registerAdditionalFormElements(): void {
        this.additionalFormElements = <HTMLSelectElement[] | HTMLInputElement[]>(
            Array.from(this.getElementsByClassName(`${this.jsName}-partial__additional-form-element`))
        );
    }

    protected mapEvents(): void {
        this.autocompleteInput.addEventListener(AutocompleteEvents.SET, (event: CustomEvent) =>
            this.onAutocompleteSet(event),
        );
        this.autocompleteInput.addEventListener(AutocompleteEvents.UNSET, () => this.onAutocompleteUnset());
        this.mapQuantityInputChange();
        this.mapAdditionalFormElementChange();
    }

    protected mapQuantityInputChange(): void {
        this.quantityInput.addEventListener('change', () => this.reloadField(this.autocompleteInput.inputValue));
    }

    protected mapAdditionalFormElementChange(): void {
        if (!this.additionalFormElements || !this.additionalFormElements.length) {
            return;
        }

        this.additionalFormElements.forEach((item) => {
            item.addEventListener(
                'input',
                debounce(() => {
                    this.reloadField(this.autocompleteInput.inputValue);
                }, this.autocompleteInput.debounceDelay),
            );
        });
    }

    protected onAutocompleteSet(event: CustomEvent): void {
        this.extraQueryParams = event.detail.extraValues;
        this.reloadField(this.autocompleteInput.inputValue);
    }

    protected onAutocompleteUnset(): void {
        this.reloadField();
    }

    protected setQueryParams(sku: string): void {
        const quantityInputValue = this.quantityValue;

        this.ajaxProvider.queryParams.clear();
        this.ajaxProvider.queryParams.set('sku', sku);
        this.ajaxProvider.queryParams.set('index', this.index);

        if (Boolean(quantityInputValue)) {
            this.ajaxProvider.queryParams.set('quantity', `${quantityInputValue}`);
        }

        if (this.additionalFormElements && this.additionalFormElements.length) {
            this.additionalFormElements.forEach((item) => {
                if (!item.name || !item.value) {
                    return;
                }

                this.ajaxProvider.queryParams.set(item.name, item.value);
            });
        }

        if (!this.extraQueryParams) {
            return;
        }

        this.extraQueryParams.forEach((value, key) => this.ajaxProvider.queryParams.set(key, value));
    }

    /**
     * Sends an ajax request to the server and renders the response on the page.
     * @param sku A product SKU used for reloading autocomplete field.
     */
    async reloadField(sku: string = ''): Promise<void> {
        this.setQueryParams(sku);

        await this.ajaxProvider.fetch();

        this.registerQuantityInput();
        this.mapQuantityInputChange();

        this.registerAdditionalFormElements();
        this.mapAdditionalFormElementChange();

        if (!sku) {
            return;
        }

        const quantityValueLength = this.quantityValue.length;

        this.quantityInput.focus();
        this.quantityInput.setSelectionRange(quantityValueLength, quantityValueLength);
    }

    /**
     * Gets the unformatted quantity value.
     */
    get quantityValue(): string {
        return this.quantityHiddenInput.value;
    }

    protected get index(): string {
        return this.getAttribute('index');
    }
}

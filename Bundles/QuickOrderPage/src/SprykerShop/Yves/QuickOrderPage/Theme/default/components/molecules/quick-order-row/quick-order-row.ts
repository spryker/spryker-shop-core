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

        if(this.quantityInput) {
            this.quantityInputAttributes(this.quantityInput);
        }
    }

    protected mapEvents(): void {
        this.autocompleteInput.addEventListener(AutocompleteEvents.SET, (e: CustomEvent) => this.onAutocompleteSet(e));
        this.autocompleteInput.addEventListener(AutocompleteEvents.UNSET, (e: CustomEvent) => this.onAutocompleteUnset(e));
        this.quantityInput.addEventListener('input', debounce((e: Event) => this.onQuantityChange(e), this.autocompleteInput.debounceDelay));
    }

    protected quantityInputAttributes(quantityInput: HTMLInputElement): void {
        const max = quantityInput.getAttribute('max'),
              min = quantityInput.getAttribute('min') || '1',
              step = quantityInput.getAttribute('step') || '1';

        quantityInput.setAttribute('max', max);
        quantityInput.setAttribute('min', min);
        quantityInput.setAttribute('step', step);
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

    protected toggleErrorMessage(isShow: boolean): void {
        if (isShow)  {
            const errorMessageClass = this.errorMessage.classList[0] + '--show';

            setTimeout(() => this.errorMessage.classList.add(errorMessageClass), 0);
            setTimeout(() => this.errorMessage.classList.remove(errorMessageClass), 5000);
        }
    }
    
    protected setMinimumValue(isSet: boolean = false, minimumValue: string): void {
        if(isSet) {
            this.quantityInput.value = minimumValue;
        }
    }

    protected checkQuantityValidation(quantityValue: string, quantityMin: string, quantityMax: string): boolean {
        const result = (quantityMax !== '' && quantityValue > quantityMax
                        || quantityValue !== '' && quantityValue < quantityMin) ? true : false;

        return result;
    }

    async reloadField(sku: string = '', quantity: number = null) {
        const quantityValue = this.quantityInput.value,
              quantityMin = this.quantityInput.getAttribute('min'),
              quantityMax = this.quantityInput.getAttribute('max'),
              isSetMinimumValue = (quantityValue !== '' && quantityValue < quantityMin) ? true : false,
              isShowErrorMessage = this.checkQuantityValidation(quantityValue, quantityMin, quantityMax);

        if (!!sku) {
            this.ajaxProvider.queryParams.set('sku', sku);
            this.ajaxProvider.queryParams.set('index', this.ajaxProvider.getAttribute('class').split('-').pop().trim());
        }

        if (!!quantity) {
            this.ajaxProvider.queryParams.set('quantity', `${quantity}`);
        }

        await this.ajaxProvider.fetch();
        this.registerQuantityInput();
        this.toggleErrorMessage(isShowErrorMessage);
        this.setMinimumValue(isSetMinimumValue, quantityMin);
        this.mapEvents();
    }
}

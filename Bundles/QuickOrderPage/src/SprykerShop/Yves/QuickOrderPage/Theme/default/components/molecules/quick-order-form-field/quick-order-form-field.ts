import Component from 'ShopUi/models/component';
import AutocompleteForm from 'ShopUi/components/molecules/autocomplete-form/autocomplete-form';
import AjaxProvider from 'ShopUi/components/molecules/ajax-provider/ajax-provider';

interface ProductJSON {
    baseMeasurementUnit: {
        code: string,
        defaultPrecision: number,
        idProductMeasurementUnit: number,
        name: string,
    },
    idProductConcrete: number,
    productQuantityStorage: {
        idProduct: number,
        quantityInterval: number,
        quantityMax: number,
        quantityMin: number
    }
}

export default class QuickOrderFormField extends Component {
    autocompleteForm: AutocompleteForm;
    selectedItem: HTMLElement;
    productLoadedEvent: CustomEvent;
    productDeleteEvent: CustomEvent;
    autocompleteFormInput: HTMLInputElement;
    ajaxProvider: AjaxProvider;
    productData: ProductJSON;

    protected readyCallback(): void {
        this.autocompleteForm = <AutocompleteForm>this.querySelector('autocomplete-form');
        this.autocompleteFormInput = <HTMLInputElement>this.autocompleteForm.querySelector(`.${this.autocompleteForm.jsName}__input`);
        this.ajaxProvider = <AjaxProvider>this.querySelector(`.${this.jsName}__provider`);

        this.mapEvents();
    }

    protected mapEvents(): void {
        this.createCustomEvents();
        this.addEventListener('click', (event: Event) => this.onClick(<Event>event));

        this.autocompleteFormInput.addEventListener('input', () => this.onChange());
    }

    private createCustomEvents(): void {
        this.productLoadedEvent = <CustomEvent>new CustomEvent("product-loaded-event");

        this.productDeleteEvent = <CustomEvent>new CustomEvent("product-delete-event");
    }

    private onClick(event: Event): void {
        this.selectedItem = <HTMLElement>event.target;

        if (this.selectedItem.matches(this.autocompleteForm.itemSelector)) {
            event.stopPropagation();
            this.loadProduct();
        }
    }

    private onChange(): void {
        if(this.autocompleteFormInput.value.length <= this.autocompleteForm.minLetters) {
            this.productData = <ProductJSON>{};
            this.dispatchEvent(<CustomEvent>this.productDeleteEvent);
        }
    }

    async loadProduct(): Promise<void> {
        this.ajaxProvider.queryParams.set('id-product', <string>this.selectedId);

        try {
            const response: string = <string>await this.ajaxProvider.fetch();
            this.productData = <ProductJSON>this.generateResponseData(response);

            this.dispatchEvent(<CustomEvent>this.productLoadedEvent);
        } catch (err) {
            throw err;
        }
    }

    private generateResponseData(response: string): ProductJSON {
        return JSON.parse(response);
    }

    get selectedId(): string {
        return this.selectedItem.getAttribute('data-id-product');
    }
}

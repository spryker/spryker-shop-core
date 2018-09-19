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
        quantityMin: 1
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
        this.addEventListener('click', (event: Event) => this.componentClickHandler(event));

        this.autocompleteFormInput.addEventListener('input', () => this.autocompleteFormInputOnChange());
    }

    private createCustomEvents(): void {
        this.productLoadedEvent = <CustomEvent>new CustomEvent("product-loaded-event", {
            detail: {
                username: "product-loaded-event"
            }
        });

        this.productDeleteEvent = <CustomEvent>new CustomEvent("product-delete-event", {
            detail: {
                username: "product-delete-event"
            }
        });
    }

    private componentClickHandler(event: Event): void {
        this.selectedItem = <HTMLElement>event.target;

        if (this.selectedItem.matches(this.autocompleteForm.itemSelector)) {
            event.stopPropagation();
            this.loadProduct();
        }
    }

    async loadProduct(): Promise<void> {
        this.ajaxProvider.queryParams.set('id-product', this.selectedId);

        try {
            const response: string = <string>await this.ajaxProvider.fetch();
            this.productData = <ProductJSON>this.generateResponseData(response);

            this.dispatchEvent(this.productLoadedEvent);
        } catch (err) {
            throw err;
        }
    }

    private generateResponseData(response: string): ProductJSON {
        return Object.assign({}, JSON.parse(response));
    }

    private autocompleteFormInputOnChange(): void {
        if(this.autocompleteFormInput.value.length <= this.autocompleteForm.minLetters) {
            this.productData = <ProductJSON>{};
            this.dispatchEvent(this.productDeleteEvent);
        }
    }

    get selectedId(): string {
        return this.selectedItem.getAttribute('data-id-product');
    }

    get productId(): string {
        return this.autocompleteForm.hiddenInputElement.dataset.id;
    }
}

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
    idProductAbstract: number,
    productQuantity: {
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
    hiddenIdInputElement: HTMLInputElement;
    ajaxProvider: AjaxProvider;
    productData: ProductJSON;

    protected readyCallback(): void {
        this.autocompleteForm = <AutocompleteForm>this.querySelector('autocomplete-form');
        this.autocompleteFormInput = <HTMLInputElement>this.autocompleteForm.querySelector(`.${this.autocompleteForm.jsName}__input`);
        this.hiddenIdInputElement = <HTMLInputElement>document.querySelector(`input[name='${this.hiddenIdInputName}']`);
        this.ajaxProvider = <AjaxProvider>this.querySelector(`.${this.jsName}__provider`);

        this.mapEvents();
    }

    protected mapEvents(): void {
        this.createCustomEvents();
        this.addEventListener('click', (event: Event) => this.onClick(<Event>event));

        this.autocompleteForm.addEventListener('products-loaded-event', () => this.onSkuCoincidence());
        this.autocompleteFormInput.addEventListener('input', () => this.onChange());
        this.autocompleteFormInput.addEventListener('keydown', (event: KeyboardEvent) => this.onInputKeyDown(event));
    }

    private createCustomEvents(): void {
        this.productLoadedEvent = <CustomEvent>new CustomEvent("product-loaded-event");

        this.productDeleteEvent = <CustomEvent>new CustomEvent("product-delete-event");
    }

    private onClick(event: Event): void {
        this.selectedItem = <HTMLElement>event.target;

        if (this.selectedItem.matches(this.autocompleteForm.itemSelector)) {
            event.stopPropagation();
            this.setInputId(this.selectedProductId);
            this.loadProduct();
        }
    }

    private onChange(): void {
        if(this.autocompleteFormInput.value.length <= this.autocompleteForm.minLetters) {
            this.productData = <ProductJSON>{};
            this.dispatchEvent(<CustomEvent>this.productDeleteEvent);
        }
    }

    private onInputKeyDown(event: KeyboardEvent): void {
        const keyCode = event.keyCode;
        const dropDownItems = <NodeList>this.querySelectorAll(this.autocompleteForm.itemSelector);

        if(this.autocompleteFormInput.value.length && dropDownItems.length && keyCode === 9) {
            (<HTMLElement>dropDownItems[0]).click();
            return;
        }
    }

    private onSkuCoincidence(): void {
        const dropDownItems = <NodeList>this.querySelectorAll(this.autocompleteForm.itemSelector);

        if(dropDownItems.length === 1) {
            (<HTMLElement>dropDownItems[0]).click();
            this.autocompleteFormInput.blur();
            return;
        }
    }

    async loadProduct(): Promise<void> {
        this.ajaxProvider.queryParams.set('id-product', <string>this.selectedProductId);

        try {
            const response: string = <string>await this.ajaxProvider.fetch();
            this.productData = <ProductJSON>this.generateResponseData(response);
            this.productData.idProductAbstract = +this.selectedProductAbstractId;
            this.dispatchEvent(<CustomEvent>this.productLoadedEvent);
        } catch (err) {
            throw err;
        }
    }

    private generateResponseData(response: string): ProductJSON {
        return JSON.parse(response);
    }

    private setInputId(data: string): void {
        this.hiddenIdInputElement.value = data;
    }

    get selectedProductId(): string {
        return this.selectedItem.getAttribute('data-id-product');
    }

    get selectedProductAbstractId(): string {
        return this.selectedItem.getAttribute('data-id-product-abstract');
    }

    get hiddenIdInputName(): string {
        return this.autocompleteForm.getAttribute('hidden-id-input-name');
    }
}

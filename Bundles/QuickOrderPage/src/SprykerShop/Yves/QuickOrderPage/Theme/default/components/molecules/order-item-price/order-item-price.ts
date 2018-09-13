import Component from 'ShopUi/models/component';
import AjaxProvider from 'ShopUi/components/molecules/ajax-provider/ajax-provider';
import QuickOrderFormField from '../quick-order-form-field/quick-order-form-field';

interface PricesJSON {
    idProductConcrete: number,
    total: number,
    quantity: number,
    currentProductPrice:{
        price: any,
        prices: Array<any>
    },
    currency: {
        idCurrency: any,
        code: string,
        name: string,
        symbol: string,
        isDefault: boolean,
        fractionDigits: number
    }
}

export default class OrderItemPrice extends Component {
    ajaxProvider: AjaxProvider;
    currentFieldComponent: QuickOrderFormField;
    wrapperInjector: HTMLElement;
    quantityInput: HTMLFormElement;
    timerId: number;

    protected readyCallback(): void {
        this.wrapperInjector = <HTMLElement>this.querySelector(`.${this.jsName}`);
        this.quantityInput = <HTMLFormElement>document.querySelector(this.quantityInputSelector);
        this.ajaxProvider = <AjaxProvider>this.querySelector('ajax-provider');
        this.currentFieldComponent = <QuickOrderFormField>this.closest('quick-order-form-field');

        this.mapEvents();
    }

    protected mapEvents(): void {
        this.quantityInput.addEventListener('change', () => this.addIdHandler(this.productId, this.quantityCount));

        document.addEventListener('application-bootstrap-completed', () => {
            this.currentFieldComponent.autocompleteForm.hiddenInputElement.addEventListener('addId', () =>{
                this.addIdHandler(this.productId, this.quantityCount);
            });
        });
    }

    private addIdHandler(productId: string, quantityCount: number): void {
        if (productId && quantityCount > 0) {
            clearImmediate(<number>this.timerId);
            this.timerId = <number>setTimeout(() => this.loadPrices(productId, quantityCount), 150);
            return;
        }

        this.injectData('');
    }

    async loadPrices(id: string, count: number): Promise<void> {
        this.ajaxProvider.queryParams.set('quantity', String(count));
        this.ajaxProvider.queryParams.set('id-product', id);

        try {
            const response: string = await this.ajaxProvider.fetch();
            const data: PricesJSON = this.generateResponseData(response);
            this.injectData(this.generateDataToInject(data));
        } catch (err) {
            throw err;
        }
    }

    private generateResponseData(response: string): PricesJSON {
        return Object.assign({}, JSON.parse(response));
    }

    private generateDataToInject(data: PricesJSON): string {
        return `${data.currency.symbol} ${data.total / 100}`;
    }

    private injectData(data: string): void {
        this.wrapperInjector.innerHTML = data;
    }

    get quantityCount(): number {
        return Number(this.quantityInput.value);
    }

    get quantityInputSelector(): string {
        return this.getAttribute('quantity-input-selector');
    }

    get productId(): string {
        return this.currentFieldComponent.productId;
    }
}

import Component from 'ShopUi/models/component';
import AjaxProvider from 'ShopUi/components/molecules/ajax-provider/ajax-provider';
import QuickOrderFormField from '../quick-order-form-field/quick-order-form-field';
import OrderQuantity from '../order-quantity/order-quantity';

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
    quantityComponent: OrderQuantity;
    wrapperInjector: HTMLElement;
    timerId: number;

    protected readyCallback(): void {
        this.wrapperInjector = <HTMLElement>this.querySelector(`.${this.jsName}`);
        this.ajaxProvider = <AjaxProvider>this.querySelector('ajax-provider');
        this.currentFieldComponent = <QuickOrderFormField>this.closest('quick-order-form-field');
        this.quantityComponent = <OrderQuantity>this.currentFieldComponent.querySelector('order-quantity');

        this.mapEvents();
    }

    protected mapEvents(): void {
        this.quantityComponent.addEventListener('quantity-input-update', () => this.changePrice());
        this.currentFieldComponent.addEventListener('product-delete-event', () => this.changePrice());
    }

    private changePrice(): void {
        if (this.productId && this.quantityCount > 0) {
            clearTimeout(<number>this.timerId);
            this.timerId = <number>setTimeout(() => this.loadPrices(), 250);
            return;
        }

        this.injectData('');
    }

    async loadPrices(): Promise<void> {
        this.ajaxProvider.queryParams.set('quantity', String(this.quantityCount));
        this.ajaxProvider.queryParams.set('id-product', String(this.productId));

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
        return this.quantityComponent.currentInputValue;
    }

    get productId(): number {
        return this.currentFieldComponent.productData.idProductConcrete;
    }
}

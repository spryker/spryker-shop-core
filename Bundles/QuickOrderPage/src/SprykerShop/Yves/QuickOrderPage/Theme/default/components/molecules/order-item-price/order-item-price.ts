import Component from 'ShopUi/models/component';
import AjaxProvider from 'ShopUi/components/molecules/ajax-provider/ajax-provider';
import QuickOrderFormField from '../quick-order-form-field/quick-order-form-field';
import OrderQuantity from '../order-quantity/order-quantity';

interface PricesJSON {
    idProductConcrete: number,
    quantity: number,
    currentProductPrice:{
        price: any,
        prices: Array<any>,
        sumPrice: any,
        currency: {
            idCurrency: any,
            code: string,
            name: string,
            symbol: string,
            isDefault: boolean,
            fractionDigits: number
        }
    }
}

export default class OrderItemPrice extends Component {
    ajaxProvider: AjaxProvider;
    currentFieldComponent: QuickOrderFormField;
    quantityComponent: OrderQuantity;
    wrapperForInsertData: HTMLElement;

    protected readyCallback(): void {
        this.wrapperForInsertData = <HTMLElement>this.querySelector(`.${this.jsName}`);
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
            this.loadPrices();
            return;
        }

        this.injectData('');
    }

    async loadPrices(): Promise<void> {
        this.ajaxProvider.queryParams.set('quantity', String(this.quantityCount));
        this.ajaxProvider.queryParams.set('id-product', String(this.productId));

        try {
            const response: string = await this.ajaxProvider.fetch();
            const data: PricesJSON = <PricesJSON>this.parseResponseData(<string>response);
            this.injectData(this.generateDataToInject(data));
        } catch (err) {
            throw err;
        }
    }

    private parseResponseData(response: string): PricesJSON {
        return <PricesJSON>JSON.parse(response);
    }

    private generateDataToInject(data: PricesJSON): string {
        return <string>`${data.currentProductPrice.currency.symbol} ${data.currentProductPrice.sumPrice / 10 ** data.currentProductPrice.currency.fractionDigits}`;
    }

    private injectData(data: string): void {
        this.wrapperForInsertData.innerHTML = <string>data;
    }

    get quantityCount(): number {
        return this.quantityComponent.currentInputValue;
    }

    get productId(): number {
        return this.currentFieldComponent.productData ? this.currentFieldComponent.productData.idProductConcrete : 0;
    }
}

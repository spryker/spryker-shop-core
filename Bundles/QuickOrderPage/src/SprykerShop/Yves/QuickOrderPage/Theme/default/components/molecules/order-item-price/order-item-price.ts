import Component from 'ShopUi/models/component';
import AjaxProvider from 'ShopUi/components/molecules/ajax-provider/ajax-provider';

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
    wrapperInjector: HTMLElement;
    addIdEvent: CustomEvent;
    ajaxProvider: AjaxProvider;
    quantityInput: HTMLFormElement;
    timerId: number;

    protected readyCallback(): void {
        this.wrapperInjector = <HTMLElement>this.querySelector(`.${this.jsName}`);
        this.quantityInput = <HTMLFormElement>document.querySelector(this.quantityInputSelector);
        this.ajaxProvider = <AjaxProvider>this.querySelector('ajax-provider');

        this.mapEvents();
    }

    protected mapEvents(): void {
        this.createCustomEvents();

        this.addEventListener('addId', () => this.addIdHandler());
        this.quantityInput.addEventListener('change', () => this.dispatchEvent(this.addIdEvent));
    }

    public addDataIdValue(id: string): void {
        this.setAttribute('data-id', id);
        this.dispatchEvent(this.addIdEvent);
    }

    private createCustomEvents(): void {
        this.addIdEvent = <CustomEvent>new CustomEvent("addId", {
            detail: {
                username: "addId"
            }
        });
    }

    private addIdHandler(): void {
        if (this.dataId && this.quantityCount > 0) {
            clearImmediate(<number>this.timerId);
            this.timerId = <number>setTimeout(() => this.loadPrices(this.dataId, this.quantityCount), 150);
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

    get dataId(): string {
        return this.getAttribute('data-id')
    }
}

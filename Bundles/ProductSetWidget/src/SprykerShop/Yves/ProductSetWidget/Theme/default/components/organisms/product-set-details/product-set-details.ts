import Component from 'ShopUi/models/component';
import ProductItem, { EVENT_UPDATE_ADD_TO_CART_URL } from 'ShopUi/components/molecules/product-item/product-item';

export default class ProductSetDetails extends Component {
    protected productItems: ProductItem[];
    protected targets: HTMLInputElement[];

    protected readyCallback(): void {}

    protected init(): void {
        this.productItems = <ProductItem[]>Array.from(this.getElementsByClassName(`${this.jsName}__product-item`));
        this.targets = <HTMLInputElement[]>Array.from(this.getElementsByClassName(`${this.jsName}__product-sku-hidden-input`));

        this.mapEvents();
    }

    protected mapEvents(): void {
        if (this.productItems) {
            this.productItems.forEach((element: ProductItem, index: number) => {
                element.addEventListener(EVENT_UPDATE_ADD_TO_CART_URL, (event: Event) => {
                    this.onCustomEvent((<CustomEvent>event).detail.sku, index);
                });
            });
        }
    }

    protected onCustomEvent(sku: string, index: number): void {
        this.targets[index].value = sku;
    }
}

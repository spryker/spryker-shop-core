import Component from 'ShopUi/models/component';

interface VolumePricesData {
    price: string;
    count: number;
}

export default class VolumePrice extends Component {
    /**
     * The product price text element.
     */
    productPriceElement: HTMLElement;

    /**
     * Data object of the volume prices list.
     */
    volumePricesData: VolumePricesData[];

    /**
     * The volume select/input element.
     */
    quantityElement: HTMLFormElement;

    /**
     * The custom class for price animation.
     */
    highLightedClass: string;

    /**
     * The current quantity select/input value.
     */
    currentQuantityValue: number;
    protected timeout: number = 400;

    protected readyCallback(): void {
        this.productPriceElement = <HTMLElement>this.querySelector(`.${this.jsName}__price`);
        this.volumePricesData = <VolumePricesData[]>JSON.parse(this.dataset.json).reverse();
        this.quantityElement = <HTMLFormElement>document.querySelector(`.${this.jsName}__quantity`);
        this.highLightedClass = <string>`${this.name}__price--highlighted`;

        this.mapEvents();
    }

    protected mapEvents(): void {
        if (!this.quantityElement) {
            return;
        }

        this.quantityElement.addEventListener('change', (event: Event) => {
            this.quantityChangeHandler(event);
        });
    }

    protected quantityChangeHandler(event: Event): void {
        this.currentQuantityValue = Number((<HTMLInputElement>event.target).value);
        this.checkQuantityValue();
    }

    protected checkQuantityValue(): void {
        this.volumePricesData.every((item: VolumePricesData) => {
            return this.checkQuantityValueCallback(item);
        });
    }

    protected checkQuantityValueCallback(priceData: VolumePricesData): boolean {
        const volumePrice: string = priceData.price;
        const volumePriceCount: number = priceData.count;

        if (this.currentQuantityValue >= volumePriceCount) {
            this.changePrice(volumePrice);

            return false;
        }

        return true;
    }

    protected changePrice(price: string): void {
        if (this.productPriceElement.innerText !== price) {
            this.productPriceElement.innerHTML = price;
            this.highlight();
        }
    }

    protected highlight(): void {
        const classList = this.productPriceElement.classList;

        classList.add(this.highLightedClass);
        setTimeout(() => classList.remove(this.highLightedClass), this.timeout);
    }
}

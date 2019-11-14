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
     *
     * @deprecated
     */
    currentQuantityValue: number;
    protected timeout: number = 400;

    protected readyCallback(): void {}

    protected init(): void {
        this.productPriceElement = <HTMLElement>this.getElementsByClassName(`${this.jsName}__price`)[0];
        this.volumePricesData = <VolumePricesData[]>JSON.parse(this.dataset.json);
        this.quantityElement = <HTMLFormElement>document.getElementsByClassName(`${this.jsName}__quantity`)[0];
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
        const currentQuantityValue = Number((<HTMLInputElement>event.target).value);
        const defaultPrice = this.volumePricesData[0].price;

        this.changePrice(defaultPrice);

        this.volumePricesData.forEach((item: VolumePricesData) => {
            if (currentQuantityValue !== Number(item.count)) {
                return;
            }

            this.changePrice(item.price);
        });
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

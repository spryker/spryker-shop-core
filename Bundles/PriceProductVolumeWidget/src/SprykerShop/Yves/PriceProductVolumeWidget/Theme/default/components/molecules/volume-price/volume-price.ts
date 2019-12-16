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
    highLightedClass: string = `${this.name}__price--highlighted`;

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
        this.quantityElement = <HTMLFormElement>document.getElementsByClassName(`${this.jsName}__quantity`)[0];

        this.mapEvents();
        this.sortVolumePriceData();
    }

    protected mapEvents(): void {
        if (!this.quantityElement) {
            return;
        }

        this.quantityElement.addEventListener('change', (event: Event) => {
            this.quantityChangeHandler(event);
        });
    }

    protected sortVolumePriceData(): void {
        const volumePricesData = <VolumePricesData[]>JSON.parse(this.dataset.json);

        this.volumePricesData = <VolumePricesData[]>volumePricesData.sort((firstElement, secondElement) => {
            return secondElement.count - firstElement.count;
        });
    }

    protected quantityChangeHandler(event: Event): void {
        const currentQuantityValue = Number((<HTMLInputElement>event.target).value);

        this.volumePricesData.every((item: VolumePricesData) => {
            if (currentQuantityValue >= Number(item.count)) {
                this.changePrice(item.price);

                return false;
            }

            return true;
        });
    }

    protected changePrice(price: string): void {
        if (this.productPriceElement.innerText === price) {
            return;
        }

        this.productPriceElement.innerText = price;
        this.highlight();
    }

    protected highlight(): void {
        const classList = this.productPriceElement.classList;

        classList.add(this.highLightedClass);
        setTimeout(() => classList.remove(this.highLightedClass), this.timeout);
    }
}

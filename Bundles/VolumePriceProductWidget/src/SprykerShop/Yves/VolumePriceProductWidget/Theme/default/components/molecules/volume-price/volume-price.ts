import Component from 'ShopUi/models/component';

export default class VolumePrice extends Component {
    productPriceElement: HTMLElement
    volumePricesData: Object[]
    quantityElement: HTMLFormElement

    protected readyCallback(): void {
        this.productPriceElement = <HTMLElement>this.querySelector(`.${this.jsName}__price`);
        this.volumePricesData = <Object[]>JSON.parse(this.dataset.json);
        this.quantityElement = <HTMLFormElement>document.querySelector(`.${this.jsName}-quantity`);

        this.mapEvents();
    }

    private mapEvents(): void {
        this.quantityElement.addEventListener('change', this.quantityChangeHandler.bind(this));
    }

    private quantityChangeHandler(event): void {
        const currentQuantityuValue = <Number> Number(event.target.value);

        this.privateCheckQuantityValue(currentQuantityuValue);
    }

    private privateCheckQuantityValue(currentQuantityuValue): void {
        this.removeHighlight();

        for(let i = this.volumePricesData.length - 1; i >= 0; i--) {
            const volumePrice: String = (<Object>this.volumePricesData[i])['price'];
            const volumePriceCount: Number = Number((<Object>this.volumePricesData[i])['count']);

            if(currentQuantityuValue >= volumePriceCount) {
                this.changePrice(volumePrice);
                break;
            }
        }
    }

    private changePrice(price): void {
        if(this.productPriceElement.innerText !== price) {
            this.productPriceElement.innerHTML = price;
            this.addHighlight();
        }
    }

    private addHighlight(): void {
        this.productPriceElement.classList.add(`${this.name}__price--highlighted`);
    }

    private removeHighlight(): void {
        this.productPriceElement.classList.remove(`${this.name}__price--highlighted`);
    }
}
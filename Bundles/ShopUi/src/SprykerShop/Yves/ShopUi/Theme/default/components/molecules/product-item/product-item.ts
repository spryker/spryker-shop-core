import Component from '../../../models/component';

export const EVENT_UPDATE_RATING = 'updateRating';

export interface ProductItemData {
    imageUrl: string;
    name: string;
    rating: number;
    defaultPrice: string;
    originalPrice: string;
    detailPageUrl: string;
    addToCartUrl: string;
}

export default class ProductItem extends Component {
    protected productImage: HTMLImageElement;
    protected productName: HTMLElement;
    protected productRating: HTMLElement;
    protected productDefaultPrice: HTMLElement;
    protected productOriginalPrice: HTMLElement;
    protected productLinkDetailPage: HTMLAnchorElement[];
    protected productLinkAddToCart: HTMLAnchorElement;

    protected readyCallback(): void {}

    protected init(): void {
        this.productImage = <HTMLImageElement>this.getElementsByClassName(`${this.jsName}__image`)[0];
        this.productName = <HTMLElement>this.getElementsByClassName(`${this.jsName}__name`)[0];
        this.productRating = <HTMLElement>this.getElementsByClassName(`${this.jsName}__rating`)[0];
        this.productDefaultPrice = <HTMLElement>this.getElementsByClassName(`${this.jsName}__default-price`)[0];
        this.productOriginalPrice = <HTMLElement>this.getElementsByClassName(`${this.jsName}__original-price`)[0];
        this.productLinkDetailPage = <HTMLAnchorElement[]>Array.from(this.getElementsByClassName(`${this.jsName}__link-detail-page`));
        this.productLinkAddToCart = <HTMLAnchorElement>this.getElementsByClassName(`${this.jsName}__link-add-to-cart`)[0];

    }

    protected set newImageUrl(imageUrl: string) {
        this.productImage.src = imageUrl;
    }

    protected set newName(name: string) {
        this.productName.innerText = name;
    }

    protected set newRating(rating: number) {
        this.dispatchCustomEvent(EVENT_UPDATE_RATING, {rating: rating});
    }

    protected set newDefaultPrice(defaultPrice: string) {
        this.productDefaultPrice.innerText = defaultPrice;
    }

    protected set newOriginalPrice(originalPrice: string) {
        this.productOriginalPrice.innerText = originalPrice;
    }

    protected set newDetailPageUrl(detailPageUrl: string) {
        this.productLinkDetailPage.href = detailPageUrl;
    }

    protected set newAddToCartUrl(addToCartUrl: string) {
        this.productLinkAddToCart.href = addToCartUrl;
    }
}

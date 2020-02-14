import Component from '../../../models/component';

/**
 * @event updateRating An event emitted when the product rating has been updated.
 * @event updateAddToCartUrl An event emitted when the product 'add to cart' URL has been updated.
 */
export const EVENT_UPDATE_RATING = 'updateRating';
export const EVENT_UPDATE_ADD_TO_CART_URL = 'updateAddToCartUrl';

export interface ProductItemData {
    imageUrl: string;
    nameValue: string;
    ratingValue: number;
    defaultPrice: string;
    originalPrice: string;
    detailPageUrl: string;
    addToCartUrl: string;
}

export default class ProductItem extends Component {
    protected productImage: HTMLImageElement;
    protected productName: HTMLElement;
    protected productRating: HTMLInputElement;
    protected productDefaultPrice: HTMLElement;
    protected productOriginalPrice: HTMLElement;
    protected productLinkDetailPage: HTMLAnchorElement[];
    protected productLinkAddToCart: HTMLAnchorElement;

    protected readyCallback(): void {}

    protected init(): void {
        this.productImage = <HTMLImageElement>this.getElementsByClassName(`${this.jsName}__image`)[0];
        this.productName = <HTMLElement>this.getElementsByClassName(`${this.jsName}__name`)[0];
        this.productRating = <HTMLInputElement>this.getElementsByClassName(`${this.jsName}__rating`)[0];
        this.productDefaultPrice = <HTMLElement>this.getElementsByClassName(`${this.jsName}__default-price`)[0];
        this.productOriginalPrice = <HTMLElement>this.getElementsByClassName(`${this.jsName}__original-price`)[0];
        this.productLinkDetailPage = <HTMLAnchorElement[]>Array.from(this.getElementsByClassName(
            `${this.jsName}__link-detail-page`
        ));
        this.productLinkAddToCart = <HTMLAnchorElement>this.getElementsByClassName(
            `${this.jsName}__link-add-to-cart`
        )[0];
    }

    /**
     * Sets the product card information.
     * @param data A data object for setting the product card information.
     */
    updateProductItemData(data: ProductItemData): void {
        this.imageUrl = data.imageUrl;
        this.nameValue = data.nameValue;
        this.ratingValue = data.ratingValue;
        this.defaultPrice = data.defaultPrice;
        this.originalPrice = data.originalPrice;
        this.detailPageUrl = data.detailPageUrl;
        this.addToCartUrl = data.addToCartUrl;
    }

    /**
     * Sets the product card image URL.
     */
    set imageUrl(imageUrl: string) {
        if (this.productImage) {
            this.productImage.src = imageUrl;
        }
    }

    /**
     * Sets the product card name.
     */
    set nameValue(name: string) {
        if (this.productName) {
            this.productName.innerText = name;
        }
    }

    /**
     * Sets the product card rating.
     */
    set ratingValue(rating: number) {
        this.dispatchCustomEvent(EVENT_UPDATE_RATING, {rating});
    }

    /**
     * Sets the product card default price.
     */
    set defaultPrice(defaultPrice: string) {
        if (this.productDefaultPrice) {
            this.productDefaultPrice.innerText = defaultPrice;
        }
    }

    /**
     * Sets the product card original price.
     */
    set originalPrice(originalPrice: string) {
        if (this.productOriginalPrice) {
            this.productOriginalPrice.innerText = originalPrice;
        }
    }

    /**
     * Sets the product card detail page URL.
     */
    set detailPageUrl(detailPageUrl: string) {
        if (this.productLinkDetailPage) {
            this.productLinkDetailPage.forEach((element: HTMLAnchorElement) => element.href = detailPageUrl);
        }
    }

    /**
     * Sets the product card 'add to cart' URL.
     */
    set addToCartUrl(addToCartUrl: string) {
        if (this.productLinkAddToCart) {
            this.productLinkAddToCart.href = addToCartUrl;
        }

        this.dispatchCustomEvent(EVENT_UPDATE_ADD_TO_CART_URL, {sku: addToCartUrl.split('/').pop()});
    }

    /**
     * Gets the product card image URL.
     */
    get imageUrl(): string {
        if (this.productImage) {
            return this.productImage.src;
        }
    }

    /**
     * Gets the product card name.
     */
    get nameValue(): string {
        if (this.productName) {
            return this.productName.innerText;
        }
    }

    /**
     * Gets the product card rating.
     */
    get ratingValue(): number {
        if (this.productRating) {
            return Number(this.productRating.value);
        }
    }

    /**
     * Gets the product card default price.
     */
    get defaultPrice(): string {
        if (this.productDefaultPrice) {
            return this.productDefaultPrice.innerText;
        }
    }

    /**
     * Gets the product card original price.
     */
    get originalPrice(): string {
        if (this.productOriginalPrice) {
            return this.productOriginalPrice.innerText;
        }
    }

    /**
     * Gets the product card detail page URL.
     */
    get detailPageUrl(): string {
        if (this.productLinkDetailPage) {
            return this.productLinkDetailPage[0].href;
        }
    }

    /**
     * Gets the product card 'add to cart' URL.
     */
    get addToCartUrl(): string {
        if (this.productLinkAddToCart) {
            return this.productLinkAddToCart.href;
        }
    }
}

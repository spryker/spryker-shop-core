/* tslint:disable: max-file-line-count */
import Component from '../../../models/component';

export const EVENT_UPDATE_RATING = 'updateRating';
export const EVENT_UPDATE_ADD_TO_CART_URL = 'updateAddToCartUrl';

/**
 * @event updateRating An event emitted when the product rating has been updated.
 * @event updateAddToCartUrl An event emitted when the product 'add to cart' URL has been updated.
 */
export interface ProductItemData {
    imageUrl: string;
    labels: ProductItemLabelsData[];
    nameValue: string;
    ratingValue: number;
    defaultPrice: string;
    originalPrice: string;
    detailPageUrl: string;
    addToCartUrl: string;
}

export interface ProductItemLabelsData {
    text: string;
    type: string;
}

export default class ProductItem extends Component {
    protected productImage: HTMLImageElement;
    protected productLabelFlags: HTMLElement[];
    protected productLabelTag: HTMLElement;
    protected productName: HTMLElement;
    protected productRating: HTMLInputElement;
    protected productDefaultPrice: HTMLElement;
    protected productOriginalPrice: HTMLElement;
    protected productLinkDetailPage: HTMLAnchorElement[];
    protected productLinkAddToCart: HTMLAnchorElement;

    protected readyCallback(): void {}

    protected init(): void {
        this.productImage = <HTMLImageElement>this.getElementsByClassName(`${this.jsName}__image`)[0];
        this.productLabelFlags = <HTMLElement[]>Array.from(this.getElementsByClassName(`${this.jsName}__label-flag`));
        this.productLabelTag = <HTMLElement>this.getElementsByClassName(`${this.jsName}__label-tag`)[0];
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
        this.newImageUrl = data.imageUrl;
        this.newLabels = data.labels;
        this.newNameValue = data.nameValue;
        this.newRatingValue = data.ratingValue;
        this.newDefaultPrice = data.defaultPrice;
        this.newOriginalPrice = data.originalPrice;
        this.newDetailPageUrl = data.detailPageUrl;
        this.newAddToCartUrl = data.addToCartUrl;
    }

    protected set newImageUrl(imageUrl: string) {
        if (this.productImage) {
            this.productImage.src = imageUrl;
        }
    }

    protected set newLabels(labels: ProductItemLabelsData[]) {
        if (!labels.length) {
            this.productLabelFlags.forEach((element: HTMLElement) => element.classList.add(this.classToToggle));
            this.productLabelTag.classList.add(this.classToToggle);

            return;
        }

        labels.forEach((element: ProductItemLabelsData, index: number) => {
            this.createProductLabelFlagClones(index);
            this.deleteProductLabelFlagClones(labels);
            this.deleteProductLabelFlagModifiers(index);
            this.updateProductLabelFlags(element, index);
        });
    }

    protected set newNameValue(name: string) {
        if (this.productName) {
            this.productName.innerText = name;
        }
    }

    protected set newRatingValue(rating: number) {
        this.dispatchCustomEvent(EVENT_UPDATE_RATING, {rating});
    }

    protected set newDefaultPrice(defaultPrice: string) {
        if (this.productDefaultPrice) {
            this.productDefaultPrice.innerText = defaultPrice;
        }
    }

    protected set newOriginalPrice(originalPrice: string) {
        if (this.productOriginalPrice) {
            this.productOriginalPrice.innerText = originalPrice;
        }
    }

    protected set newDetailPageUrl(detailPageUrl: string) {
        if (this.productLinkDetailPage) {
            this.productLinkDetailPage.forEach((element: HTMLAnchorElement) => element.href = detailPageUrl);
        }
    }

    protected set newAddToCartUrl(addToCartUrl: string) {
        if (this.productLinkAddToCart) {
            this.productLinkAddToCart.href = addToCartUrl;
        }

        this.dispatchCustomEvent(EVENT_UPDATE_ADD_TO_CART_URL, {sku: addToCartUrl.split('/').pop()});
    }

    protected createProductLabelFlagClones(index: number): void {
        if (index < 1) {
            return;
        }

        const cloneLabel = this.productLabelFlags[0].cloneNode(true);
        this.productLabelFlags[0].parentNode.insertBefore(cloneLabel, this.productLabelFlags[0].nextSibling);
        this.productLabelFlags = <HTMLElement[]>Array.from(this.getElementsByClassName(`${this.jsName}__label-flag`));
    }

    protected deleteProductLabelFlagClones(labels: ProductItemLabelsData[]): void {
        while (this.productLabelFlags.length > labels.length) {
            this.productLabelFlags[this.productLabelFlags.length - 1].remove();
            this.productLabelFlags = <HTMLElement[]>Array.from(
                this.getElementsByClassName(`${this.jsName}__label-flag`)
            );
        }
    }

    protected deleteProductLabelFlagModifiers(index: number): void {
        this.productLabelFlags[index].classList.forEach((element: string) => {
            if (element.includes('--')) {
                this.productLabelFlags[index].classList.remove(element);
            }
        });
    }

    protected updateProductLabelFlags(element: ProductItemLabelsData, index: number): void {
        const labelClassName: string = this.productLabelFlags[index].getAttribute('data-config-name');
        const labelTextContent = <HTMLElement>this.productLabelFlags[index].getElementsByClassName(`${this.jsName}__label-flag-text`)[0];

        if (element.type) {
            this.productLabelFlags[index].classList.add(`${labelClassName}--${element.type}`);
        }

        this.productLabelFlags[index].classList.remove(this.classToToggle);
        labelTextContent.innerText = element.text;
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

    protected get classToToggle(): string {
        return this.getAttribute('class-to-toggle');
    }
}

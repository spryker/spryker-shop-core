/* tslint:disable: max-file-line-count */
import Component from '../../../models/component';

/**
 * @event updateRating An event emitted when the product rating has been updated.
 * @event updateAddToCartUrl An event emitted when the product 'add to cart' URL has been updated.
 */
export const EVENT_UPDATE_RATING = 'updateRating';
export const EVENT_UPDATE_ADD_TO_CART_URL = 'updateAddToCartUrl';

export interface ProductItemData {
    imageUrl: string;
    imageAlt: string;
    labels: ProductItemLabelsData[];
    nameValue: string;
    ratingValue: number;
    defaultPrice: string;
    originalPrice: string;
    detailPageUrl: string;
    addToCartUrl: string;
}

interface ProductItemLabelsData {
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
        this.imageUrl = data.imageUrl;
        this.imageAlt = data.nameValue;
        this.labels = data.labels;
        this.nameValue = data.nameValue;
        this.ratingValue = data.ratingValue;
        this.defaultPrice = data.defaultPrice;
        this.originalPrice = data.originalPrice;
        this.detailPageUrl = data.detailPageUrl;
        this.addToCartUrl = data.addToCartUrl;
    }

    /**
     * Sets the product card image URL.
     * @param imageUrl A product card image URL.
     */
    set imageUrl(imageUrl: string) {
        if (this.productImage) {
            this.productImage.src = imageUrl;
        }
    }

    /**
     * Sets the product card image alt.
     * @param imageAlt A product card image alt.
     */
    set imageAlt(imageAlt: string) {
        if (this.productImage) {
            this.productImage.alt = imageAlt;
        }
    }

    /**
     * Sets the product card labels.
     * @param labels An array of product card labels.
     */
    set labels(labels: ProductItemLabelsData[]) {
        if (!labels.length) {
            this.productLabelFlags.forEach((element: HTMLElement) => element.classList.add(this.classToToggle));
            this.productLabelTag.classList.add(this.classToToggle);

            return;
        }

        const labelTagType = this.productLabelTag.getAttribute('data-label-tag-type');
        const labelFlags = labels.filter((element: ProductItemLabelsData) => element.type !== labelTagType);
        const labelTag = labels.filter((element: ProductItemLabelsData) => element.type === labelTagType);

        if (!labelTag[0]) {
            this.productLabelTag.classList.add(this.classToToggle);
        }

        if (!labelFlags.length) {
            this.productLabelFlags.forEach((element: HTMLElement) => element.classList.add(this.classToToggle));
        }

        this.updateProductLabels(labelFlags, labelTag[0]);
    }

    /**
     * Sets the product card name.
     * @param name A product card name.
     */
    set nameValue(name: string) {
        if (this.productName) {
            this.productName.innerText = name;
        }
    }

    /**
     * Sets the product card rating.
     * @param rating A product card rating.
     */
    set ratingValue(rating: number) {
        this.dispatchCustomEvent(EVENT_UPDATE_RATING, {rating});
    }

    /**
     * Sets the product card default price.
     * @param defaultPrice A product card default price.
     */
    set defaultPrice(defaultPrice: string) {
        if (this.productDefaultPrice) {
            this.productDefaultPrice.innerText = defaultPrice;
        }
    }

    /**
     * Sets the product card original price.
     * @param originalPrice A product card original price.
     */
    set originalPrice(originalPrice: string) {
        if (this.productOriginalPrice) {
            this.productOriginalPrice.innerText = originalPrice;
        }
    }

    /**
     * Sets the product card detail page URL.
     * @param detailPageUrl A product card detail page URL.
     */
    set detailPageUrl(detailPageUrl: string) {
        if (this.productLinkDetailPage) {
            this.productLinkDetailPage.forEach((element: HTMLAnchorElement) => element.href = detailPageUrl);
        }
    }

    /**
     * Sets the product card 'add to cart' URL.
     * @param addToCartUrl A product card 'add to cart' URL.
     */
    set addToCartUrl(addToCartUrl: string) {
        if (this.productLinkAddToCart) {
            this.productLinkAddToCart.href = addToCartUrl;
        }

        this.dispatchCustomEvent(EVENT_UPDATE_ADD_TO_CART_URL, {sku: addToCartUrl.split('/').pop()});
    }

    protected updateProductLabelTag(element: ProductItemLabelsData): void {
        const labelTagTextContent = <HTMLElement>this.productLabelTag.getElementsByClassName(`${this.jsName}__label-tag-text`)[0];

        this.productLabelTag.classList.remove(this.classToToggle);
        labelTagTextContent.innerText = element.text;
    }

    protected createProductLabelFlagClones(index: number): void {
        if (index < 1) {
            return;
        }

        const cloneLabelFlag = this.productLabelFlags[0].cloneNode(true);
        this.productLabelFlags[0].parentNode.insertBefore(cloneLabelFlag, this.productLabelFlags[0].nextSibling);
        this.productLabelFlags = <HTMLElement[]>Array.from(this.getElementsByClassName(`${this.jsName}__label-flag`));
    }

    protected deleteProductLabelFlagClones(labelFlags: ProductItemLabelsData[]): void {
        while (this.productLabelFlags.length > labelFlags.length) {
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
        const labelFlagClassName: string = this.productLabelFlags[index].getAttribute('data-config-name');
        const labelFlagTextContent = <HTMLElement>this.productLabelFlags[index].getElementsByClassName(`${this.jsName}__label-flag-text`)[0];

        if (element.type) {
            this.productLabelFlags[index].classList.add(`${labelFlagClassName}--${element.type}`);
        }

        this.productLabelFlags[index].classList.remove(this.classToToggle);
        labelFlagTextContent.innerText = element.text;
    }

    protected updateProductLabels(labelFlags: ProductItemLabelsData[], labelTag: ProductItemLabelsData): void {
        if (labelTag) {
            this.updateProductLabelTag(labelTag);
        }

        if (labelFlags.length) {
            labelFlags.forEach((element: ProductItemLabelsData, index: number) => {
                this.createProductLabelFlagClones(index);
                this.deleteProductLabelFlagClones(labelFlags);
                this.deleteProductLabelFlagModifiers(index);
                this.updateProductLabelFlags(element, index);
            });
        }
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

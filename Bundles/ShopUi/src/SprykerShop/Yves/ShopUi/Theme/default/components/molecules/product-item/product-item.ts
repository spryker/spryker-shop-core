/* eslint-disable max-lines */
import Component from '../../../models/component';

/**
 * @event updateRating An event emitted when the product rating has been updated.
 * @event updateLabels An event emitted when the product labels has been updated.
 * @event updateAddToCartUrl An event emitted when the product 'add to cart' URL has been updated.
 * @event updateAjaxAddToCartUrl An event emitted when the product AJAX 'add to cart' URL has been updated.
 * @event updateAddToCartFormAction An event emitted when the product 'add to cart' form action has been updated.
 */
export const EVENT_UPDATE_RATING = 'updateRating';
export const EVENT_UPDATE_LABELS = 'updateLabels';
export const EVENT_UPDATE_ADD_TO_CART_URL = 'updateAddToCartUrl';
export const EVENT_UPDATE_AJAX_ADD_TO_CART_URL = 'updateAjaxAddToCartUrl';
export const EVENT_UPDATE_ADD_TO_CART_FORM_ACTION = 'updateAddToCartFormAction';

export interface ProductItemLabelsData {
    text: string;
    type: string;
}

export interface ProductItemData {
    imageUrl: string;
    imageAlt: string;
    labels: ProductItemLabelsData[];
    name: string;
    ratingValue: number;
    defaultPrice: string;
    sku?: string;
    abstractSku: string;
    originalPrice: string;
    detailPageUrl: string;
    addToCartUrl: string;
    ajaxAddToCartUrl?: string;
    addToCartFormAction?: string;
    price?: number;
}

type Url = string | null;

export default class ProductItem extends Component {
    protected productImage: HTMLImageElement;
    protected productName: HTMLElement;
    protected productRating: HTMLInputElement;
    protected productDefaultPrice: HTMLElement;
    protected productSku: HTMLMetaElement;
    protected productAbstractSku: HTMLMetaElement;
    protected productOriginalPrice: HTMLElement;
    protected productLinkDetailPage: HTMLAnchorElement[];
    protected productLinkAddToCart: HTMLAnchorElement;
    protected productAjaxButtonAddToCart: HTMLButtonElement;
    protected productFormAddToCart: HTMLFormElement;
    protected productButtonAddToCart: HTMLButtonElement;

    protected readyCallback(): void {}

    protected init(): void {
        this.productImage = <HTMLImageElement>this.getElementsByClassName(`${this.jsName}__image`)[0];
        this.productName = <HTMLElement>this.getElementsByClassName(`${this.jsName}__name`)[0];
        this.productRating = <HTMLInputElement>this.getElementsByClassName(`${this.jsName}__rating`)[0];
        this.productDefaultPrice = <HTMLElement>this.getElementsByClassName(`${this.jsName}__default-price`)[0];
        this.productSku = <HTMLMetaElement>this.getElementsByClassName(`${this.jsName}__sku`)[0];
        this.productAbstractSku = <HTMLMetaElement>this.getElementsByClassName(`${this.jsName}__abstract-sku`)[0];
        this.productOriginalPrice = <HTMLElement>this.getElementsByClassName(`${this.jsName}__original-price`)[0];
        this.productLinkDetailPage = <HTMLAnchorElement[]>(
            Array.from(this.getElementsByClassName(`${this.jsName}__link-detail-page`))
        );
        this.productLinkAddToCart = <HTMLAnchorElement>(
            this.getElementsByClassName(`${this.jsName}__link-add-to-cart`)[0]
        );
        this.productAjaxButtonAddToCart = <HTMLButtonElement>(
            this.getElementsByClassName(`${this.jsName}__ajax-button-add-to-cart`)[0]
        );
        this.productFormAddToCart = <HTMLFormElement>this.getElementsByClassName(`${this.jsName}__form-add-to-cart`)[0];
        this.productButtonAddToCart = <HTMLButtonElement>(
            this.getElementsByClassName(`${this.jsName}__button-add-to-cart`)[0]
        );
    }

    /**
     * Sets the product card information.
     * @param data A data object for setting the product card information.
     */
    updateProductItemData(data: ProductItemData): void {
        this.imageUrl = data.imageUrl;
        this.imageAlt = data.imageAlt;
        this.labels = data.labels;
        this.productItemName = data.name;
        this.ratingValue = data.ratingValue;
        this.defaultPrice = data.defaultPrice;
        this.sku = data.sku ?? null;
        this.abstractSku = data.abstractSku;
        this.originalPrice = data.originalPrice;
        this.detailPageUrl = this.generatePDPUrl(data.detailPageUrl);
        this.addToCartUrl = data.addToCartUrl;
        this.ajaxAddToCartUrl = data.ajaxAddToCartUrl ?? null;
        this.addToCartFormAction = data.addToCartFormAction ?? null;
        this.updateDetails(data);
    }

    protected getSkuFromUrl(url: Url): Url {
        if (!url) {
            return null;
        }

        const lastPartOfUrl = new RegExp(`([^\\/])+$`, 'g');
        const sku = url.match(lastPartOfUrl);

        return sku[0];
    }

    protected generatePDPUrl(url: string): string {
        const fullUrl = new URL(url, window.location.origin);

        if (this.details.searchId) {
            fullUrl.searchParams.set('search-id', this.details.searchId);
        }

        return `${fullUrl.pathname}?${fullUrl.searchParams.toString()}`;
    }

    protected updateDetails(data: ProductItemData): void {
        const details = { ...this.details };

        for (const key in data) {
            if (details[key] === undefined || details[key] === null) {
                continue;
            }

            details[key] = data[key];
        }

        this.setAttribute('details', JSON.stringify(details));
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
        this.dispatchCustomEvent(EVENT_UPDATE_LABELS, { labels });
    }

    /**
     * Gets the product card name.
     */
    get productItemName(): string {
        if (this.productName) {
            return this.productName.innerText;
        }
    }

    /**
     * Sets the product card name.
     * @param name A product card name.
     */
    set productItemName(name: string) {
        if (this.productName) {
            this.productName.innerText = name;
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
     * Sets the product card rating.
     * @param rating A product card rating.
     */
    set ratingValue(rating: number) {
        this.dispatchCustomEvent(EVENT_UPDATE_RATING, { rating });
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
     * Sets the product card default price.
     * @param defaultPrice A product card default price.
     */
    set defaultPrice(defaultPrice: string) {
        if (this.productDefaultPrice) {
            this.productDefaultPrice.innerText = defaultPrice;
        }
    }

    /**
     * Gets the product card sku.
     */
    get sku(): string {
        if (this.productSku) {
            return this.productSku.content;
        }
    }

    /**
     * Sets the product card sku.
     * @param productSku A product card sku.
     */
    set sku(productSku: string) {
        if (this.productSku) {
            this.productSku.content = productSku;
        }
    }

    /**
     * Gets the product card abstract sku.
     */
    get abstractSku(): string {
        if (this.productAbstractSku) {
            return this.productAbstractSku.content;
        }
    }

    /**
     * Sets the product card abstract sku.
     * @param productAbstractSku A product card abstract sku.
     */
    set abstractSku(productAbstractSku: string) {
        if (this.productAbstractSku) {
            this.productAbstractSku.content = productAbstractSku;
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
     * Sets the product card original price.
     * @param originalPrice A product card original price.
     */
    set originalPrice(originalPrice: string) {
        if (this.productOriginalPrice) {
            this.productOriginalPrice.innerText = originalPrice;
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
     * Sets the product card detail page URL.
     * @param detailPageUrl A product card detail page URL.
     */
    set detailPageUrl(detailPageUrl: string) {
        if (this.productLinkDetailPage) {
            this.productLinkDetailPage.forEach((element: HTMLAnchorElement) => (element.href = detailPageUrl));
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

    /**
     * Sets the product card 'add to cart' URL.
     * @param addToCartUrl A product card 'add to cart' URL.
     */
    set addToCartUrl(addToCartUrl: string) {
        if (this.productLinkAddToCart) {
            this.productLinkAddToCart.href = addToCartUrl;
        }

        this.dispatchCustomEvent(EVENT_UPDATE_ADD_TO_CART_URL, { sku: this.getSkuFromUrl(addToCartUrl) });
    }

    /**
     * Gets the product card AJAX 'add to cart' URL.
     */
    get ajaxAddToCartUrl(): string {
        if (this.productAjaxButtonAddToCart) {
            return this.productAjaxButtonAddToCart.dataset.url;
        }
    }

    /**
     * Sets the product card AJAX 'add to cart' URL.
     * @param ajaxAddToCartUrl A product card AJAX 'add to cart' URL.
     */
    set ajaxAddToCartUrl(ajaxAddToCartUrl: Url) {
        if (this.productAjaxButtonAddToCart) {
            this.productAjaxButtonAddToCart.disabled = !ajaxAddToCartUrl;
            this.productAjaxButtonAddToCart.dataset.url = ajaxAddToCartUrl;
        }

        this.dispatchCustomEvent(EVENT_UPDATE_AJAX_ADD_TO_CART_URL, { sku: this.getSkuFromUrl(ajaxAddToCartUrl) });
    }

    /**
     * Gets the product card 'add to cart' form action.
     */
    get addToCartFormAction(): string {
        if (this.productFormAddToCart) {
            return this.productFormAddToCart.action;
        }

        if (this.productButtonAddToCart) {
            return this.productButtonAddToCart.dataset.formAction;
        }
    }

    /**
     * Sets the product card 'add to cart' form action.
     * @param addToCartFormAction A product card 'add to cart' form action.
     */
    set addToCartFormAction(addToCartFormAction: Url) {
        if (this.productFormAddToCart) {
            this.productFormAddToCart.action = addToCartFormAction;
        }

        if (this.productButtonAddToCart) {
            this.productButtonAddToCart.dataset.formAction = addToCartFormAction;
        }

        this.dispatchCustomEvent(EVENT_UPDATE_ADD_TO_CART_FORM_ACTION, {
            sku: this.getSkuFromUrl(addToCartFormAction),
        });
    }

    get details(): Record<string, string> {
        return JSON.parse(this.getAttribute('details') ?? '{}');
    }
}

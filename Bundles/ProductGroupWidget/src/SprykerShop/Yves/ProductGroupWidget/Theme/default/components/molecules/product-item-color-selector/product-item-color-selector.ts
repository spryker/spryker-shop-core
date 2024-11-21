import ProductItem, { ProductItemData } from 'ShopUi/components/molecules/product-item/product-item';
import ColorSelector from '../color-selector/color-selector';

export default class ProductItemColorSelector extends ColorSelector {
    protected productItemData: ProductItemData;
    protected productItem: ProductItem;

    protected init(): void {
        if (this.productItemClassName) {
            this.productItem = <ProductItem>this.closest(`.${this.productItemClassName}`);
        }

        super.init();
    }

    protected onTriggerSelection(event: Event): void {
        super.onTriggerSelection(event);
        this.getProductItemData();
        this.productItem.updateProductItemData(this.productItemData);
    }

    protected getProductItemData(): void {
        this.productItemData = {
            imageUrl: this.imageUrl,
            imageAlt: this.productImageAlt,
            labels: this.labels ? JSON.parse(this.labels) : [],
            name: this.productItemName,
            ratingValue: this.ratingValue,
            defaultPrice: this.defaultPrice,
            originalPrice: this.originalPrice,
            detailPageUrl: this.detailPageUrl,
            addToCartUrl: this.addToCartUrl,
            ajaxAddToCartUrl: this.ajaxAddToCartUrl,
            addToCartFormAction: this.addToCartFormAction,
            sku: this.sku,
            price: this.price,
        };
    }

    protected get imageUrl(): string {
        return this.currentSelection.getAttribute('data-product-image-src');
    }

    protected get labels(): string {
        return this.currentSelection.getAttribute('data-product-labels');
    }

    protected get productItemName(): string {
        return this.currentSelection.getAttribute('data-product-name');
    }

    protected get ratingValue(): number {
        return Number(this.currentSelection.getAttribute('data-product-rating'));
    }

    protected get defaultPrice(): string {
        return this.currentSelection.getAttribute('data-product-default-price');
    }

    protected get originalPrice(): string {
        return this.currentSelection.getAttribute('data-product-original-price');
    }

    protected get detailPageUrl(): string {
        return this.currentSelection.getAttribute('data-product-detail-page-url');
    }

    protected get addToCartUrl(): string {
        return this.currentSelection.getAttribute('data-product-add-to-cart-url');
    }

    protected get ajaxAddToCartUrl(): string {
        return this.currentSelection.getAttribute('data-product-ajax-add-to-cart-url');
    }

    protected get addToCartFormAction(): string {
        return this.currentSelection.getAttribute('data-product-add-to-cart-form-action');
    }

    protected get sku(): string {
        return this.currentSelection.getAttribute('data-product-sku');
    }

    protected get productItemClassName(): string {
        return this.getAttribute('product-item-class-name');
    }

    protected get productImageAlt(): string {
        return this.currentSelection.getAttribute('data-product-image-alt');
    }

    protected get price(): number {
        return Number(this.currentSelection.getAttribute('data-product-price'));
    }
}

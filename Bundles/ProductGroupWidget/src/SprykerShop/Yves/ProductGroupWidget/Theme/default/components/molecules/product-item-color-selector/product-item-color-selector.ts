import ColorSelector from '../color-selector/color-selector';
import ProductItem, { ProductItemData } from 'ShopUi/components/molecules/product-item/product-item';

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
            nameValue: this.nameValue,
            ratingValue: this.ratingValue,
            defaultPrice: this.defaultPrice,
            originalPrice: this.originalPrice,
            detailPageUrl: this.detailPageUrl,
            addToCartUrl: this.addToCartUrl
        }
    }

    protected get imageUrl(): string {
        return this.currentSelection.getAttribute('data-product-image-src');
    }

    protected get nameValue(): string {
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

    protected get productItemClassName(): string {
        return this.getAttribute('product-item-class-name');
    }
}

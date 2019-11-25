import ColorSelector from '../color-selector/color-selector';
import ProductItem, { ProductItemData } from 'ShopUi/components/molecules/product-item/product-item';

export default class ProductItemColorSelector extends ColorSelector {
    protected productItemData: ProductItemData;
    protected currentSelection: HTMLButtonElement;

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
}

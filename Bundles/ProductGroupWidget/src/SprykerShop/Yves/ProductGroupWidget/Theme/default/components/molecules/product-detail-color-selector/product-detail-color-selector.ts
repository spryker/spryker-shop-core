import ColorSelector from '../color-selector/color-selector';
import ImageCarousel from 'ShopUi/components/molecules/image-carousel/image-carousel';

export default class ProductDetailColorSelector extends ColorSelector {
    protected imageCarousel: ImageCarousel;
    protected productSku: HTMLElement;
    protected productSkuValueDefault: string;

    protected init(): void {
        super.init();

        this.imageCarousel = <ImageCarousel>document.getElementsByClassName(this.imageCarouselClassName)[0];
        this.productSku = <HTMLInputElement>document.getElementsByClassName(`${this.jsName}__sku`)[0];
        this.productSkuValueDefault = '';

        if (this.productSku) {
            this.productSkuValueDefault = this.productSku.content ?? '';
        }
    }

    protected mapEvents(): void {
        super.mapEvents();
        this.mapTriggerMouseleaveEvent();
    }

    protected mapTriggerMouseleaveEvent() {
        this.triggers.forEach((element: HTMLElement) => {
            element.addEventListener('mouseleave', () => this.onTriggerUnselection());
        });
    }

    protected onTriggerSelection(event: Event): void {
        super.onTriggerSelection(event);
        this.imageCarousel.slideImageUrl = this.imageUrl;
        this.productSku.content = this.sku;
    }

    protected onTriggerUnselection(): void {
        const firstTriggerElement = <HTMLElement>this.triggers[0];

        this.resetActiveItemSelections();
        this.setActiveItemSelection(firstTriggerElement);
        this.imageCarousel.restoreDefaultImageUrl();
        this.restoreDefaultSku();
    }

    protected get imageUrl(): string {
        return this.currentSelection.getAttribute('data-product-image-src');
    }

    protected get sku(): string {
        return this.currentSelection.getAttribute('data-product-sku');
    }

    protected get imageCarouselClassName(): string {
        return this.getAttribute('image-carousel-class-name');
    }

    protected restoreDefaultSku(): string {
        this.productSku.content = this.productSkuValueDefault;
    }
}

import ColorSelector from '../color-selector/color-selector';
import ImageCarousel from 'ShopUi/components/molecules/image-carousel/image-carousel';

export default class ProductDetailColorSelector extends ColorSelector {
    protected imageCarousel: ImageCarousel;

    protected init(): void {
        super.init();

        this.imageCarousel = <ImageCarousel>document.getElementsByClassName(this.imageCarouselClassName)[0];
    }

    protected mapEvents(): void {
        super.mapEvents();

        this.triggers.forEach((element: HTMLElement) => {
            element.addEventListener('mouseleave', () => this.onTriggerUnselection());
        });
    }

    protected onTriggerSelection(event: Event): void {
        super.onTriggerSelection(event);
        this.imageCarousel.setNewImageUrl(this.imageUrl);
    }

    protected onTriggerUnselection(): void {
        const firstTriggerElement = <HTMLElement>this.triggers[0];

        this.resetActiveItemSelections();
        this.setActiveItemSelection(firstTriggerElement);
        this.imageCarousel.restoreDefaultImageUrl();
    }

    protected get imageUrl(): string {
        return this.currentSelection.getAttribute('data-product-image-src');
    }

    protected get imageCarouselClassName(): string {
        return this.getAttribute('image-carousel-class-name');
    }
}

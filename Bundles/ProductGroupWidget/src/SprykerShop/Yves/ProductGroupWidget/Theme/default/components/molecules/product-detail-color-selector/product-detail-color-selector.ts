import ColorSelector from '../color-selector/color-selector';
import SimpleCarousel from 'ShopUi/components/molecules/simple-carousel/simple-carousel';

export default class ProductDetailColorSelector extends ColorSelector {
    protected simpleCarousel: SimpleCarousel;

    protected init(): void {
        super.init();

        this.simpleCarousel = <SimpleCarousel>document.getElementsByClassName(this.simpleCarouselClassName)[0];
    }

    protected mapEvents(): void {
        super.mapEvents();

        this.triggers.forEach((element: HTMLElement) => {
            element.addEventListener('mouseleave', () => this.onTriggerUnselection());
        });
    }

    protected onTriggerSelection(event: Event): void {
        super.onTriggerSelection(event);
        this.simpleCarousel.setNewImageUrl(this.imageUrl);
    }

    protected onTriggerUnselection(): void {
        const firstTriggerElement = <HTMLElement>this.triggers[0];

        this.setActiveItemSelection(firstTriggerElement);
        this.simpleCarousel.restoreDefaultImageUrl();
    }

    protected get imageUrl(): string {
        return this.currentSelection.getAttribute('data-product-image-src');
    }

    protected get simpleCarouselClassName(): string {
        return this.getAttribute('simple-carousel-class-name');
    }
}

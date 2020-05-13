import SimpleCarousel from '../simple-carousel/simple-carousel';

export default class ImageCarousel extends SimpleCarousel {
    protected defaultImageUrl: string;
    protected currentSlideImage: HTMLImageElement;

    protected readyCallback(): void {
        this.getCurrentSlideImage();
        this.setDefaultImageUrl();
        super.readyCallback();
    }

    /**
     * Performs sliding of slider items.
     */
    slide(): void {
        super.slide();
        this.getCurrentSlideImage();
        this.setDefaultImageUrl();
    }

    /**
     * Sets the new slide image with a new URL.
     * @param url An image URL.
     */
    set slideImageUrl(url: string) {
        this.currentSlideImage.src = url;
    }

    /**
     * Sets the slide image with a default URL.
     */
    restoreDefaultImageUrl(): void {
        this.currentSlideImage.src = this.defaultImageUrl;
    }

    protected getCurrentSlideImage(): void {
        const currentSlide = <HTMLElement>this.getElementsByClassName(`${this.jsName}__slide`)[this.viewCurrentIndex];

        this.currentSlideImage = currentSlide.getElementsByTagName('img')[0];
    }

    protected setDefaultImageUrl(): void {
        this.defaultImageUrl = this.currentSlideImage.src;
    }
}

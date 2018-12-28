import Component from '../../../models/component';

export default class SimpleCarousel extends Component {
    triggerPrev: HTMLElement
    triggerNext: HTMLElement
    slider: HTMLElement
    slidesCount: number
    slideWidth: number
    dots: HTMLElement[]
    viewsCount: number
    viewCurrentIndex: number = 0

    readonly dotSelector: string
    readonly dotCurrentModifier: string

    constructor() {
        super();

        this.dotSelector = `${this.jsName}__dot`;
        this.dotCurrentModifier = `${this.name}__dot--current`;
    }

    protected readyCallback(): void {
        this.slidesCount = this.getElementsByClassName(`${this.jsName}__slide`).length;

        if (this.slidesCount <= 1) {
            return;
        }

        this.triggerPrev = this.querySelector(`.${this.jsName}__prev`);
        this.triggerNext = this.querySelector(`.${this.jsName}__next`);
        this.slider = this.querySelector(`.${this.jsName}__slider`);
        this.slideWidth = 100 / this.slidesToShow;
        this.dots = <HTMLElement[]>Array.from(this.getElementsByClassName(this.dotSelector));
        this.viewsCount = this.getViewsCount();

        this.mapEvents();
    }

    getViewsCount(): number {
        return Math.ceil((this.slidesCount - this.slidesToShow) / this.slidesToScroll) + 1;
    }

    protected mapEvents(): void {
        this.triggerPrev.addEventListener('click', (event: Event) => this.onPrevClick(event));
        this.triggerNext.addEventListener('click', (event: Event) => this.onNextClick(event));
        this.dots.forEach((dot: HTMLElement) => dot.addEventListener('click', (event: Event) => this.onDotClick(event)));
    }

    protected onPrevClick(event: Event): void {
        event.preventDefault();
        this.loadPrevViewIndex();
        this.slide();
        this.updateCurrentDot();
    }

    protected onNextClick(event: Event): void {
        event.preventDefault();
        this.loadNextViewIndex();
        this.slide();
        this.updateCurrentDot();
    }

    protected onDotClick(event: Event): void {
        event.preventDefault();
        this.loadViewIndexFromDot(<HTMLElement>event.srcElement)
        this.slide();
        this.updateCurrentDot();
    }

    /**
     * Performs index setting of the previous slide
     */
    loadPrevViewIndex(): void {
        this.viewCurrentIndex = this.viewCurrentIndex - 1;

        if (this.viewCurrentIndex < 0) {
            this.viewCurrentIndex = this.viewsCount - 1;
        }
    }

    /**
     * Performs index setting of the next slide
     */
    loadNextViewIndex(): void {
        this.viewCurrentIndex = this.viewCurrentIndex + 1;

        if (this.viewCurrentIndex >= this.viewsCount) {
            this.viewCurrentIndex = 0;
        }
    }

    /**
     * Performs index setting of the active slide
     * @param dot HTMLElement for reading index of the active slide
     */
    loadViewIndexFromDot(dot: HTMLElement): void {
        this.viewCurrentIndex = this.dots.indexOf(dot);

        if (this.viewCurrentIndex === -1) {
            this.viewCurrentIndex = 0;
        }
    }

    /**
     * Performs sliding of slider items
     */
    slide(): void {
        let slidesToSlide = this.slidesToScroll * this.viewCurrentIndex;

        if (this.slidesToScroll + slidesToSlide > this.slidesCount) {
            slidesToSlide = slidesToSlide - (this.slidesCount - slidesToSlide);
        }

        const offset = - (slidesToSlide * this.slideWidth);
        this.slider.style.transform = `translateX(${offset}%)`;
    }

    /**
     * Performs toggling the active class and modifier on the current dot
     */
    updateCurrentDot(): void {
        if (this.dots.length === 0) {
            return;
        }

        this
            .querySelector(`.${this.dotSelector}.${this.dotCurrentModifier}`)
            .classList
            .remove(this.dotCurrentModifier);

        this
            .dots[this.viewCurrentIndex]
            .classList
            .add(this.dotCurrentModifier);
    }

    /**
     * Gets a number of slides which will showing at the same time
     */
    get slidesToShow(): number {
        return parseInt(this.getAttribute('slides-to-show') || '0');
    }

    /**
     * Gets a number of slides which will to scroll by one iteration
     */
    get slidesToScroll(): number {
        return parseInt(this.getAttribute('slides-to-scroll') || '0');
    }
}

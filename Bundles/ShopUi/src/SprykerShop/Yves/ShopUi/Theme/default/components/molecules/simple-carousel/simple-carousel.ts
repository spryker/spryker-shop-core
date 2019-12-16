import Component from '../../../models/component';

export default class SimpleCarousel extends Component {
    /**
     * Switches a slide to a previous one.
     */
    triggerPrev: HTMLElement;

    /**
     * Switches a slide to a next one.
     */
    triggerNext: HTMLElement;

    /**
     * The current slider.
     */
    slider: HTMLElement;

    /**
     * The number of the slides.
     */
    slidesCount: number;

    /**
     * The slider width.
     */
    slideWidth: number;

    /**
     * Thr dot-switch elements below the slides.
     */
    dots: HTMLElement[];

    /**
     * The number of views.
     */
    viewsCount: number;

    /**
     * The index of the active slide.
     */
    viewCurrentIndex: number = 0;

    /**
     * Dot element selector.
     */
    readonly dotSelector: string;

    /**
     * Dot element "is current" modifier.
     */
    readonly dotCurrentModifier: string;
    protected fullSliderWidth: number = 100;

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

        this.triggerPrev = <HTMLElement>this.getElementsByClassName(`${this.jsName}__prev`)[0];
        this.triggerNext = <HTMLElement>this.getElementsByClassName(`${this.jsName}__next`)[0];
        this.slider = <HTMLElement>this.getElementsByClassName(`${this.jsName}__slider`)[0];
        this.slideWidth = this.fullSliderWidth / this.slidesToShow;
        this.dots = <HTMLElement[]>Array.from(this.getElementsByClassName(this.dotSelector));
        this.viewsCount = this.getViewsCount();

        this.mapEvents();
    }

    /**
     * Gets the number of slides.
     */
    getViewsCount(): number {
        return Math.ceil((this.slidesCount - this.slidesToShow) / this.slidesToScroll) + 1;
    }

    protected mapEvents(): void {
        this.triggerPrev.addEventListener('click', (event: Event) => this.onPrevClick(event));
        this.triggerNext.addEventListener('click', (event: Event) => this.onNextClick(event));
        this.dots.forEach((dot: HTMLElement) => {
            dot.addEventListener('click', (event: Event) => this.onDotClick(event));
        });
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
        this.loadViewIndexFromDot(<HTMLElement>event.target);
        this.slide();
        this.updateCurrentDot();
    }

    /**
     * Switches the current slide to the previous one.
     */
    loadPrevViewIndex(): void {
        this.viewCurrentIndex = this.viewCurrentIndex - 1;

        if (this.viewCurrentIndex < 0) {
            this.viewCurrentIndex = this.viewsCount - 1;
        }
    }

    /**
     * Switches the current slide to the next one.
     */
    loadNextViewIndex(): void {
        this.viewCurrentIndex = this.viewCurrentIndex + 1;

        if (this.viewCurrentIndex >= this.viewsCount) {
            this.viewCurrentIndex = 0;
        }
    }

    /**
     * Switches to the slide based on the provided dot element.
     * @param dot HTMLElement corresponding to the new target slide that has to be loaded.
     */
    loadViewIndexFromDot(dot: HTMLElement): void {
        this.viewCurrentIndex = this.dots.indexOf(dot);

        if (this.viewCurrentIndex === -1) {
            this.viewCurrentIndex = 0;
        }
    }

    /**
     * Performs sliding of slider items.
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
     * Toggles the active class and the modifier on the current dot.
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
     * Gets the number of slides to be shown.
     */
    get slidesToShow(): number {
        return parseInt(this.getAttribute('slides-to-show') || '0');
    }

    /**
     * Gets the number of slides to be scrolled by an interaction.
     */
    get slidesToScroll(): number {
        return parseInt(this.getAttribute('slides-to-scroll') || '0');
    }
}

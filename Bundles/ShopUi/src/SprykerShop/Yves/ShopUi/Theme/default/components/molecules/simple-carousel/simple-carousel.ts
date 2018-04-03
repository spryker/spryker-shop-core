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

        this.dotSelector = `${this.componentSelector}__dot`;
        this.dotCurrentModifier = `${this.componentName}__dot--current`;
    }

    readyCallback(): void {
        this.slidesCount = this.getElementsByClassName(`${this.componentSelector}__slide`).length;

        if (this.slidesCount <= 1) {
            return;
        }

        this.triggerPrev = this.querySelector(`.${this.componentSelector}__prev`);
        this.triggerNext = this.querySelector(`.${this.componentSelector}__next`);
        this.slider = this.querySelector(`.${this.componentSelector}__slider`);
        this.slideWidth = 100 / this.slidesToShow;
        this.dots = <HTMLElement[]>Array.from(this.getElementsByClassName(this.dotSelector));
        this.viewsCount = this.getViewsCount();
        
        this.mapEvents();
    }

    getViewsCount(): number { 
        return Math.ceil((this.slidesCount - this.slidesToShow) / this.slidesToScroll) + 1;
    }

    mapEvents(): void {
        this.triggerPrev.addEventListener('click', (event: Event) => this.onPrevClick(event));
        this.triggerNext.addEventListener('click', (event: Event) => this.onNextClick(event));
        this.dots.forEach((dot: HTMLElement) => dot.addEventListener('click', (event: Event) => this.ondotClick(event)));
    }

    onPrevClick(event: Event): void {
        event.preventDefault();
        this.loadPrevViewIndex();
        this.slide();
        this.updateCurrentDot();
    }

    onNextClick(event: Event): void { 
        event.preventDefault();
        this.loadNextViewIndex();
        this.slide();
        this.updateCurrentDot();
    }

    ondotClick(event: Event): void {
        event.preventDefault();
        this.loadViewIndexFromDot(<HTMLElement>event.srcElement)
        this.slide();
        this.updateCurrentDot();
    }

    loadPrevViewIndex(): void { 
        this.viewCurrentIndex = this.viewCurrentIndex - 1;

        if (this.viewCurrentIndex < 0) {
            this.viewCurrentIndex = this.viewsCount - 1;
        }
    }

    loadNextViewIndex(): void {
        this.viewCurrentIndex = this.viewCurrentIndex + 1;

        if (this.viewCurrentIndex >= this.viewsCount) {
            this.viewCurrentIndex = 0;
        }
    }

    loadViewIndexFromDot(dot: HTMLElement): void { 
        this.viewCurrentIndex = this.dots.indexOf(dot);

        if (this.viewCurrentIndex === -1) {
            this.viewCurrentIndex = 0;
        }
    }

    slide(): void {
        let slidesToSlide = this.slidesToScroll * this.viewCurrentIndex;

        if (this.slidesToScroll + slidesToSlide > this.slidesCount) { 
            slidesToSlide = slidesToSlide - (this.slidesCount - slidesToSlide);
        }

        const offset = - (slidesToSlide * this.slideWidth);
        this.slider.style.transform = `translateX(${offset}%)`;
    }

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

    set slidesToShow(value: number) {
        this.setAttributeSafe('slides-to-show', `${value}`);
    }

    get slidesToShow(): number {
        return parseInt(this.getAttributeSafe('slides-to-show' || '0'));
    }

    set slidesToScroll(value: number) {
        this.setAttributeSafe('slides-to-scroll', `${value}`);
    }

    get slidesToScroll(): number {
        return parseInt(this.getAttributeSafe('slides-to-scroll' || '0'));
    }
}

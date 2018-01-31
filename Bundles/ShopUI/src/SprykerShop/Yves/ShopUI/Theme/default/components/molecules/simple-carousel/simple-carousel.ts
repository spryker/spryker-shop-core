import Component from '../../../models/component';

export default class SimpleCarousel extends Component {
    grid: HTMLElement
    prevTrigger: HTMLElement
    nextTrigger: HTMLElement
    slidesCount: number
    steps: number[]
    stepIndex: number

    ready(): void {
        this.slidesCount = this.getElementsByClassName(`${this.selector}__slide`).length;

        if (this.slidesCount <= 1) { 
            return;
        }

        this.grid = this.querySelector(`.${this.selector}__grid`);
        this.prevTrigger = this.querySelector(`.${this.selector}__prev`);
        this.nextTrigger = this.querySelector(`.${this.selector}__next`);
        this.steps = this.getSteps();
        this.stepIndex = 0;
        this.mapEvents();
    }

    getSteps(): number[] { 
        const stepWidth: number = (100 / this.slidesToDisplay);
        const gridWidth: number = (stepWidth * this.slidesCount);
        const steps: number = (gridWidth - 100) / stepWidth;
        return new Array(1 + steps).fill(stepWidth).map((width: number, index: number) => - width * index);
    }

    mapEvents(): void {
        this.prevTrigger.addEventListener('click', (event: Event) => this.onPrevClick(event));
        this.nextTrigger.addEventListener('click', (event: Event) => this.onNextClick(event));
    }

    onPrevClick(event: Event) {
        event.preventDefault();
        this.prev();
    }

    onNextClick(event: Event) { 
        event.preventDefault();
        this.next();
    }

    prev() {
        this.stepIndex = this.stepIndex - 1;

        if (this.stepIndex < 0) {
            this.stepIndex = this.steps.length - 1;
        }

        this.grid.style.transform = `translateX(${this.steps[this.stepIndex]}%)`;
    }

    next() { 
        this.stepIndex = this.stepIndex + 1;

        if (this.stepIndex >= this.steps.length) {
            this.stepIndex = 0;
        }

        this.grid.style.transform = `translateX(${this.steps[this.stepIndex]}%)`;
    }

    set slidesToDisplay(value: number) {
        this.setAttributeSafe('slides-to-display', `${value}`);
    }

    get slidesToDisplay(): number {
        return parseInt(this.getAttributeSafe('slides-to-display' || '0'));
    }
}

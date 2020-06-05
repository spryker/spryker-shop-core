import Component from '../../../models/component';
import { EVENT_ELEMENT_IN_VIEWPORT } from '../viewport-intersection-observer/viewport-intersection-observer';

export default class LazyImage extends Component {
    protected image: HTMLImageElement;
    protected background: HTMLElement;

    protected readyCallback(): void {}

    protected init(): void {
        this.image = <HTMLImageElement>this.getElementsByClassName(`${this.jsName}__image`)[0];
        this.background = <HTMLImageElement>this.getElementsByClassName(`${this.jsName}__background`)[0];
        this.mapEvents();
    }

    protected mapEvents(): void {
        this.addEventListener(EVENT_ELEMENT_IN_VIEWPORT, () => {
            if (this.image) {
                this.imageHandler();

                return;
            }
            if (this.background) {
                this.backgroundHandler();
            }
        });
    }

    protected imageHandler(): void {
        this.image.src = this.image.dataset.src;
    }

    protected backgroundHandler(): void {
        this.background.style.backgroundImage = this.background.dataset.backgroundImage;
    }
}

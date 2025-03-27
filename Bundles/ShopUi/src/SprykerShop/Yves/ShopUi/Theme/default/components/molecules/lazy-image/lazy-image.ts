import Component from '../../../models/component';
import ViewportIntersectionObserver, {
    EVENT_ELEMENT_IN_VIEWPORT,
} from '../viewport-intersection-observer/viewport-intersection-observer';

export default class LazyImage extends Component {
    protected image: HTMLImageElement;
    protected background: HTMLElement;
    protected viewportIntersectionObserver: ViewportIntersectionObserver;

    protected readyCallback(): void {}

    protected init(): void {
        this.image = <HTMLImageElement>this.getElementsByClassName(`${this.jsName}__image`)[0];
        this.background = <HTMLElement>this.getElementsByClassName(`${this.jsName}__background`)[0];
        this.viewportIntersectionObserver = <ViewportIntersectionObserver>(
            document.getElementsByClassName('viewport-intersection-observer')[0]
        );
        this.mapEvents();

        if (!this.viewportIntersectionObserver) {
            return;
        }

        this.viewportIntersectionObserver.updateTargets();
    }

    protected mapEvents(): void {
        this.mapTriggerCustomViewportEvent();
    }

    protected mapTriggerCustomViewportEvent(): void {
        this.addEventListener(EVENT_ELEMENT_IN_VIEWPORT, () => {
            if (this.image && this.image.dataset.src) {
                this.imageSrc = this.image.dataset.src;

                return;
            }
            if (this.background && this.background.dataset.backgroundImage) {
                this.backgroundImage = this.background.dataset.backgroundImage;
            }
        });
    }

    protected set imageSrc(src: string) {
        this.image.src = src;
    }

    protected set backgroundImage(image: string) {
        this.background.style.backgroundImage = image;
    }
}

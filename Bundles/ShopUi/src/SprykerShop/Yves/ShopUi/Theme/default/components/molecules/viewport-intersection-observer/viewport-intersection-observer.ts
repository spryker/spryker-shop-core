import Component from '../../../models/component';

/**
 * @event elementInViewport An event emitted when an element in the viewport.
 */
export const EVENT_ELEMENT_IN_VIEWPORT = 'elementInViewport';

interface ViewportOptions {
    root?: HTMLElement;
    rootMargin: string;
    threshold: number;
}

export default class ViewportIntersectionObserver extends Component {
    protected observer: IntersectionObserver;
    protected viewportOptions: ViewportOptions;
    protected targets: HTMLElement[];

    protected readyCallback(): void {}

    protected init(): void {
        this.updateTargets();
    }

    /**
     * Updates targets to follow for the observer.
     * @publicAPI
     */
    updateTargets(): void {
        this.observer = this.observerInit();
        this.viewportOptions = {
            rootMargin: this.viewportMargin,
            threshold: this.visibilityThreshold,
        };

        if (this.viewportClassName) {
            this.viewportOptions.root = <HTMLElement>document.getElementsByClassName(this.viewportClassName)[0];
        }

        this.targets = <HTMLElement[]>Array.from(document.getElementsByClassName(this.targetClassName));
        this.observerSubscriber();
    }

    protected observerInit(): IntersectionObserver {
        return new IntersectionObserver(this.observerCallback(), this.viewportOptions);
    }

    protected observerCallback(): IntersectionObserverCallback {
        return (entries: IntersectionObserverEntry[], observer: IntersectionObserver) => {
            entries.forEach((entry: IntersectionObserverEntry) => {
                if (!entry.intersectionRatio || !entry.isIntersecting) {
                    return;
                }

                const item = entry.target;
                const viewportCustomEvent = new CustomEvent(EVENT_ELEMENT_IN_VIEWPORT);

                if (!this.isObserveVisibleTargets) {
                    observer.unobserve(item);
                }

                item.dispatchEvent(viewportCustomEvent);
            });
        };
    }

    protected observerSubscriber(): void {
        if (!this.targets.length) {
            return;
        }
        this.targets.forEach((item: HTMLElement) => this.observer.observe(item));
    }

    protected get viewportClassName(): string {
        return this.getAttribute('viewport-class-name');
    }

    protected get viewportMargin(): string {
        return this.getAttribute('viewport-margin');
    }

    protected get visibilityThreshold(): number {
        return Number(this.getAttribute('visibility-threshold'));
    }

    protected get isObserveVisibleTargets(): boolean {
        return this.hasAttribute('observe-visible-targets');
    }

    protected get targetClassName(): string {
        return this.getAttribute('target-class-name');
    }
}

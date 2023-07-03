import Component from 'ShopUi/models/component';

export const EVENT_SHOW_OVERLAY = 'showOverlay';
export const EVENT_HIDE_OVERLAY = 'hideOverlay';

export interface OverlayEventDetail {
    id?: string;
    zIndex?: number;
}

export default class MainOverlay extends Component {
    protected triggerQueue: OverlayEventDetail[] = [];
    protected showClassName = `${this.name}--show`;

    protected readyCallback(): void {}

    protected init(): void {
        this.mapEvents();

        if (this.isOpen) {
            this.onShowOverlay();
        }
    }

    protected mapEvents(): void {
        this.mapShowOverlayEvent();
        this.mapHideOverlayEvent();
    }

    protected mapShowOverlayEvent(): void {
        document.addEventListener(EVENT_SHOW_OVERLAY, (event: CustomEvent<OverlayEventDetail>) =>
            this.onShowOverlay(event.detail),
        );
    }

    protected mapHideOverlayEvent(): void {
        document.addEventListener(EVENT_HIDE_OVERLAY, (event: CustomEvent<OverlayEventDetail>) =>
            this.onHideOverlay(event.detail),
        );
    }

    protected onShowOverlay(detail: OverlayEventDetail = {}): void {
        this.classList.add(this.showClassName);
        this.setProperties(detail);
        this.triggerQueue.push(detail);
    }

    protected onHideOverlay(detail: OverlayEventDetail): void {
        const isSingleInQueue = this.triggerQueue.length === 1;

        if (!detail?.id && isSingleInQueue) {
            this.triggerQueue.pop();
            this.hideOverlay();

            return;
        }

        if (!detail?.id) {
            this.triggerQueue.pop();
            this.setPreviousOverlayState(this.triggerQueue[this.triggerQueue.length - 1]);

            return;
        }

        this.triggerQueue = this.triggerQueue.filter((item) => item?.id !== detail.id);

        if (!this.triggerQueue.length) {
            this.hideOverlay();

            return;
        }

        this.setPreviousOverlayState(this.triggerQueue[this.triggerQueue.length - 1]);
    }

    protected hideOverlay(): void {
        this.classList.remove(this.showClassName);
        this.resetProperties();
    }

    protected setPreviousOverlayState(detail: OverlayEventDetail): void {
        this.resetProperties();
        this.setProperties(detail);
    }

    protected setProperties(detail: OverlayEventDetail): void {
        if (detail?.zIndex) {
            this.style.zIndex = String(detail.zIndex);
        }
    }

    protected resetProperties(): void {
        this.style.removeProperty('z-index');
    }

    protected get isOpen(): boolean {
        return this.hasAttribute('is-open');
    }
}

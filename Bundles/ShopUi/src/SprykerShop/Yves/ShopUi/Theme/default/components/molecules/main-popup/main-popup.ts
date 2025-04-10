import Component from '../../../models/component';
import { mount } from '../../../app';
import { EVENT_SHOW_OVERLAY, EVENT_HIDE_OVERLAY, OverlayEventDetail } from '../main-overlay/main-overlay';

export const EVENT_OPEN_POPUP = 'openPopup';
export const EVENT_CLOSE_POPUP = 'closePopup';
export const EVENT_POPUP_OPENED = 'popupOpened';
export const EVENT_POPUP_CLOSED = 'popupClosed';

export default class MainPopup extends Component {
    protected triggers: HTMLElement[];
    protected overlay: HTMLElement;
    protected cloneTarget = document.body;
    protected clone: HTMLElement;
    protected currentPopup: HTMLElement;
    protected isRootPopup: boolean;
    protected isPopupOpened = false;
    protected showClassName = `${this.name}--open`;
    protected eventShowOverlay: CustomEvent<OverlayEventDetail>;
    protected eventHideOverlay: CustomEvent<OverlayEventDetail>;

    protected readyCallback(): void {}

    protected async init(): Promise<void> {
        this.triggers = <HTMLElement[]>Array.from(document.getElementsByClassName(this.triggerClassName));
        this.overlay = <HTMLElement>document.getElementsByClassName(this.overlayClassName)[0];
        this.isRootPopup = this.parentElement === this.cloneTarget;

        await this.mapEvents();

        if (this.isInitiallyOpen) {
            this.togglePopup(true);
        }
    }

    protected async mapEvents(): Promise<void> {
        this.mapOpenPopupEvent();
        this.mapClosePopupEvent();
        this.mapTriggersClickEvent();
        this.mapCloseButtonClickEvent();

        if (this.overlay) {
            await mount();
            this.mapOverlayEvents();
        }
    }

    protected mapOpenPopupEvent(): void {
        this.addEventListener(EVENT_OPEN_POPUP, () => this.onOpenPopup());
    }

    protected mapClosePopupEvent(): void {
        this.addEventListener(EVENT_CLOSE_POPUP, () => this.onClosePopup());
    }

    protected mapTriggersClickEvent(): void {
        this.triggers.forEach((trigger: HTMLElement) => {
            trigger.addEventListener('click', () => this.onTriggerClick());
        });
    }

    protected mapCloseButtonClickEvent(currentPopup: HTMLElement = this): void {
        const closeButtons = currentPopup.querySelectorAll<HTMLButtonElement>(
            `.${this.jsName}__close, ${this.closePopupSelector}`,
        );

        closeButtons.forEach((closeButton: HTMLButtonElement) => {
            closeButton.addEventListener('click', () => this.onCloseButtonClick());
        });
    }

    protected mapOverlayEvents(): void {
        const overlayConfig: CustomEventInit<OverlayEventDetail> = {
            bubbles: true,
            detail: {
                id: this.popupId,
                zIndex: Number(getComputedStyle(this).zIndex) - 1,
            },
        };

        this.eventShowOverlay = new CustomEvent(EVENT_SHOW_OVERLAY, overlayConfig);
        this.eventHideOverlay = new CustomEvent(EVENT_HIDE_OVERLAY, overlayConfig);

        if (this.shouldCloseByOverlayClick) {
            this.mapOverlayClickEvent();
        }
    }

    protected mapOverlayClickEvent(): void {
        this.overlay.addEventListener('click', () => this.onOverlayClick());
    }

    protected onOpenPopup(): void {
        this.togglePopup(true);
    }

    protected onClosePopup(): void {
        this.togglePopup(false);
    }

    protected onTriggerClick(): void {
        this.togglePopup();
    }

    protected onCloseButtonClick(): void {
        this.togglePopup(false);
    }

    protected onOverlayClick(): void {
        this.togglePopup(false);
    }

    protected togglePopup(forcePopup?: boolean): void {
        if (this.isPopupOpened === forcePopup) {
            return;
        }

        if (!this.currentPopup) {
            this.definePopup();
        }

        this.isPopupOpened = this.currentPopup.classList.toggle(this.showClassName, forcePopup);

        if (!this.isRootPopup && this.hasContentReload) {
            this.reloadContent();
        }

        if (this.overlay) {
            this.toggleOverlay();
        }

        if (this.isPopupOpened) {
            this.dispatchCustomEvent(EVENT_POPUP_OPENED);
        }

        if (!this.isPopupOpened) {
            this.dispatchCustomEvent(EVENT_POPUP_CLOSED);
        }
    }

    protected definePopup(): void {
        if (this.isRootPopup) {
            this.currentPopup = this;

            return;
        }

        this.createClone();
        this.currentPopup = this.clone;
    }

    protected createClone(): void {
        this.clone = document.createElement('div');
        this.clone.setAttribute('id', this.popupId);
        this.clone.setAttribute('class', this.getAttribute('class'));

        if (!this.hasContentReload) {
            this.clone.innerHTML = this.innerHTML;
            this.innerHTML = '';
        }

        this.cloneTarget.appendChild(this.clone);

        if (!this.hasContentReload) {
            this.mapCloseButtonClickEvent(this.clone);
        }

        if (this.hasContentMount) {
            mount();
        }
    }

    protected reloadContent(): void {
        if (this.isPopupOpened) {
            this.clone.innerHTML = this.innerHTML;
            this.innerHTML = '';
            this.mapCloseButtonClickEvent(this.clone);
        }

        if (!this.isPopupOpened) {
            this.innerHTML = this.clone.innerHTML;
            this.clone.innerHTML = '';
        }

        if (this.hasContentMount) {
            mount();
        }
    }

    protected replaceContent(html: string): void {
        const popupContent = this.clone.getElementsByClassName(`${this.jsName}__content`)[0];
        popupContent.innerHTML = html;

        mount();
    }

    protected toggleOverlay(): void {
        if (this.isPopupOpened) {
            this.dispatchEvent(this.eventShowOverlay);

            return;
        }

        this.dispatchEvent(this.eventHideOverlay);
    }

    protected get popupId(): string {
        return this.getAttribute('content-id');
    }

    protected get triggerClassName(): string {
        return this.getAttribute('trigger-class-name');
    }

    protected get isInitiallyOpen(): boolean {
        return this.hasAttribute('is-open');
    }

    protected get hasContentMount(): boolean {
        return this.hasAttribute('has-content-mount');
    }

    protected get hasContentReload(): boolean {
        return this.hasAttribute('has-content-reload');
    }

    protected get overlayClassName(): string {
        return this.getAttribute('overlay-class-name');
    }

    protected get shouldCloseByOverlayClick(): boolean {
        return this.hasAttribute('should-close-by-overlay-click');
    }

    protected get closePopupSelector(): string {
        return this.getAttribute('close-popup-selector');
    }
}

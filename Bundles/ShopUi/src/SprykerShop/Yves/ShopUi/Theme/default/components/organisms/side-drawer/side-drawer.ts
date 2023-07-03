import Component from '../../../models/component';
import {
    EVENT_HIDE_OVERLAY,
    EVENT_SHOW_OVERLAY,
    OverlayEventDetail,
} from 'ShopUi/components/molecules/main-overlay/main-overlay';

export default class SideDrawer extends Component {
    /**
     * Collection of the trigger elements.
     */
    triggers: HTMLElement[];
    /**
     * Collection of the container elements.
     */
    containers: HTMLElement[];
    protected eventShowOverlay: CustomEvent<OverlayEventDetail>;
    protected eventHideOverlay: CustomEvent<OverlayEventDetail>;

    protected readyCallback(): void {}

    protected init(): void {
        this.triggers = <HTMLElement[]>Array.from(document.getElementsByClassName(this.triggerSelector));
        this.containers = <HTMLElement[]>Array.from(document.getElementsByClassName(this.containerSelector));

        this.mapEvents();
    }

    protected mapEvents(): void {
        this.mapOverlayEvents();
        this.mapTriggerClickEvent();
    }

    protected mapOverlayEvents(): void {
        this.eventShowOverlay = new CustomEvent(EVENT_SHOW_OVERLAY, {
            bubbles: true,
            detail: { zIndex: Number(getComputedStyle(this).zIndex) - 1 },
        });
        this.eventHideOverlay = new CustomEvent(EVENT_HIDE_OVERLAY, { bubbles: true });
    }

    protected mapTriggerClickEvent(): void {
        this.triggers.forEach((trigger: HTMLElement) => {
            trigger.addEventListener('click', (event: Event) => this.onTriggerClick(event));
        });
    }

    protected onTriggerClick(event: Event): void {
        event.preventDefault();
        this.toggle();
    }

    /**
     * Toggles the visibility of side-drawer and overlay.
     * Toggles the scrollability of containers.
     */
    toggle(): void {
        const isShown = !this.classList.contains(`${this.name}--show`);
        this.classList.toggle(`${this.name}--show`, isShown);
        this.containers.forEach((conatiner: HTMLElement) => conatiner.classList.toggle(`is-not-scrollable`, isShown));
        this.toggleOverlay(isShown);
    }

    protected toggleOverlay(isShown: boolean): void {
        this.dispatchEvent(isShown ? this.eventShowOverlay : this.eventHideOverlay);
    }

    /**
     * Gets a class name of the trigger element.
     */
    get triggerSelector(): string {
        return this.getAttribute('trigger-selector');
    }

    /**
     * Gets the css query selector of the container element.
     */
    get containerSelector(): string {
        return this.getAttribute('container-selector');
    }
}

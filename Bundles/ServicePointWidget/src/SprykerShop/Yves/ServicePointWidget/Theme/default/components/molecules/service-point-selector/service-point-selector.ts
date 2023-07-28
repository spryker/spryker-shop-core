import Component from 'ShopUi/models/component';
import ServicePointFinder, { EVENT_SET_SERVICE_POINT } from '../service-point-finder/service-point-finder';
import MainPopup, { EVENT_CLOSE_POPUP } from '../main-popup/main-popup';
import { mount } from 'ShopUi/app';

export default class ServicePointSelector extends Component {
    protected input: HTMLInputElement;
    protected noLocationContainer: HTMLElement;
    protected location: HTMLElement;
    protected locationContainer: HTMLElement;
    protected triggers: HTMLButtonElement[];
    protected finder: ServicePointFinder;
    protected popup: MainPopup;

    protected readyCallback(): void {}

    protected init(): void {
        this.input = <HTMLInputElement>this.getElementsByClassName(`${this.jsName}__input`)[0];
        this.noLocationContainer = <HTMLElement>this.getElementsByClassName(`${this.jsName}__no-location`)[0];
        this.location = <HTMLElement>this.getElementsByClassName(`${this.jsName}__location`)[0];
        this.locationContainer = <HTMLElement>this.getElementsByClassName(`${this.jsName}__location-container`)[0];
        this.triggers = <HTMLButtonElement[]>Array.from(this.getElementsByClassName(this.triggerClassName));
        this.popup = <MainPopup>this.getElementsByClassName(`${this.jsName}__popup`)[0];

        this.mapEvents();
    }

    protected mapEvents(): void {
        this.mapTriggerClickEvent();
    }

    protected mapTriggerClickEvent(): void {
        this.triggers.forEach((trigger: HTMLButtonElement) => {
            trigger.addEventListener('click', () => this.onTriggerClick());
        });
    }

    protected async onTriggerClick(): Promise<void> {
        if (this.popup) {
            await mount();
        }

        this.mapFinderSetServicePointEvent();
    }

    protected mapFinderSetServicePointEvent(): void {
        if (this.finder) {
            return;
        }

        this.finder = <ServicePointFinder>document.getElementsByClassName(this.finderClassName)[0];
        this.finder.addEventListener(EVENT_SET_SERVICE_POINT, (event: CustomEvent) =>
            this.onServicePointSelected(event),
        );
    }

    protected onServicePointSelected(event: CustomEvent): void {
        this.input.value = event.detail.uuid;
        this.location.innerHTML = event.detail.address;
        this.popup.dispatchEvent(new CustomEvent(EVENT_CLOSE_POPUP));
        this.input.dispatchEvent(new Event('input'));
        this.toggleContainer();
    }

    protected toggleContainer(): void {
        const hasInputValue = Boolean(this.input.value);

        this.noLocationContainer.classList.toggle(this.toggleClassName, hasInputValue);
        this.locationContainer.classList.toggle(this.toggleClassName, !hasInputValue);
    }

    protected get triggerClassName(): string {
        return this.getAttribute('trigger-class-name');
    }

    protected get finderClassName(): string {
        return this.getAttribute('finder-class-name');
    }

    protected get toggleClassName(): string {
        return this.getAttribute('toggle-class-name');
    }
}

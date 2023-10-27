import Component from 'ShopUi/models/component';
import MainPopup, { EVENT_CLOSE_POPUP, EVENT_POPUP_OPENED } from 'ShopUi/components/molecules/main-popup/main-popup';
import ServicePointFinder, {
    EVENT_SET_SERVICE_POINT,
    ServicePointEventDetail,
} from 'ServicePointWidget/components/molecules/service-point-finder/service-point-finder';

export default class ServicePointSelector extends Component {
    protected input: HTMLInputElement;
    protected noLocationContainer: HTMLElement;
    protected location: HTMLElement;
    protected locationContainer: HTMLElement;
    protected finder: ServicePointFinder;
    protected popup: MainPopup;
    protected deliverySelect: HTMLSelectElement;

    protected readyCallback(): void {}

    protected init(): void {
        this.input = <HTMLInputElement>this.getElementsByClassName(`${this.jsName}__input`)[0];
        this.noLocationContainer = <HTMLElement>this.getElementsByClassName(`${this.jsName}__no-location`)[0];
        this.location = <HTMLElement>this.getElementsByClassName(`${this.jsName}__location`)[0];
        this.locationContainer = <HTMLElement>this.getElementsByClassName(`${this.jsName}__location-container`)[0];
        this.popup = <MainPopup>this.getElementsByClassName(`${this.jsName}__popup`)[0];
        this.deliverySelect = <HTMLSelectElement>document.getElementsByClassName(this.deliverySelectClassName)[0];

        this.mapEvents();
    }

    protected mapEvents(): void {
        this.mapPopupOpenedEvent();
    }

    protected mapPopupOpenedEvent(): void {
        this.popup.addEventListener(EVENT_POPUP_OPENED, () => this.onPopupOpened());
    }

    protected onPopupOpened(): void {
        this.mapFinderSetServicePointEvent();
    }

    protected mapFinderSetServicePointEvent(): void {
        if (this.finder) {
            return;
        }

        this.finder = <ServicePointFinder>document.getElementsByClassName(this.finderClassName)[0];
        this.finder.addEventListener(EVENT_SET_SERVICE_POINT, (event: CustomEvent<ServicePointEventDetail>) =>
            this.onServicePointSelected(event.detail),
        );
    }

    protected onServicePointSelected(detail: ServicePointEventDetail): void {
        this.closePopup();

        if (this.deliverySelect && detail.partiallyAvailable) {
            this.deliverySelect.value = this.deliverySelectValue;
            this.deliverySelect.dispatchEvent(new Event('change'));

            return;
        }

        this.input.value = detail.uuid;
        this.location.innerHTML = detail.address;
        this.input.dispatchEvent(new Event('input'));
        this.toggleContainer();
    }

    protected closePopup(): void {
        this.popup.dispatchEvent(new CustomEvent(EVENT_CLOSE_POPUP));
    }

    protected toggleContainer(): void {
        const hasInputValue = Boolean(this.input.value);

        this.noLocationContainer.classList.toggle(this.toggleClassName, hasInputValue);
        this.locationContainer.classList.toggle(this.toggleClassName, !hasInputValue);
    }

    protected get finderClassName(): string {
        return this.getAttribute('finder-class-name');
    }

    protected get toggleClassName(): string {
        return this.getAttribute('toggle-class-name');
    }

    protected get deliverySelectClassName(): string {
        return this.getAttribute('delivery-select-class-name');
    }

    protected get deliverySelectValue(): string {
        return this.getAttribute('delivery-select-value');
    }
}

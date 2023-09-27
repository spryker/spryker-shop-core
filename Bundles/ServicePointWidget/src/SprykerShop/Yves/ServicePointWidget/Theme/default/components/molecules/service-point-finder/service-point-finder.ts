import Component from 'ShopUi/models/component';
import AjaxProvider from 'ShopUi/components/molecules/ajax-provider/ajax-provider';
import debounce from 'lodash-es/debounce';

export const EVENT_SET_SERVICE_POINT = 'setServicePoint';
export interface ServicePointEventDetail {
    uuid: string;
    address: string;
    partiallyAvailable?: boolean;
}

export default class ServicePointFinder extends Component {
    protected searchInput: HTMLInputElement;
    protected container: HTMLElement;
    protected ajaxProvider: AjaxProvider;
    protected currentSearchValue: string;

    protected readyCallback(): void {}

    protected init(): void {
        this.searchInput = <HTMLInputElement>this.getElementsByClassName(`${this.jsName}__search-field`)[0];
        this.container = <HTMLInputElement>this.getElementsByClassName(`${this.jsName}__service-points`)[0];
        this.ajaxProvider = <AjaxProvider>this.getElementsByClassName(`${this.jsName}__ajax-provider`)[0];

        this.mapEvents();

        if (this.hasInitialRequest) {
            this.fetchServicePoints();
        }
    }

    protected mapEvents(): void {
        this.mapSearchInputKeyUpEvent();
        this.mapServicePointsClickEvent();
    }

    protected mapSearchInputKeyUpEvent(): void {
        this.searchInput.addEventListener(
            'keyup',
            debounce(() => this.onInputKeyUp(), this.debounceDelay),
        );
    }

    protected mapServicePointsClickEvent(): void {
        this.container.addEventListener('click', (event: Event) => this.onServicePointsClick(event));
    }

    protected onInputKeyUp(): void {
        const isSearchLengthValid = this.searchValue.length >= this.minLetters || this.searchValue.length === 0;

        if (isSearchLengthValid && this.searchValue !== this.currentSearchValue) {
            this.currentSearchValue = this.searchValue;
            this.fetchServicePoints();
        }
    }

    protected onServicePointsClick(event: Event): void {
        const clickedElement = <HTMLElement>event.target;
        const isClickedServicePointTrigger =
            clickedElement.classList.contains(this.servicePointTriggerClassName) ||
            clickedElement.parentElement.classList.contains(this.servicePointTriggerClassName);

        if (isClickedServicePointTrigger) {
            this.dispatchSetServicePointEvent(clickedElement);
        }
    }

    protected dispatchSetServicePointEvent(servicePointTrigger: HTMLElement): void {
        const eventDetail: ServicePointEventDetail = {
            uuid: servicePointTrigger.dataset[this.servicePointUuidDataAttribute],
            address: servicePointTrigger.dataset[this.servicePointAddressDataAttribute],
        };
        const hasServicePointPartiallyAvailableDataAttribute =
            this.servicePointPartiallyAvailableDataAttribute in servicePointTrigger.dataset;

        if (hasServicePointPartiallyAvailableDataAttribute) {
            eventDetail.partiallyAvailable = hasServicePointPartiallyAvailableDataAttribute;
        }

        this.dispatchCustomEvent(EVENT_SET_SERVICE_POINT, eventDetail);
    }

    protected fetchServicePoints(): void {
        this.ajaxProvider.queryParams.set(this.queryString, this.searchValue);
        this.ajaxProvider.fetch();
    }

    protected get searchValue(): string {
        return this.searchInput.value.trim();
    }

    protected get debounceDelay(): number {
        return Number(this.getAttribute('debounce-delay'));
    }

    protected get minLetters(): number {
        return Number(this.getAttribute('min-letters'));
    }

    protected get queryString(): string {
        return this.getAttribute('query-string');
    }

    protected get hasInitialRequest(): boolean {
        return this.hasAttribute('has-initial-request');
    }

    protected get servicePointTriggerClassName(): string {
        return this.getAttribute('service-point-trigger-class-name');
    }

    protected get servicePointUuidDataAttribute(): string {
        return this.getAttribute('service-point-uuid-data-attribute');
    }

    protected get servicePointAddressDataAttribute(): string {
        return this.getAttribute('service-point-address-data-attribute');
    }

    protected get servicePointPartiallyAvailableDataAttribute(): string {
        return this.getAttribute('service-point-partially-available-data-attribute');
    }
}

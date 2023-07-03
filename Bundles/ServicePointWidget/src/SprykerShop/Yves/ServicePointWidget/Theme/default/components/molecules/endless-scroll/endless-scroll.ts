import Component from 'ShopUi/models/component';
import AjaxProvider, { EVENT_FETCHED, EVENT_FETCHING } from 'ShopUi/components/molecules/ajax-provider/ajax-provider';
import { mount } from 'ShopUi/app';

export const EVENT_CONTENT_UPDATED = 'scrollContentUpdated';

export default class EndlessScroll extends Component {
    protected ajaxProvider: AjaxProvider;
    protected scrollContainer: HTMLElement;
    protected contentContainer: HTMLElement;
    protected currentOffset = this.resultOffset;
    protected isFetching = false;
    protected containerScrollHandler: () => void;

    protected readyCallback(): void {}

    protected init(): void {
        this.ajaxProvider = <AjaxProvider>this.getElementsByClassName(`${this.jsName}__ajax-provider`)[0];
        this.scrollContainer = <HTMLElement>this.getElementsByClassName(`${this.jsName}__scroll-container`)[0];
        this.contentContainer = <HTMLElement>this.getElementsByClassName(`${this.jsName}__container`)[0];

        if (this.targetClassName) {
            this.scrollContainer = <HTMLElement>this.getElementsByClassName(this.targetClassName)[0];
            this.contentContainer = <HTMLElement>this.getElementsByClassName(this.targetClassName)[0];
        }

        if (this.isAllContentLoaded) {
            return;
        }

        this.mapEvents();
    }

    protected mapEvents(): void {
        this.mapContainerScrollEvent();
        this.mapFetchingContentEvent();
        this.mapFetchedContentEvent();
    }

    protected mapContainerScrollEvent(): void {
        this.containerScrollHandler = () => this.onContainerScroll();
        this.scrollContainer.addEventListener('scroll', this.containerScrollHandler);
    }

    protected mapFetchingContentEvent(): void {
        this.ajaxProvider.addEventListener(EVENT_FETCHING, () => this.onFetching());
    }

    protected mapFetchedContentEvent(): void {
        this.ajaxProvider.addEventListener(EVENT_FETCHED, () => this.onFetched());
    }

    protected onContainerScroll(): void {
        const scrollTopTriggerPosition =
            this.scrollContainer.scrollHeight - this.scrollContainer.clientHeight - this.scrollBottomTriggerPosition;

        if (scrollTopTriggerPosition <= this.scrollContainer.scrollTop) {
            this.fetchContent();
        }
    }

    protected onFetching(): void {
        this.isFetching = true;
    }

    protected onFetched(): void {
        this.isFetching = false;
        this.currentOffset += this.resultLimit;

        if (this.isAllContentLoaded) {
            this.removeScrollListener();
        }

        this.render();
    }

    protected fetchContent(): Promise<void> {
        if (this.isFetching) {
            return;
        }

        this.ajaxProvider.queryParams.set(this.queryRange, String(this.currentOffset));
        this.ajaxProvider.fetch();
    }

    protected render(): void {
        const response = this.ajaxProvider.xhr.response;

        if (!response) {
            this.removeScrollListener();

            return;
        }

        this.contentContainer.innerHTML += response;

        if (this.hasContentMount) {
            mount();
        }

        this.dispatchCustomEvent(EVENT_CONTENT_UPDATED);
    }

    protected removeScrollListener(): void {
        this.scrollContainer.removeEventListener('scroll', this.containerScrollHandler);
    }

    protected get isAllContentLoaded(): boolean {
        if (!this.resultTotal) {
            return false;
        }

        return this.resultTotal <= this.currentOffset;
    }

    protected get scrollBottomTriggerPosition(): number {
        const percentageContainerHeight = 100;

        return (
            (this.scrollContainer.scrollHeight / percentageContainerHeight) *
            (percentageContainerHeight - this.percentageRequestTrigger)
        );
    }

    protected get queryRange(): string {
        return this.getAttribute('query-range');
    }

    protected get resultOffset(): number {
        return Number(this.getAttribute('result-offset'));
    }

    protected get resultLimit(): number {
        return Number(this.getAttribute('result-limit'));
    }

    protected get resultTotal(): number {
        return Number(this.getAttribute('result-total'));
    }

    protected get percentageRequestTrigger(): number {
        return Number(this.getAttribute('percentage-request-trigger'));
    }

    protected get targetClassName(): string {
        return this.getAttribute('target-class-name');
    }

    protected get hasContentMount(): boolean {
        return this.hasAttribute('has-content-mount');
    }
}

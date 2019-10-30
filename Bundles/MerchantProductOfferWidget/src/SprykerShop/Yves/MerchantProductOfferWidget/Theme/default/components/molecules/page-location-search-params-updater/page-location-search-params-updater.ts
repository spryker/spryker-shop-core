import Component from 'ShopUi/models/component';

export default class PageLocationSearchParamsUpdater extends Component {
    protected triggers: HTMLElement[];

    protected readyCallback(): void {}

    protected init(): void {
        this.triggers = <HTMLElement[]>Array.from(document.getElementsByClassName(this.triggerClass));
        this.mapEvents();
    }

    protected mapEvents(): void {
        this.triggers.forEach(trigger => trigger.addEventListener(this.eventName, () => this.onEvent()));
    }

    protected onEvent(): void {
        window.location.search = this.searchParams;
    }

    protected get searchParams(): string {
        return this.getAttribute('search-params');
    }

    protected get triggerClass(): string {
        return this.getAttribute('trigger-class');
    }

    protected get eventName(): string {
        return this.getAttribute('event');
    }
}

import Component from 'ShopUi/models/component';

export default class QueryCreator extends Component {
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
        window.location.search = this.queryValue;
    }

    protected get queryValue(): string {
        return this.getAttribute('query');
    }

    protected get triggerClass(): string {
        return this.getAttribute('trigger-class');
    }

    protected get eventName(): string {
        return this.getAttribute('event');
    }
}

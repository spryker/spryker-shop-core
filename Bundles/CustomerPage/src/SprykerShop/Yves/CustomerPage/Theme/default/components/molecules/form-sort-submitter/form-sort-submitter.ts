import Component from 'ShopUi/models/component';

export default class FormSortSubmitter extends Component {
    protected triggers: HTMLElement[];
    protected targetSortBy: HTMLInputElement;
    protected targetSortDirection: HTMLInputElement;

    protected readyCallback(): void {}

    protected init(): void {
        this.triggers = <HTMLElement[]>Array.from(document.getElementsByClassName(this.triggerClassName));
        this.targetSortBy = <HTMLInputElement>document.getElementsByClassName(this.targetSortByClassName)[0];
        this.targetSortDirection = <HTMLInputElement>document.getElementsByClassName(this.targetSortDirectionClassName)[0];
        this.mapEvents();
    }

    protected mapEvents(): void {
        this.mapTriggerClickEvent();
    }

    protected mapTriggerClickEvent(): void {
        this.triggers.forEach((trigger: HTMLElement) => {
            trigger.addEventListener('click', () => this.setValues(trigger));
        });
    }

    protected setValues(trigger: HTMLElement): void {
        const sortByValue: string = trigger.getAttribute(this.sotrByAttribute);
        const sortDirectionValue: string = trigger.getAttribute(this.sotrDirectionAttribute);

        this.targetSortBy.value = sortByValue;
        this.targetSortDirection.value = sortDirectionValue;

        const form: HTMLFormElement = this.targetSortBy.closest('form');
        form.submit();
    }

    protected get triggerClassName(): string {
        return this.getAttribute('trigger-class-name');
    }

    protected get targetSortByClassName(): string {
        return this.getAttribute('target-sort-by-class-name');
    }

    protected get targetSortDirectionClassName(): string {
        return this.getAttribute('target-sort-direction-class-name');
    }

    protected get sotrByAttribute(): string {
        return this.getAttribute('sort-by-attribute');
    }

    protected get sotrDirectionAttribute(): string {
        return this.getAttribute('sort-direction-attribute');
    }
}

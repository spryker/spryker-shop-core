import Component from 'ShopUi/models/component';

export default class WindowLocationApplicator extends Component {
    protected form: HTMLFormElement;
    protected triggers: HTMLButtonElement[];

    protected readyCallback(): void {}

    protected init(): void {
        this.form = <HTMLFormElement>document.getElementsByClassName(this.formClassName)[0];
        this.triggers = <HTMLButtonElement[]>Array.from(document.getElementsByClassName(this.triggerClassName));

        this.mapEvents();
    }

    protected mapEvents(): void {
        this.triggers.forEach((element: HTMLButtonElement) => {
            element.addEventListener('click', (event: Event) => this.onTriggerEvent(event));
        });
    }

    protected onTriggerEvent(event: Event): void {
        const categoryUrl = (<HTMLButtonElement>event.currentTarget).getAttribute('data-url') || '';

        this.getQueryString(categoryUrl);
    }

    protected getQueryString(categoryUrl: string = window.location.pathname): void {
        const formData = new FormData(this.form);
        const data = new URLSearchParams(<URLSearchParams>formData);

        formData.forEach((value: string, key: string) => {
            if (value.length) {
                return;
            }

            data.delete(key);
        });

        this.setWindowLocation(categoryUrl, data.toString());
    }

    protected setWindowLocation(categoryUrl: string, data: string): void {
        window.location.href = `${categoryUrl}?${data}`;
    }

    protected get formClassName(): string {
        return this.getAttribute('form-class-name');
    }

    protected get triggerClassName(): string {
        return this.getAttribute('trigger-class-name');
    }
}

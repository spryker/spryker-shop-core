import Component from 'ShopUi/models/component';

export default class UrlGenerator extends Component {
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
        const categoryUrl = (<HTMLButtonElement>event.currentTarget).getAttribute('data-url');

        this.urlEncodeFormData(categoryUrl);
    }

    protected urlEncodeFormData(url?: string): void {
        const formData = new FormData(this.form);
        /* tslint:disable:no-any */
        const data = new URLSearchParams(formData as any);
        /* tslint:enable:no-any */
        const pathname = url ? url : window.location.pathname;

        formData.forEach((value: string, key: string) => {
            if (value.length) {
                return;
            }

            data.delete(key);
        });

        window.location.href = `${pathname}?${data.toString()}`;
    }

    protected get formClassName(): string {
        return this.getAttribute('form-class-name');
    }

    protected get triggerClassName(): string {
        return this.getAttribute('trigger-class-name');
    }
}

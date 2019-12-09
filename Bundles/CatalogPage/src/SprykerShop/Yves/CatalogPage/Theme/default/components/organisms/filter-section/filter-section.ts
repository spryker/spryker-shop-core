import Component from 'ShopUi/models/component';
import AjaxProvider from 'ShopUi/components/molecules/ajax-provider/ajax-provider';

export default class FilterSection extends Component {
    protected form: HTMLFormElement;
    protected ajaxProvider: AjaxProvider;
    protected triggers: HTMLButtonElement[];
    protected formSubmit: HTMLButtonElement;

    protected readyCallback(): void {}

    protected init(): void {
        this.form = <HTMLFormElement>this.closest('form');
        this.triggers = <HTMLButtonElement[]>Array.from(this.getElementsByClassName(`${this.jsName}__trigger`));
        this.ajaxProvider = <AjaxProvider>this.getElementsByClassName(`${this.jsName}__provider`)[0];
        this.formSubmit = <HTMLButtonElement>this.getElementsByClassName(`${this.jsName}__form-submit`)[0];

        this.mapEvents();
    }

    protected mapEvents(): void {
        this.triggers.forEach((element: HTMLButtonElement) => {
            element.addEventListener('click', (event: Event) => this.onCategoryClick(event));
        });

        this.formSubmit.addEventListener('click', () => this.onSubmit());
    }

    protected onCategoryClick(event: Event): void {
        const url = (<HTMLButtonElement>event.currentTarget).getAttribute('data-url');

        this.urlEncodeFormData(url);
    }

    protected onSubmit(): void {
        this.urlEncodeFormData();
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
}

import Component from 'ShopUi/models/component';
import AjaxProvider from 'ShopUi/components/molecules/ajax-provider/ajax-provider';

export default class FilterCategory extends Component {
    protected form: HTMLFormElement;
    protected purifyAjaxProvider: AjaxProvider;
    protected purifyTriggers: HTMLButtonElement[];

    protected readyCallback(): void {}

    protected init(): void {}
        this.form = <HTMLFormElement>this.closest('form');
        this.purifyTriggers = <HTMLButtonElement[]>Array.from(this.getElementsByClassName(`${this.jsName}__purify-trigger`));
        this.purifyAjaxProvider = <AjaxProvider>this.getElementsByClassName(`${this.jsName}__url-purify-provider`)[0];

        this.mapEvents();
    }

    protected mapEvents(): void {
        this.purifyTriggers.forEach((element: HTMLButtonElement) => {
            element.addEventListener('click', (event: Event) => this.onAddRowClick(event));
        });
    }

    protected onAddRowClick(event: Event): void {
        event.preventDefault();
        this.addRow();
    }

    protected addRow(): Promise<void> {
        const data = new FormData(this.form);
        const response = await this.purifyAjaxProvider.fetch(data);

        window.location.href = response;
    }
}

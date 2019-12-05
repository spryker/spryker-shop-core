import Component from 'ShopUi/models/component';
import AjaxProvider from 'ShopUi/components/molecules/ajax-provider/ajax-provider';

export default class FilterSection extends Component {
    protected form: HTMLFormElement;
    protected purifyAjaxProvider: AjaxProvider;
    protected purifyTriggers: HTMLButtonElement[];

    protected readyCallback(): void {}

    protected init(): void {
        this.form = <HTMLFormElement>this.closest('form');
        this.purifyTriggers = <HTMLButtonElement[]>Array.from(
            this.getElementsByClassName(`${this.jsName}__purify-trigger`)
        );
        this.purifyAjaxProvider = <AjaxProvider>this.getElementsByClassName(`${this.jsName}__url-purify-provider`)[0];

        this.mapEvents();
    }

    protected mapEvents(): void {
        this.purifyTriggers.forEach((element: HTMLButtonElement) => {
            element.addEventListener('click', (event: Event) => this.onCategoryClick(event));
        });
    }

    protected onCategoryClick(event: Event): void {
        event.preventDefault();
        const url = (<HTMLButtonElement>event.currentTarget).getAttribute('data-url');
        this.categoryRedirect(url);
    }

    protected async categoryRedirect(url: string): Promise<void> {
        const data = new FormData(this.form);
        data.append('url', url);
        const response = await this.purifyAjaxProvider.fetch(data);
        const jsonResponse = JSON.parse(response);
        window.location.href = jsonResponse.url;
    }
}

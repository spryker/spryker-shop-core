import Component from 'ShopUi/models/component';
import AjaxProvider from 'ShopUi/components/molecules/ajax-provider/ajax-provider';
import { mount } from 'ShopUi/app';


export default class CartUpselling extends Component {
    protected providers: AjaxProvider[];

    protected readyCallback(): void {
        /* tslint:disable: deprecation */
        this.providers = <AjaxProvider[]>(Array.from(document.getElementsByClassName(this.providerClassName)));
        /* tslint:enable: deprecation */
        this.mapEvents();
    }

    protected mapEvents(): void {
        this.providers.forEach((provider: AjaxProvider) => {
            provider.addEventListener('fetched', (event: Event) => this.onFetched(event));
        });
    }

    protected async onFetched(event: Event): Promise<void> {
        await mount();
    }

    protected get providerClassName(): string {
        return this.getAttribute('provider-class-name');
    }
}

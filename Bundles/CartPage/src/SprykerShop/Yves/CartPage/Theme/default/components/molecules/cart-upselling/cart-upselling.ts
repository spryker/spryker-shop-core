import Component from 'ShopUi/models/component';
import AjaxProvider from 'ShopUi/components/molecules/ajax-provider/ajax-provider';
import { mount } from 'ShopUi/app';

export default class CartUpselling extends Component {
    protected providers: AjaxProvider[];

    protected readyCallback(): void {
        this.providers = <AjaxProvider[]>Array.from(document.getElementsByClassName(this.providerClassName));

        this.mapEvents();
    }

    protected mapEvents(): void {
        this.providers.forEach((provider: AjaxProvider) => {
            provider.addEventListener('fetched', () => this.onFetched());
        });
    }

    protected async onFetched(): Promise<void> {
        await mount();
    }

    protected get providerClassName(): string {
        return this.getAttribute('provider-class-name');
    }
}

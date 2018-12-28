import Component from '../../../models/component';
import AjaxProvider from '../ajax-provider/ajax-provider';
import { mount } from 'ShopUi/app';

export default class AjaxRenderer extends Component {
    protected provider: AjaxProvider
    protected target: HTMLElement

    protected readyCallback(): void {
        this.provider = <AjaxProvider>document.querySelector(this.providerSelector);
        this.target = !!this.targetSelector ? <HTMLElement>document.querySelector(this.targetSelector) : null;
        this.mapEvents();
    }

    protected mapEvents(): void {
        this.provider.addEventListener('fetched', (event: Event) => this.onFetched(event));
    }

    protected onFetched(event: Event): void {
        this.render();
    }

    /**
     * Gets a responcew from the server and render this ne on a page
     */
    render(): void {
        const response = this.provider.xhr.response;

        if (!response && !this.renderIfResponseIsEmpty) {
            return;
        }

        if (!!this.target) {
            this.target.innerHTML = response;
            return;
        }

        this.innerHTML = response;
    }

    /**
     * Gets a querySelector name of the provider element
     */
    get providerSelector(): string {
        return this.getAttribute('provider-selector') || '';
    }

    /**
     * Gets a querySelector name of the target element
     */
    get targetSelector(): string {
        return this.getAttribute('target-selector') || '';
    }

    /**
     * Gets if the responce from the server is empty
     */
    get renderIfResponseIsEmpty(): boolean {
        return this.hasAttribute('render-if-response-is-empty');
    }

    /**
     * Gets if the component is mounted after rendering
     */
    get mountAfterRender(): boolean {
        return this.hasAttribute('mount-after-render');
    }
}

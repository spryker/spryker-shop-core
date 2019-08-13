import Component from '../../../models/component';
import AjaxProvider from '../ajax-provider/ajax-provider';
import { mount } from 'ShopUi/app';

export default class AjaxRenderer extends Component {
    protected provider: AjaxProvider;
    protected target: HTMLElement;

    protected readyCallback(): void {
        /* tslint:disable: deprecation */
        this.provider = <AjaxProvider> (this.providerClassName ?
            document.getElementsByClassName(this.providerClassName)[0] : document.querySelector(this.providerSelector));
        this.target = <HTMLElement> (this.targetClassName ?
            document.getElementsByClassName(this.targetClassName)[0] :
            document.querySelector(this.targetSelector ? this.targetSelector : undefined));
        /* tslint:enable: deprecation */
        this.mapEvents();
    }

    protected mapEvents(): void {
        this.provider.addEventListener('fetched', (event: Event) => this.onFetched(event));
    }

    protected onFetched(event: Event): void {
        this.render();
    }

    /**
     * Gets a response from the server and renders it on the page.
     */
    render(): void {
        const response = this.provider.xhr.response;

        if (!response && !this.renderIfResponseIsEmpty) {
            return;
        }

        if (this.target) {
            this.target.innerHTML = response;

            return;
        }

        this.innerHTML = response;
    }

    /**
     * Gets a querySelector name of the provider element.
     *
     * @deprecated Use providerClassName() instead.
     */
    get providerSelector(): string {
        return this.getAttribute('provider-selector');
    }
    protected get providerClassName(): string {
        return this.getAttribute('provider-class-name');
    }

    /**
     * Gets a querySelector name of the target element.
     *
     * @deprecated Use targetClassName() instead.
     */
    get targetSelector(): string {
        return this.getAttribute('target-selector');
    }
    protected get targetClassName(): string {
        return this.getAttribute('target-class-name');
    }

    /**
     * Gets if the response from the server is empty.
     */
    get renderIfResponseIsEmpty(): boolean {
        return this.hasAttribute('render-if-response-is-empty');
    }

    /**
     * Gets if the component is mounted after rendering.
     */
    get mountAfterRender(): boolean {
        return this.hasAttribute('mount-after-render');
    }
}

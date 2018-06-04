import Component from '../../../models/component';
import AjaxProvider from '../ajax-provider/ajax-provider';

export default class AjaxRenderer extends Component {
    protected provider: AjaxProvider
    protected target: HTMLElement

    readyCallback() {
        this.provider = <AjaxProvider>document.querySelector(this.providerSelector);
        this.target = !!this.targetSelector ? <HTMLElement>document.querySelector(this.targetSelector) : this;
        this.mapEvents();
    }

    protected mapEvents() {
        this.provider.addEventListener('fetched', (event: Event) => this.onFetched(event));
    }

    protected onFetched(event: Event): void {
        this.render();
    }

    render(): void {
        const response = this.provider.xhr.response;

        if (!!response || this.renderIfResponseIsEmpty) {
            this.target.innerHTML = response;
        }
    }

    get providerSelector(): string {
        return this.getAttribute('provider-selector') || '';
    }

    get targetSelector(): string {
        return this.getAttribute('target-selector') || '';
    }

    get renderIfResponseIsEmpty(): boolean {
        return this.hasAttribute('render-if-response-is-empty');
    }
}

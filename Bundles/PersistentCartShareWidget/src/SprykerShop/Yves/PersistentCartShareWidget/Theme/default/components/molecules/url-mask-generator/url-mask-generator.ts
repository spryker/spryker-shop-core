import { mount } from 'ShopUi/app';
import Component from 'ShopUi/models/component';
import AjaxProvider from 'ShopUi/components/molecules/ajax-provider/ajax-provider';

export default class UrlMaskGenerator extends Component {
    protected provider: AjaxProvider;
    protected trigger: HTMLInputElement;
    protected isActionsRendered: boolean = false;

    protected readyCallback(): void {
        this.provider = <AjaxProvider>this.querySelector(`.${this.jsName}__provider-${this.shareOptionGroup}`);
        this.trigger = <HTMLInputElement>this.querySelector(`.${this.jsName}__trigger-${this.shareOptionGroup} .js-toggler-radio__trigger`);
        this.mapEvents();
    }

    protected mapEvents(): void {
        this.trigger.addEventListener('change', (event: Event) => this.onChange(event));
    }

    protected onChange(event: Event): void {
        if(!this.isActionsRendered) {
            this.render();
            this.isActionsRendered = true;
        }
    }

    /**
     * Sends data to the server using ajaxProvider and rerender the form.
     */
    async render(): Promise<void> {
        await this.provider.fetch();
        mount();
    }

    /**
     * Gets a share option group of the request.
     */
    get shareOptionGroup(): string {
        return this.getAttribute('shareOptionGroup');
    }
}

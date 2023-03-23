import { mount } from 'ShopUi/app';
import Component from 'ShopUi/models/component';
import AjaxProvider from 'ShopUi/components/molecules/ajax-provider/ajax-provider';

export default class UrlMaskGenerator extends Component {
    protected provider: AjaxProvider;
    protected trigger: HTMLInputElement;
    protected isActionsRendered = false;

    protected readyCallback(): void {
        this.provider = <AjaxProvider>(
            this.getElementsByClassName(`${this.jsName}__provider-${this.shareOptionGroup}`)[0]
        );
        this.trigger = <HTMLInputElement>(this.triggerClassName
            ? this.getElementsByClassName(this.triggerClassName)[0]
            : // eslint-disable-next-line deprecation/deprecation
              this.querySelector(this.triggerSelector));

        this.mapEvents();
    }

    protected mapEvents(): void {
        this.trigger.addEventListener('change', () => this.onChange());
    }

    protected onChange(): void {
        if (!this.isActionsRendered) {
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

    /**
     * Gets a css query selector to address the html element that will trigger the render of the form.
     *
     * @deprecated Use triggerClassName() instead.
     */
    get triggerSelector(): string {
        return this.getAttribute('trigger-selector');
    }
    protected get triggerClassName(): string {
        return this.getAttribute('trigger-class-name');
    }
}

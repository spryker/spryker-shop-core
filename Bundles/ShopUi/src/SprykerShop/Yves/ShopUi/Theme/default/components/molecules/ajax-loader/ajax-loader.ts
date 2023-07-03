import Component from '../../../models/component';
import AjaxProvider, { EVENT_FETCHING, EVENT_FETCHED } from '../ajax-provider/ajax-provider';

export default class AjaxLoader extends Component {
    protected parent: HTMLElement;
    protected providers: AjaxProvider[];

    protected readyCallback(): void {}

    protected init(): void {
        this.parent = <HTMLElement>(this.parentClassName ? this.closest(`.${this.parentClassName}`) : document);
        this.providers = <AjaxProvider[]>Array.from(
            this.providerClassName
                ? this.parent.getElementsByClassName(this.providerClassName)
                : // eslint-disable-next-line deprecation/deprecation
                  this.parent.querySelectorAll(this.providerSelector),
        );

        this.mapEvents();
    }

    protected mapEvents(): void {
        this.providers.forEach((provider: AjaxProvider) => {
            provider.addEventListener(EVENT_FETCHING, () => this.onFetching());
            provider.addEventListener(EVENT_FETCHED, () => this.onFetched());
        });
    }

    protected onFetching(): void {
        this.classList.remove('is-invisible');
    }

    protected onFetched(): void {
        this.classList.add('is-invisible');
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

    protected get parentClassName(): string {
        return this.getAttribute('parent-class-name');
    }
}

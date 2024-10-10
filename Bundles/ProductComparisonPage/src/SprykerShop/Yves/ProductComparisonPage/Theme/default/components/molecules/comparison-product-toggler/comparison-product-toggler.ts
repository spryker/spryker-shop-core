import { EVENT_UPDATE_DYNAMIC_MESSAGES } from 'ShopUi/components/organisms/dynamic-notification-area/dynamic-notification-area';
import Component from 'ShopUi/models/component';
import { COMPARISON_STORAGE_KEY } from '../comparison-link/comparison-link';

export default class ComparisonProductToggler extends Component {
    protected skus: string[] = [];
    protected buttonPlaceholder?: HTMLElement;
    protected button?: HTMLButtonElement;
    protected addTemplate: string;
    protected removeTemplate: string;

    protected readyCallback(): void {}
    protected init(): void {
        this.buttonPlaceholder = this.querySelector<HTMLElement>(`.${this.jsName}__button-placeholder`);
        this.button = this.querySelector<HTMLButtonElement>(`.${this.jsName}__button`);
        this.addTemplate = this.querySelector<HTMLElement>(`.${this.jsName}__add-template`)?.innerHTML;
        this.removeTemplate = this.querySelector<HTMLElement>(`.${this.jsName}__remove-template`)?.innerHTML;

        this.updateStaleState();
        this.button.addEventListener('click', (event) => this.toggle(event));

        window.addEventListener('storage', (event) => {
            if (event.key === COMPARISON_STORAGE_KEY) {
                this.updateStaleState();
            }
        });
    }

    protected toggle(event: Event): void {
        event.preventDefault();

        const hasSku = this.skus.includes(this.sku);
        const messagesKey = this.skus.length >= this.maxItems && !hasSku ? 'max' : hasSku ? 'remove' : 'success';

        document.dispatchEvent(
            new CustomEvent(EVENT_UPDATE_DYNAMIC_MESSAGES, {
                detail: this.querySelector<HTMLTemplateElement>(`.${this.jsName}__${messagesKey}-message-template`)
                    ?.innerHTML,
            }),
        );

        if (this.skus.length >= this.maxItems && !hasSku) {
            return;
        }

        this.skus = hasSku ? this.skus.filter((item) => item !== this.sku) : [...this.skus, this.sku];
        localStorage.setItem(COMPARISON_STORAGE_KEY, JSON.stringify(this.skus));
        this.redirect(hasSku);
        this.updateButtonTemplate();
    }

    protected updateStaleState(): void {
        this.skus = JSON.parse(localStorage.getItem(COMPARISON_STORAGE_KEY)) ?? [];
        this.updateButtonTemplate();
    }

    protected redirect(hasSku: boolean): void {
        const url = `${this.url}?&skus=${this.skus.join(',')}`;
        const updateOnAdd = !hasSku && this.skus.length >= this.redirectLength;
        const updateOnRemove = hasSku && this.updateUrlOnRemove;

        if (updateOnAdd || updateOnRemove) {
            window.location.href = url;
            this.button.disabled = true;
        }
    }

    protected updateButtonTemplate(): void {
        if (!this.buttonPlaceholder || !this.removeTemplate.length || !this.addTemplate.length) {
            return;
        }

        this.buttonPlaceholder.innerHTML = this.skus.includes(this.sku) ? this.removeTemplate : this.addTemplate;
    }

    get maxItems(): number {
        return Number(this.getAttribute('maxItems'));
    }

    get url(): string {
        return this.getAttribute('url');
    }

    get sku(): string {
        return this.getAttribute('sku');
    }

    get redirectLength(): number {
        return Number(this.getAttribute('redirectLength'));
    }

    get updateUrlOnRemove(): boolean {
        return this.hasAttribute('updateUrlOnRemove');
    }
}

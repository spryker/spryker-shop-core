import Component from 'ShopUi/models/component';

export const COMPARISON_STORAGE_KEY = 'comparison-skus';

export default class ComparisonLink extends Component {
    protected readyCallback(): void {}

    protected init(): void {
        this.querySelector<HTMLButtonElement>(`.${this.jsName}__link`).addEventListener('click', (event: Event) =>
            this.redirect(event),
        );
    }

    protected redirect(event: Event): void {
        event.preventDefault();

        const skus = JSON.parse(localStorage.getItem(COMPARISON_STORAGE_KEY)) ?? [];

        window.location.href = skus.length ? `${this.url}?&skus=${skus.join(',')}` : this.url;
    }

    get url(): string {
        return this.getAttribute('url');
    }
}

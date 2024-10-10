import Component from 'ShopUi/models/component';
import { COMPARISON_STORAGE_KEY } from '../comparison-link/comparison-link';

export default class ClearComparison extends Component {
    protected button: HTMLButtonElement;

    protected readyCallback(): void {}
    protected init(): void {
        this.button = this.querySelector<HTMLButtonElement>(`.${this.jsName}__button`);

        this.button.addEventListener('click', (event) => this.clear(event));
    }

    protected clear(event: Event): void {
        event.preventDefault();

        localStorage.removeItem(COMPARISON_STORAGE_KEY);
        this.button.disabled = true;
        window.location.href = this.url;
    }

    get url(): string {
        return this.getAttribute('url');
    }
}

import Component from 'ShopUi/models/component';

export default class MiniCartRadio extends Component {
    /**
     * The radio button element.
     */
    radio: HTMLElement;

    protected readyCallback(): void {
        this.radio = <HTMLElement>this.getElementsByClassName(`${this.jsName}__input`)[0];

        this.mapEvents();
    }

    private mapEvents(): void {
        this.onclick = () => window.location.href = this.locationUrl;
    }

    /**
     * Gets the location url from a checked radio button.
     */
    get locationUrl(): string {
        return this.radio.dataset.href;
    }
}

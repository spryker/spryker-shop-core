import Component from 'ShopUi/models/component';

export default class MiniCartRadio extends Component {
    /**
     * The radio button element.
     *
     * @deprecated
     */
    radio: HTMLElement;
    protected form: HTMLFormElement;

    protected readyCallback(): void {}

    protected init(): void {
        this.radio = <HTMLElement>this.getElementsByClassName(`${this.jsName}__input`)[0];
        this.form = <HTMLFormElement>this.getElementsByClassName(`${this.jsName}__form`)[0];

        this.mapEvents();
    }

    private mapEvents(): void {
        this.addEventListener('click', () => this.onMiniCartRadioClick())
    }

    protected onMiniCartRadioClick(): void {
        this.form.submit();
    }

    /**
     * Gets the location url from a checked radio button.
     *
     * @deprecated
     */
    get locationUrl(): string {
        return this.radio.dataset.href;
    }
}

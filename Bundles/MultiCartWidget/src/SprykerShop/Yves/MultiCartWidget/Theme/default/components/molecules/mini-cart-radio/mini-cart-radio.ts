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
        /* tslint:disable: deprecation */
        this.radio = <HTMLElement>this.getElementsByClassName(`${this.jsName}__input`)[0];
        /* tslint:enable: deprecation */
        this.form = <HTMLFormElement>this.getElementsByClassName(`${this.jsName}__form`)[0];

        this.mapEvents();
    }

    protected mapEvents(): void {
        this.addEventListener('click', () => this.onMiniCartRadioClick());
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
        /* tslint:disable: deprecation */
        return this.radio.dataset.href;
        /* tslint:enable: deprecation */
    }
}

import Component from 'ShopUi/models/component';

export default class MiniCartRadio extends Component {
    /**
     * The radio button element.
     */
    radio: HTMLElement;
    form: HTMLFormElement;

    protected readyCallback(): void {

    }

    protected init(): void {
        this.radio = <HTMLElement>this.getElementsByClassName(`${this.jsName}__input`)[0];
        this.form = <HTMLFormElement>this.getElementsByClassName(`${this.jsName}__form`)[0];
        console.log(111);

        this.mapEvents();
    }

    protected formSubmit(): void {
        this.form.submit();
    }

    protected mapSubmitEvent(): void {
        this.onclick = () => this.formSubmit();
    }

    private mapEvents(): void {
        this.mapSubmitEvent();
    }

    /**
     * Gets the location url from a checked radio button.
     */
    get locationUrl(): string {
        return this.radio.dataset.href;
    }
}

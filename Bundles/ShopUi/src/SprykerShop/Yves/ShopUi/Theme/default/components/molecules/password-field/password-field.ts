import Component from 'ShopUi/models/component';

export default class PasswordField extends Component {
    protected isPasswordShown = false;
    protected button: HTMLElement;
    protected input: HTMLInputElement;

    protected readyCallback(): void {}

    protected init(): void {
        this.button = <HTMLElement>this.getElementsByClassName(this.buttonClassName)[0];
        this.input = <HTMLInputElement>this.getElementsByClassName(`${this.jsName}__input`)[0];

        this.mapEvents();
    }

    protected mapEvents(): void {
        this.mapButtonClickEvent();
    }

    protected mapButtonClickEvent(): void {
        this.button.addEventListener('click', () => this.onClick());
    }

    protected onClick(): void {
        this.isPasswordShown = !this.isPasswordShown;

        this.toggleInputType();
        this.toggleButtonClass();
    }

    protected toggleInputType(): void {
        this.input.setAttribute('type', this.isPasswordShown ? 'text' : 'password');
    }

    protected toggleButtonClass(): void {
        this.button.classList.toggle(this.buttonToggleClassName, this.isPasswordShown);
    }

    protected get buttonClassName(): string {
        return this.getAttribute('button-class-name');
    }

    protected get buttonToggleClassName(): string {
        return this.getAttribute('button-toggle-class-name');
    }
}

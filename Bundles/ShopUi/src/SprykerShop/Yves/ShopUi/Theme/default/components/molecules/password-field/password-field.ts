import Component from '../../../models/component';

export default class PasswordField extends Component {
    protected isPasswordShown = false;
    protected button: HTMLElement;
    protected input: HTMLInputElement;

    protected readyCallback(): void {}

    protected init(): void {
        this.button = <HTMLElement>this.getElementsByClassName(this.buttonClass)[0];
        this.input = <HTMLInputElement>this.getElementsByClassName(`${this.jsName}__input`)[0];

        this.mapEvents();
    }

    protected mapEvents(): void {
        this.button.addEventListener('click', () => this.onClick());
    }

    protected onClick(): void {
        this.isPasswordShown = !this.isPasswordShown;

        this.changeInputType();
        this.changeButtonClass();
    }

    protected changeInputType(): void {
        this.input.setAttribute('type', this.isPasswordShown ? 'text' : 'password');
    }

    protected changeButtonClass(): void {
        this.button.classList.toggle(this.buttonToggleClass, this.isPasswordShown);
    }

    protected get buttonClass(): string {
        return this.getAttribute('button-class');
    }

    protected get buttonToggleClass(): string {
        return this.getAttribute('button-toggle-class');
    }
}

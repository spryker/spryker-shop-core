import Component from 'ShopUi/models/component';

export default class AddressFormToggler extends Component {
    toggler: HTMLSelectElement;
    form: HTMLFormElement;

    protected readyCallback(): void {
        this.toggler = <HTMLSelectElement>document.querySelector(this.triggerSelector);
        this.form = <HTMLFormElement>document.querySelector(this.targetSelector);

        this.mapEvents();
    }

    protected mapEvents(): void {
        this.toggler.addEventListener('change', (event: Event) => this.onTogglerChange(event))
    }

    protected onTogglerChange(event: Event): void {
        const togglerElement = <HTMLSelectElement>event.srcElement;
        const selectedOption = <string>togglerElement.options[togglerElement.selectedIndex].value;

        this.toggle(!!selectedOption);
    }

    toggle(isShown: boolean): void {
        this.form.classList.toggle(this.classToToggle, isShown);
    }

    get triggerSelector(): string {
        return this.getAttribute('trigger-selector');
    }

    get targetSelector(): string {
        return this.getAttribute('target-selector');
    }

    get classToToggle(): string {
        return this.getAttribute('class-to-toggle');
    }
}

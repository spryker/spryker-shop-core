import Component from 'ShopUi/models/component';

export default class AddressFormToggler extends Component {
    toggler: HTMLSelectElement;
    form: HTMLElement;

    protected readyCallback(): void {
        this.toggler = <HTMLSelectElement>document.querySelector(this.triggerSelector);
        this.form = <HTMLElement>document.querySelector(this.targetSelector);

        this.mapEvents();
    }

    protected mapEvents(): void {
        this.toggler.addEventListener('change', (e: Event) => this.onTogglerChange(e))
    }

    protected onTogglerChange(e: Event): void {
        const togglerElement = <HTMLSelectElement>e.srcElement;
        const selectedOption = <string>togglerElement.options[togglerElement.selectedIndex].value;

        this.toggle(!!selectedOption);
    }

    toggle(shown: boolean): void {
        this.form.classList.toggle(this.classToToggle, shown);
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

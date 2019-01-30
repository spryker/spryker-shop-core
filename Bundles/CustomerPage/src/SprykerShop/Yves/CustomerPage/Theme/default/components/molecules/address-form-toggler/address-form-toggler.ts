import Component from 'ShopUi/models/component';

export default class AddressFormToggler extends Component {
    toggler: HTMLSelectElement;
    form: HTMLFormElement;

    protected readyCallback(): void {
        if(this.triggerSelector) {
            this.toggler = <HTMLSelectElement>document.querySelector(this.triggerSelector);
            this.form = <HTMLFormElement>document.querySelector(this.targetSelector);

            this.onTogglerChange();
            this.mapEvents();
        }
    }

    protected mapEvents(): void {
        this.toggler.addEventListener('change', () => this.onTogglerChange());
    }

    protected onTogglerChange(): void {
        const selectedOption = <string>this.toggler.options[this.toggler.selectedIndex].value;

        this.toggle(!!selectedOption);
    }

    toggle(isShown: boolean): void {
        const hasCompanyBusinessUnitAddress = (this.hasCompanyBusinessUnitAddress == 'true');

        this.form.classList.toggle(this.classToToggle, hasCompanyBusinessUnitAddress ? false : isShown);
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

    get hasCompanyBusinessUnitAddress(): string {
        return this.getAttribute('has-company-business-unit-address');
    }
}

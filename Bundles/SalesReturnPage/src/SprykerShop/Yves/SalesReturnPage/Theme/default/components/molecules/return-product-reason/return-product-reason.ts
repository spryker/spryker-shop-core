import Component from 'ShopUi/models/component';

export default class ReturnProductReason extends Component {
    protected select: HTMLSelectElement;
    protected target: HTMLElement;

    protected readyCallback(): void {}

    protected init(): void {
        this.select = <HTMLSelectElement>this.getElementsByClassName(`${this.jsName}__select`)[0];
        this.target = <HTMLElement>this.getElementsByClassName(`${this.jsName}__target`)[0];

        this.toggleTargetClass();
        this.mapEvents();
    }

    protected mapEvents(): void {
        this.mapSelectChange();
    }

    protected mapSelectChange(): void {
        this.select.addEventListener('change', () => this.onSelectChange());
    }

    protected onSelectChange(): void {
        this.toggleTargetClass();
    }

    protected toggleTargetClass(): void {
        const isToggleValueSelected = this.toggleValue === this.select.value;
        this.target.classList.toggle(this.classToToggle, !isToggleValueSelected);
    }

    protected get toggleValue(): string {
        return this.getAttribute('toggle-value');
    }

    protected get classToToggle(): string {
        return this.getAttribute('class-to-toggle');
    }
}

import Component from 'ShopUi/models/component';

export default class CartReorderForm extends Component {
    protected readonly selections: HTMLInputElement[];
    protected readonly trigger: HTMLElement;

    constructor() {
        super();

        this.selections = Array.from(this.querySelectorAll<HTMLInputElement>(`.${this.jsName}__selection`));
        this.trigger = this.querySelector<HTMLElement>(`.${this.jsName}__trigger`);
    }

    protected readyCallback(): void {}
    protected init(): void {
        this.selections.forEach((selection: HTMLInputElement) =>
            selection.addEventListener('change', () => this.onSelectionChange()),
        );
    }

    protected onSelectionChange(): void {
        const isEnabled = this.selections.some((selection: HTMLInputElement) => selection.checked);
        this.trigger.toggleAttribute('disabled', !isEnabled);
    }
}

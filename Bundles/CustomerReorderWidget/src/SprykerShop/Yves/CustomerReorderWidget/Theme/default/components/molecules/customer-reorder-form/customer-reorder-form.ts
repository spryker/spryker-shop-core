import Component from 'ShopUi/models/component';

export default class CustomerReorderForm extends Component {
    protected readonly selections: HTMLInputElement[];
    protected readonly trigger: HTMLElement;

    constructor() {
        super();
        this.selections = <HTMLInputElement[]>Array.from(this.getElementsByClassName(`${this.jsName}__selection`));
        this.trigger = <HTMLElement>this.getElementsByClassName(`${this.jsName}__trigger`)[0];
    }

    protected readyCallback(): void {
        this.mapEvents();
    }

    protected mapEvents(): void {
        this.selections.forEach((selection: HTMLInputElement) =>
            selection.addEventListener('change', (event: Event) => this.onSelectionChange(event))
        );
    }

    protected onSelectionChange(event: Event): void {
        const isEnabled = this.selections.some((selection: HTMLInputElement) => selection.checked);
        this.enableTrigger(isEnabled);
    }

    /**
     * Sets/removes the disabled attribute from the trigger button element, which if not disabled, on click can
     * reorder selected orders.
     * @param enable A boolean value for checking if the trigger is available for changing.
     */
    enableTrigger(isEnabled: boolean): void {
        if (isEnabled) {
            this.trigger.removeAttribute('disabled');

            return;
        }

        this.trigger.setAttribute('disabled', 'disabled');
    }
}

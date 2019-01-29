import Component from 'ShopUi/models/component';

export default class CustomerReorder extends Component {
    /**
     * Elements enabling/disabling the trigger.
     */
    readonly selections: HTMLInputElement[]

    /**
     * Element enabled/disabled by selections changes.
     */
    readonly trigger: HTMLElement

    constructor() {
        super();
        this.selections = <HTMLInputElement[]>Array.from(this.querySelectorAll(`.${this.jsName}__selection`));
        this.trigger = <HTMLElement>this.querySelector(`.${this.jsName}__trigger`);
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
        const enable = this.selections.some((selection: HTMLInputElement) => selection.checked);
        this.enableTrigger(enable);
    }

    /**
     * Sets or removes the disabled attribute from the trigger element.
     * @param enable A boolean value for checking if the trigger is available for changing.
     */
    enableTrigger(enable: boolean): void {
        if (enable) {
            this.trigger.removeAttribute('disabled');
            return;
        }

        this.trigger.setAttribute('disabled', 'disabled');
    }
}

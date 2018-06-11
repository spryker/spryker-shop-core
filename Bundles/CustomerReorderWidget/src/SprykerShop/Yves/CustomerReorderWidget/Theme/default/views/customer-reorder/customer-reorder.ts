import Component from 'ShopUi/models/component';

export default class CustomerReorder extends Component {
    readonly selections: HTMLInputElement[]
    readonly trigger: HTMLElement

    constructor() {
        super();
        this.selections = <HTMLInputElement[]>Array.from(this.querySelectorAll(`.${this.jsName}__selection`));
        this.trigger = <HTMLElement>this.querySelector(`.${this.jsName}__trigger`);
    }

    readyCallback() {
        this.mapEvents();
    }

    mapEvents() {
        this.selections.forEach((selection: HTMLInputElement) =>
            selection.addEventListener('change', (event: Event) => this.onSelectionChange(event))
        );
    }

    onSelectionChange(event: Event) {
        const enable = this.selections.some((selection: HTMLInputElement) => selection.checked);
        this.enableTrigger(enable);
    }

    enableTrigger(enable: boolean) {
        if (enable) {
            this.trigger.removeAttribute('disabled');
            return;
        }

        this.trigger.setAttribute('disabled', 'disabled');
    }
}

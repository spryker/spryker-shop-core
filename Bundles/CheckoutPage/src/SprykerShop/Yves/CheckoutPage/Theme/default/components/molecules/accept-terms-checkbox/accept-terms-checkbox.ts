import Component from 'ShopUi/models/component';

export default class AcceptTermsCheckbox extends Component {
    protected trigger: HTMLInputElement;
    protected target: HTMLButtonElement;

    protected readyCallback(): void {}

    protected init(): void {
        this.trigger = <HTMLInputElement>this.getElementsByClassName(`${this.jsName}__trigger`)[0];
        this.target = <HTMLButtonElement>document.getElementsByClassName(this.targetClassName)[0];

        this.toggleTargetDisabling();
        this.mapEvents();
    }

    protected mapEvents(): void {
        this.mapTriggerChangeEvent();
    }

    protected mapTriggerChangeEvent(): void {
        this.trigger.addEventListener('change', () => this.onTriggerChange());
    }

    protected onTriggerChange(): void {
        this.toggleTargetDisabling();
    }

    protected toggleTargetDisabling(): void {
        if (!this.target) {
            return;
        }

        if (!this.trigger.checked) {
            this.target.disabled = true;

            return;
        }

        this.target.disabled = false;
    }

    protected get targetClassName(): string {
        return this.getAttribute('target-class-name');
    }
}

import Component from 'ShopUi/models/component';

export default class ButtonsStateHandler extends Component {
    protected triggers: HTMLInputElement[];
    protected targets: HTMLElement[];

    protected readyCallback(): void {}

    protected init(): void {
        this.triggers = <HTMLInputElement[]>Array.from(document.getElementsByClassName(this.triggerClassName));
        this.targets = <HTMLElement[]>Array.from(document.getElementsByClassName(this.targetClassName));
        this.mapEvents();
    }

    protected mapEvents(): void {
        this.mapTriggerChangeEvent();
        this.mapTargetClickEvent();
    }

    protected mapTriggerChangeEvent(): void {
        this.triggers.forEach((trigger: HTMLInputElement) => {
            trigger.addEventListener('change', () => this.onTriggerChange());
        });
    }

    protected mapTargetClickEvent(): void {
        this.targets.forEach((target: HTMLLinkElement) => {
            target.addEventListener('click', (event: Event) => this.onTargetClick(event, target));
        });
    }

    protected onTriggerChange(): void {
        const checkedTriggers = this.triggers.filter(checkbox => checkbox.checked);

        if (checkedTriggers.length) {
            this.disableTargets();

            return;
        }

        this.enableTargets();
    }

    protected onTargetClick(event: Event, target: HTMLLinkElement): void {
        if (target.hasAttribute('disabled')) {
            event.preventDefault();
        }
    }

    protected disableTargets(): void {
        this.targets.forEach((target: HTMLInputElement) => {
            target.setAttribute('disabled', 'disabled');
        })
    }

    protected enableTargets(): void {
        this.targets.forEach((target: HTMLInputElement) => {
            target.removeAttribute('disabled');
        })
    }

    protected get triggerClassName(): string {
        return this.getAttribute('trigger-class-name');
    }

    protected get targetClassName(): string {
        return this.getAttribute('target-class-name');
    }
}

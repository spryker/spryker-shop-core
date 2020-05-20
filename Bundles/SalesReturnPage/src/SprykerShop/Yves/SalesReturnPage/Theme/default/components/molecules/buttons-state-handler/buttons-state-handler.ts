import Component from 'ShopUi/models/component';

export default class ButtonsStateHandler extends Component {
    protected triggers: HTMLInputElement[];
    protected targets: HTMLAnchorElement[];

    protected readyCallback(): void {}

    protected init(): void {
        this.triggers = <HTMLInputElement[]>Array.from(document.getElementsByClassName(this.triggerClassName));
        this.targets = <HTMLAnchorElement[]>Array.from(document.getElementsByClassName(this.targetClassName));
        this.mapEvents();
    }

    protected mapEvents(): void {
        this.mapTriggerChangeEvent();
        this.mapTargetClickEvent();
        this.onTriggerChange();
    }

    protected mapTriggerChangeEvent(): void {
        this.triggers.forEach((trigger: HTMLInputElement) => {
            trigger.addEventListener('change', () => this.onTriggerChange());
        });
    }

    protected mapTargetClickEvent(): void {
        this.targets.forEach((target: HTMLAnchorElement) => {
            target.addEventListener('click', (event: Event) => this.onTargetClick(event, target));
        });
    }

    protected onTriggerChange(): void {
        const checkedTriggers = <HTMLInputElement[]>this.triggers.filter(checkbox => checkbox.checked);

        if (this.isEnabler) {
            this.disableTargetsIfNotSelectedTriggers(checkedTriggers);

            return;
        }

        this.disableTargetsIfSelectedTriggers(checkedTriggers);
    }

    protected disableTargetsIfSelectedTriggers(checkedTriggers: HTMLInputElement[]): void {
        if (checkedTriggers.length) {
            this.disableTargets();

            return;
        }

        this.enableTargets();
    }

    protected disableTargetsIfNotSelectedTriggers(checkedTriggers: HTMLInputElement[]): void {
        if (checkedTriggers.length) {
            this.enableTargets();

            return;
        }

        this.disableTargets();
    }

    protected onTargetClick(event: Event, target: HTMLAnchorElement): void {
        if (target.hasAttribute('disabled')) {
            event.preventDefault();
        }
    }

    protected disableTargets(): void {
        this.targets.forEach((target: HTMLAnchorElement) => {
            target.setAttribute('disabled', 'disabled');
        });
    }

    protected enableTargets(): void {
        this.targets.forEach((target: HTMLAnchorElement) => {
            target.removeAttribute('disabled');
        });
    }

    protected get triggerClassName(): string {
        return this.getAttribute('trigger-class-name');
    }

    protected get targetClassName(): string {
        return this.getAttribute('target-class-name');
    }

    protected get isEnabler(): string {
        return this.getAttribute('is-enabler');
    }
}

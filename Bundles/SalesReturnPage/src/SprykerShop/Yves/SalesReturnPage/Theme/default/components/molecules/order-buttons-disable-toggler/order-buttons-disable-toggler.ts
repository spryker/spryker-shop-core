import Component from 'ShopUi/models/component';

export default class OrderButtonsDisableToggler extends Component {
    protected triggers: HTMLInputElement[];
    protected targets: HTMLElement[];

    protected readyCallback(): void {}

    protected init(): void {
        this.triggers = <HTMLInputElement[]>Array.from(document.getElementsByClassName(this.triggerClassName));
        this.targets = <HTMLElement[]>Array.from(document.getElementsByClassName(this.targetClassName));

        this.toggleButtonState();
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
        this.targets.forEach((target: HTMLElement) => {
            target.addEventListener('click', (event: Event) => this.onTargetClick(event, target));
        });
    }

    protected onTriggerChange(): void {
        this.toggleButtonState();
    }

    protected toggleButtonState(): void {
        const checkedTriggers = <HTMLInputElement[]>this.triggers.filter((checkbox) => checkbox.checked);

        this.toggleTargets(checkedTriggers);
    }

    protected toggleTargets(checkedTriggers: HTMLInputElement[]): void {
        if (Boolean(checkedTriggers.length) === this.isDisabledWhenChecked) {
            this.enableTargets();

            return;
        }

        this.disableTargets();
    }

    protected onTargetClick(event: Event, target: HTMLElement): void {
        if (target.classList.contains(this.disabledClassName)) {
            event.preventDefault();
        }
    }

    protected disableTargets(): void {
        this.targets.forEach((target: HTMLElement) => {
            if (target.tagName === 'A') {
                target.classList.add(this.disabledClassName);

                return;
            }

            if (target.tagName === 'BUTTON') {
                target.setAttribute('disabled', 'disabled');
            }
        });
    }

    protected enableTargets(): void {
        this.targets.forEach((target: HTMLElement) => {
            if (target.tagName === 'BUTTON') {
                target.removeAttribute('disabled');

                return;
            }

            target.classList.remove(this.disabledClassName);
        });
    }

    protected get triggerClassName(): string {
        return this.getAttribute('trigger-class-name');
    }

    protected get targetClassName(): string {
        return this.getAttribute('target-class-name');
    }

    protected get isDisabledWhenChecked(): boolean {
        const attributeValue = this.getAttribute('is-disabled-when-checked');

        return attributeValue === 'true';
    }

    protected get disabledClassName(): string {
        return this.getAttribute('disabled-class-name');
    }
}

import Component from 'ShopUi/models/component';

export default class FormValueSetter extends Component {
    protected triggers: HTMLElement[];
    protected target: HTMLInputElement;

    protected readyCallback(): void {}

    protected init(): void {
        this.triggers = <HTMLElement[]>Array.from(document.getElementsByClassName(this.triggerClassName));
        this.target = <HTMLInputElement>document.getElementsByClassName(this.targetClassName)[0];
        this.mapEvents();
    }

    protected mapEvents(): void {
        this.mapTriggerClickEvent();
    }

    protected mapTriggerClickEvent(): void {
        this.triggers.forEach((trigger: HTMLElement) => {
            trigger.addEventListener('click', (event: Event) => this.setValue(event, trigger));
        });
    }

    protected setValue(event: Event, trigger: HTMLElement): void {
        const value: string = trigger.getAttribute(this.valueAttribute);
        this.target.value = value;

        const form: HTMLFormElement = this.target.closest('form');
        form.submit();
    }

    protected get triggerClassName(): string {
        return this.getAttribute('trigger-class-name');
    }

    protected get targetClassName(): string {
        return this.getAttribute('target-class-name');
    }

    protected get valueAttribute(): string {
        return this.getAttribute('value-field');
    }
}

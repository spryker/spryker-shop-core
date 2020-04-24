import Component from 'ShopUi/models/component';

export default class FormValueSetter extends Component {
    protected form: HTMLFormElement;
    protected triggers: HTMLElement[];
    protected target: HTMLInputElement;

    protected readyCallback(): void {}

    protected init(): void {
        this.form = <HTMLFormElement>document.getElementsByClassName(this.formClassName)[0];
        this.triggers = <HTMLElement[]>Array.from(document.getElementsByClassName(this.triggerClassName));
        this.target = <HTMLInputElement>document.getElementsByClassName(this.targetClassName)[0];
        this.mapEvents();
    }

    protected mapEvents(): void {
        this.mapTriggerClickEvent();
    }

    protected mapTriggerClickEvent(): void {
        this.triggers.forEach((trigger: HTMLElement) => {
            trigger.addEventListener('click', () => this.setValue(trigger));
        });
    }

    protected setValue(trigger: HTMLElement): void {
        const value: string = trigger.getAttribute(this.valueAttribute);
        const submitEvent: Event = new Event('submit');

        this.target.value = value;
        this.form.dispatchEvent(submitEvent);
        this.form.submit();
    }

    protected get formClassName(): string {
        return this.getAttribute('form-class-name');
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

import Component from 'ShopUi/models/component';

const TAG_NAME = 'form';

export default class FormSubmitter extends Component {
    protected triggers: HTMLElement[];

    protected readyCallback(): void {}

    protected init(): void {
        this.triggers = <HTMLElement[]>Array.from(document.querySelectorAll(this.triggerSelector));
        this.mapEvents();
    }

    protected mapEvents(): void {
        this.triggers.forEach(trigger =>
            trigger.addEventListener(this.eventName, (event: Event) => this.onEvent(event)));
    }

    protected onEvent(event: Event): void {
        const trigger = <HTMLFormElement>event.currentTarget;
        const form = <HTMLFormElement>trigger.closest(TAG_NAME);

        if (!form) {
            return;
        }

        const submit = <HTMLButtonElement | HTMLInputElement>form.querySelector('[type="submit"]')
            || <HTMLButtonElement>form.querySelector('button');

        if (submit) {
            submit.click();
        }

        form.submit();
    }

    protected get triggerSelector(): string {
        return this.getAttribute('trigger-selector');
    }

    protected get eventName(): string {
        return this.getAttribute('event');
    }
}

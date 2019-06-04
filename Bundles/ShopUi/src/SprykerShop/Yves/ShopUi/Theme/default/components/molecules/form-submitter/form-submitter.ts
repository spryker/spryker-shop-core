import Component from 'ShopUi/models/component';

const TAG_NAME = 'form';

export default class FormSubmitter extends Component {
    protected triggers: HTMLElement[];

    protected readyCallback(): void {}

    /**
     * Default callback, which is called when all web components are ready for use.
     */
    mountCallback(): void {
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

    /**
     * Gets a querySelector of the triggers.
     */
    get triggerSelector(): string {
        return this.getAttribute('trigger-selector');
    }

    /**
     * Gets a name of the event.
     */
    get eventName(): string {
        return this.getAttribute('event');
    }
}

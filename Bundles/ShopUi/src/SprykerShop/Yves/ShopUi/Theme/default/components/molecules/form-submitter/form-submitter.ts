import Component from 'ShopUi/models/component';

export default class FormSubmitter extends Component {
    /**
     * Elements triggering the form submit.
     */
    protected triggers: HTMLElement[];

    protected readyCallback(): void {
        this.triggers = <HTMLElement[]>Array.from(document.querySelectorAll(this.triggerSelector));
        this.mapEvents();
    }

    protected mapEvents(): void {
        this.triggers.forEach(trigger =>
            trigger.addEventListener(this.eventName, this.triggerEvent.bind(this, trigger)));
    }

    protected triggerEvent(trigger: HTMLElement): void {
        const TAG_NAME = 'form';
        const form = <HTMLFormElement>trigger.closest(TAG_NAME);
        if (form) {
            const submit = <HTMLElement | HTMLInputElement>form.querySelector('[type="submit"]') ||
                <HTMLButtonElement> form.querySelector('button');
            if (submit) {
                submit.click();
            }
            form.submit();
        }
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

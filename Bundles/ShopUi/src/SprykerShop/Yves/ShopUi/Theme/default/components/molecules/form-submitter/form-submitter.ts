import Component from 'ShopUi/models/component';

export default class FormSubmitter extends Component {
    protected event: string;
    protected triggers: HTMLElement[];

    protected readyCallback(): void {
        this.event = <string>this.getAttribute('event');
        this.triggers = <HTMLElement[]>Array.from(document.querySelectorAll(this.triggerSelector));
        this.mapEvents();
    }

    protected mapEvents(): void {
        this.triggers.forEach(trigger => trigger.addEventListener(this.event, this.triggerEvent.bind(this, trigger)))
    }

    protected triggerEvent(trigger: HTMLElement, event: Event): void {
        const form = this.findElement(trigger, 'FORM');
        if(form.tagName === 'FORM') {
            const submit = <HTMLElement | HTMLInputElement>form.querySelector("[type='submit']");
            submit.click();
        }
    }

    protected findElement(target: any, tagName: string): HTMLFormElement | HTMLBodyElement {
        if(target.tagName !== tagName && target.tagName !== 'BODY') {
            return this.findElement(target.parentNode, tagName);
        }
        return target;
    }

    get triggerSelector(): string {
        return this.getAttribute('trigger-selector');
    }
}

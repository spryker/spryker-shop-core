import Component from '../../../models/component';
import { mount } from 'ShopUi/app';

/**
 * @event updateDynamicMessages An event emitted when need to update messages.
 */
export const EVENT_UPDATE_DYNAMIC_MESSAGES = 'updateDynamicMessages';

export default class DynamicNotificationArea extends Component {
    protected target: HTMLElement;

    protected readyCallback(): void {}

    protected init(): void {
        this.target = <HTMLElement>document.getElementsByClassName(this.targetClassName)[0];
        this.mapEvents();
    }

    protected mapEvents(): void {
        this.mapTriggerCustomUpdateMessagesEvent();
    }

    protected mapTriggerCustomUpdateMessagesEvent(): void {
        document.addEventListener(EVENT_UPDATE_DYNAMIC_MESSAGES, (event: CustomEvent) =>
            this.updateMessages(event.detail),
        );
    }

    protected updateMessages(responseHtml: string): void {
        if (!responseHtml) {
            return;
        }
        const notificationArea = this.target || this;
        notificationArea.insertAdjacentHTML('beforeend', responseHtml);
        mount();
    }

    protected get targetClassName(): string {
        return this.getAttribute('target-class-name');
    }
}

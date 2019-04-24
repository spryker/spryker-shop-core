import Component from 'ShopUi/models/component';

const EVENT_COPY = 'copyToClipboard';
const HIDE_CLASS_NAME = 'is-hidden';
type Target = HTMLInputElement | HTMLTextAreaElement;

/**
 * @event copyToClipboard An event emitted when the component performs a copy.
 */
export default class ClipboardCopy extends Component {
    protected target: Target;
    protected trigger: HTMLButtonElement;
    protected successCopyMessage: HTMLElement;
    protected errorCopyMessage: HTMLElement;
    protected durationTimeoutId: number;

    /**
     * Default message show duration.
     */
    readonly defaultDuration: number = 5000;

    protected readyCallback(): void {
        this.trigger = <HTMLButtonElement>document.querySelector(`${this.triggerSelector}`);
        this.target = <Target>document.querySelector(`${this.targetSelector}`);
        this.successCopyMessage = <HTMLElement>this.querySelector(`.${this.jsName}__success-message`);
        this.errorCopyMessage = <HTMLElement>this.querySelector(`.${this.jsName}__error-message`);
        this.mapEvents();
    }

    protected mapEvents(): void {
        this.trigger.addEventListener('click', (event: Event) => this.onClick(event));
    }

    protected onClick(event: Event): void {
        this.copy();
    }

    /**
     * Performs the copy to the clipboard and tells the component to show the message.
     */
    copy(): void {
        if(!this.isCopyCommandSupported) {
            setTimeout(() => this.showMessage(this.errorCopyMessage, this.defaultDuration));

            return;
        }

        this.target.select();
        document.execCommand('copy');
        setTimeout(() => this.showMessage(this.successCopyMessage, this.defaultDuration));
        this.dispatchCustomEvent(EVENT_COPY);
    }

    /**
     * Shows the message during the time set.
     * @param message A HTMLElement which contains the text message.
     * @param duration A number value which defines the period of time for which the message is shown.
     */
    showMessage(message: HTMLElement, duration: number): void {
        message.classList.remove(HIDE_CLASS_NAME);
        this.durationTimeoutId = setTimeout(() => this.hideMessage(message), duration);
    }

    /**
     * Hides the message.
     * @param message A HTMLElement which contains the text message.
     */
    hideMessage(message: HTMLElement): void {
        clearTimeout(this.durationTimeoutId);
        message.classList.add(HIDE_CLASS_NAME);
    }

    /**
     * Gets a css query selector to address the html element that will trigger the copy to clipboard.
     */
    get triggerSelector(): string {
        return this.getAttribute('trigger-selector');
    }

    /**
     * Gets a css query selector to address the html element containing the text to copy.
     */
    get targetSelector(): string {
        return this.getAttribute('target-selector');
    }

    /**
     * Gets if a browser supports the automatically copy to clipboard feature.
     */
    get isCopyCommandSupported(): boolean {
        return document.queryCommandSupported('copy');
    }
}

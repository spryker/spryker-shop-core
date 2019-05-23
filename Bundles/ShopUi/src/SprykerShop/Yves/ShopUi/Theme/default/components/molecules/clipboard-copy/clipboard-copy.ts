import Component from 'ShopUi/models/component';

const EVENT_COPY = 'copyToClipboard';

/**
 * @event copyToClipboard An event emitted when the component performs a copy.
 */
export default class ClipboardCopy extends Component {
    protected target: HTMLInputElement | HTMLTextAreaElement;
    protected trigger: HTMLButtonElement;
    protected successCopyMessage: HTMLElement;
    protected errorCopyMessage: HTMLElement;
    protected durationTimeoutId: number;

    /**
     * Default message show duration.
     */
    readonly defaultDuration: number = 5000;

    protected readyCallback(): void {
        this.trigger = <HTMLButtonElement>document.querySelector(this.triggerSelector);
        this.target = <HTMLInputElement | HTMLTextAreaElement>document.querySelector(this.targetSelector);
        this.successCopyMessage = <HTMLElement>this.querySelector(`.${this.jsName}__success-message`);
        this.errorCopyMessage = <HTMLElement>this.querySelector(`.${this.jsName}__error-message`);
        this.mapEvents();
    }

    protected mapEvents(): void {
        this.trigger.addEventListener('click', (event: Event) => this.onClick(event));
    }

    protected onClick(event: Event): void {
        this.copyToClipboard();
    }

    /**
     * Performs the copy to the clipboard and tells the component to show the message.
     */
    copyToClipboard(): void {
        if (!this.isCopyCommandSupported) {
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
        message.classList.remove(this.hideClassName);
        this.durationTimeoutId = setTimeout(() => this.hideMessage(message), duration);
    }

    /**
     * Hides the message.
     * @param message A HTMLElement which contains the text message.
     */
    hideMessage(message: HTMLElement): void {
        clearTimeout(this.durationTimeoutId);
        message.classList.add(this.hideClassName);
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

    /**
     * Gets a css class name for toggling an element.
     */
    get hideClassName(): string {
        return this.getAttribute('class-to-toggle');
    }
}

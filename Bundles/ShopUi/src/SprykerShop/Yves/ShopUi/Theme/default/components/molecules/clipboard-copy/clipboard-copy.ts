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
        /* tslint:disable: deprecation */
        this.trigger = <HTMLButtonElement>(this.triggerClassName ?
            document.getElementsByClassName(this.triggerClassName)[0] : document.querySelector(this.triggerSelector));
        this.target = <HTMLInputElement | HTMLTextAreaElement>(this.targetClassName ?
            document.getElementsByClassName(this.targetClassName)[0] : document.querySelector(this.targetSelector));
        /* tslint:enable: deprecation */
        this.successCopyMessage = <HTMLElement>this.getElementsByClassName(`${this.jsName}__success-message`)[0];
        this.errorCopyMessage = <HTMLElement>this.getElementsByClassName(`${this.jsName}__error-message`)[0];
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
            this.showMessage(this.errorCopyMessage, this.defaultDuration);

            return;
        }

        this.target.select();
        document.execCommand('copy');
        this.showMessage(this.successCopyMessage, this.defaultDuration);
        this.dispatchCustomEvent(EVENT_COPY);
    }

    /**
     * Shows the message during the time set.
     * @param message A HTMLElement which contains the text message.
     * @param duration A number value which defines the period of time for which the message is shown.
     */
    showMessage(message: HTMLElement, duration: number): void {
        message.classList.remove(this.hideClassName);
        this.durationTimeoutId = window.setTimeout(() => this.hideMessage(message), duration);
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
     *
     * @deprecated Use triggerClassName() instead.
     */
    get triggerSelector(): string {
        return this.getAttribute('trigger-selector');
    }
    protected get triggerClassName(): string {
        return this.getAttribute('trigger-class-name');
    }

    /**
     * Gets a css query selector to address the html element containing the text to copy.
     *
     * @deprecated Use targetClassName() instead.
     */
    get targetSelector(): string {
        return this.getAttribute('target-selector');
    }
    protected get targetClassName(): string {
        return this.getAttribute('target-class-name');
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

import Component from 'ShopUi/models/component';

const EVENT_COPY = 'copy';
type Target = HTMLInputElement | HTMLTextAreaElement;

/**
 * @event copy An event emitted when the component performs a copy.
 */
export default class ClipboardCopy extends Component {
    protected target: Target;
    protected trigger: HTMLButtonElement;
    protected message: HTMLElement;
    protected durationTimeoutId: number;

    /**
     * Default message show duration.
     */
    readonly defaultDuration: number = 5000

    protected readyCallback(): void {
        this.trigger = <HTMLButtonElement>document.querySelector(`${this.triggerSelector}`);
        this.target = <Target>document.querySelector(`${this.targetSelector}`);
        this.message = <HTMLElement>this.querySelector(`.${this.jsName}__message`);
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
    public copy(): void {
        this.target.select();
        document.execCommand('copy');
        setTimeout(() => this.showMessageFor(this.defaultDuration));
        this.dispatchCustomEvent(EVENT_COPY);
    }

    /**
     * Shows the message during the time set.
     * @param duration A number value which defines the period of time for which the message is shown.
     */
    public showMessageFor(duration: number): void {
        this.message.classList.remove('is-hidden');
        this.durationTimeoutId = setTimeout(() => this.hideMessage(), duration);
    }

    /**
     * Hides the message.
     */
    public hideMessage(): void {
        clearTimeout(this.durationTimeoutId);
        this.message.classList.add('is-hidden');
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
}

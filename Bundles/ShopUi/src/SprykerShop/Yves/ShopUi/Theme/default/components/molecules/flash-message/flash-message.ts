import Component from '../../../models/component';

export default class FlashMessage extends Component {
    /**
     * Default flash message show duration.
     */
    readonly defaultDuration: number = 5000;

    /**
     * The id of flash message timeout.
     */
    durationTimeoutId: number;

    protected readyCallback(): void {
        this.mapEvents();
        window.setTimeout(() => this.showFor(this.defaultDuration));
    }

    protected mapEvents(): void {
        this.addEventListener('click', (event: Event) => this.onClick(event));
    }

    protected onClick(event: Event): void {
        event.preventDefault();
        this.hide();
    }

    /**
     * Shows the flash message during the time set.
     * @param duration A number value which defines the period of time for which the flash message is shown.
     */
    showFor(duration: number) {
        this.classList.add(`${this.name}--show`);
        this.durationTimeoutId = window.setTimeout(() => this.hide(), duration);
    }

    /**
     * Hides the flash message.
     */
    hide() {
        clearTimeout(this.durationTimeoutId);
        this.classList.remove(`${this.name}--show`);
    }
}

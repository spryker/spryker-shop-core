import Component from '../../../models/component';

export default class ActionSingleClickEnforcer extends Component {
    /**
     * Elements on which the single action check is enforced.
     */
    targets: HTMLElement[];

    protected readyCallback(): void {
        this.targets = <HTMLElement[]>Array.from(document.querySelectorAll(this.targetSelector));
        this.mapEvents();
    }

    protected mapEvents(): void {
        this.targets.forEach((element: HTMLElement) => {
            element.addEventListener('click', (event: Event) => this.onTargetClick(event));
        });
    }

    protected onTargetClick(event: Event): void {
        const targetElement = <HTMLElement>event.currentTarget;
        const isLink: boolean = targetElement.matches('a');
        const isDisabled: boolean = targetElement.hasAttribute('disabled') || Boolean(targetElement.dataset.disabled);

        if (isDisabled) {
            event.preventDefault();

            return;
        }

        if (isLink) {
            event.preventDefault();
            const link = <HTMLLinkElement>targetElement;
            this.disableLink(event, link);

            return;
        }

        const form: HTMLFormElement = targetElement.closest('form');
        const buttonType = targetElement.getAttribute('type');
        const isSubmit = buttonType === 'submit';
        const isButtonSubmit = targetElement.matches('button') && !buttonType;

        if (form && (isSubmit || isButtonSubmit)) {
            form.addEventListener('submit', () => {
                this.disableButton(targetElement);
            });
        }
    }

    protected disableLink(event: Event, targetElement: HTMLLinkElement): void {
        targetElement.dataset.disabled = 'true';
        window.location.href = targetElement.href;
    }

    protected disableButton(targetElement: HTMLElement): void {
        targetElement.setAttribute('disabled', 'disabled');
    }

    /**
     * Gets a querySelector name of the target element.
     */
    get targetSelector(): string {
        return this.getAttribute('target-selector');
    }
}

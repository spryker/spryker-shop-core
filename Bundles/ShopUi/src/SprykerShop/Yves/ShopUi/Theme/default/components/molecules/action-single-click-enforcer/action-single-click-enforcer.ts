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

        if (isLink) {
            this.disableLink(event, targetElement);

            return;
        }

        const form: HTMLFormElement = targetElement.closest('form');

        if (form) {
            const buttonType = targetElement.getAttribute('type');
            this.disableButton(targetElement);

            if (buttonType === 'submit') {
                form.submit();
            }

            return;
        }
    }

    protected disableLink(event: Event, targetElement: HTMLElement): void {
        event.preventDefault();

        if (targetElement.dataset && targetElement.dataset.disabled) {
            return;
        }

        const url: string = (<HTMLLinkElement>targetElement).href;

        targetElement.dataset.disabled = 'true';
        window.location.href = url;
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

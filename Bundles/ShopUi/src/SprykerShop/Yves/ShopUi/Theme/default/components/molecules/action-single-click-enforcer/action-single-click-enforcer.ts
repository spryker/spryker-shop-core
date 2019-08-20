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
        const isDisabled: boolean = targetElement.hasAttribute('disabled');
        const isLink: boolean = targetElement.matches('a');
        const form: HTMLFormElement = targetElement.closest('form');
        const submitButton = form ? <HTMLButtonElement | HTMLInputElement>form.querySelector('[type="submit"]')
            || <HTMLButtonElement>form.getElementsByTagName('button')[0] : undefined;

        if (isDisabled) {
            event.preventDefault();

            return;
        }

        if (form && submitButton) {
            form.addEventListener('submit', () => {
                this.disableElement(targetElement);
            });
            submitButton.click();

            return;
        }

        if (isLink) {
            event.preventDefault();
            const url: string = (<HTMLLinkElement>targetElement).href;

            window.location.href = url;
        }

        this.disableElement(targetElement);
    }

    protected disableElement(targetElement: HTMLElement): void {
        targetElement.setAttribute('disabled', 'disabled');
    }

    /**
     * Gets a querySelector name of the target element.
     */
    get targetSelector(): string {
        return this.getAttribute('target-selector');
    }
}

import Component from '../../../models/component';

export default class ActionSingleClickEnforcer extends Component {
    /**
     * Elements on which the single action check is enforced.
     */
    targets: HTMLElement[]

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
        const targetElement = <HTMLElement> event.currentTarget;
        const isDisabled: boolean = targetElement.hasAttribute('disabled');
        const isSubmit: boolean = (<HTMLInputElement>targetElement).type === 'submit';
        const form: HTMLFormElement = isSubmit ? targetElement.closest('form') : null;

        if (isDisabled) {
            event.preventDefault();
            return;
        }

        if (isSubmit && form) {
            form.submit();
        }

        targetElement.setAttribute('disabled', 'disabled');
    }

    /**
     * Gets a querySelector name of the target element.
     */
    get targetSelector(): string {
        return this.getAttribute('target-selector') || '';
    }
}

import Component from '../../../models/component';

export default class ActionSingleClickEnforcer extends Component {
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

        if (isDisabled) {
            event.preventDefault();
            return;
        }

        if (isSubmit) {
            const form = <HTMLFormElement>targetElement.closest('form');
            if (form) {
                form.submit();
            }
        }

        targetElement.setAttribute('disabled', 'disabled');
    }

    get targetSelector(): string {
        return this.getAttribute('target-selector') || '';
    }
}

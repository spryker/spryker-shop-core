import Component from '../../../models/component';

export default class ActionSingleClickEnforcer extends Component {
    /**
     * Elements on which the single action check is enforced.
     */
    readonly targets: HTMLElement[]

    constructor() {
        super();
        this.targets = <HTMLElement[]>Array.from(document.querySelectorAll(this.targetSelector));
    }

    protected readyCallback(): void {
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

        if (isDisabled) {
            event.preventDefault();
            return;
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

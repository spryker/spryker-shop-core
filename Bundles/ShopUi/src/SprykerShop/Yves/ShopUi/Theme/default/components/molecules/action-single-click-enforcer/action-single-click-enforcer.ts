import Component from '../../../models/component';

export default class ActionSingleClickEnforcer extends Component {
    readonly targets: HTMLElement[]

    constructor() {
        super();
        this.targets = <HTMLElement[]>Array.from(document.querySelectorAll(this.targetSelector));
    }

    readyCallback(): void {
        this.mapEvents();
    }

    mapEvents(): void {
        this.targets.forEach((element: HTMLElement) => {
            element.addEventListener('click', (event: Event) => this.onTargetClick(event));
        });
    }

    onTargetClick(event: Event): void {
        const targetElement = <HTMLElement> event.currentTarget;
        const isDisabled: boolean = targetElement.hasAttribute('disabled');

        if (isDisabled) {
            event.preventDefault();
            return;
        }

        targetElement.setAttribute('disabled', 'disabled');
    }

    get targetSelector(): string {
        return this.getAttribute('target-selector') || '';
    }

}

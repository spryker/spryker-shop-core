import Component from '../../../models/component';

export default class ActionSingleClickEnforcer extends Component {
    readonly targets: HTMLElement[]

    constructor() {
        super();
        this.targets = <HTMLElement[]>this.getValidSelector();
    }

    readyCallback(): void {
        this.mapEvents();
    }

    mapEvents(): void {
        this.targets.forEach((element: HTMLElement) => {
            element.addEventListener('click', (event: Event) => this.onTargetsClick(event));
        });
    }

    onTargetsClick(event: Event): void {
        const targetElement = <HTMLElement> event.currentTarget;
        const hasAttribute: boolean = targetElement.hasAttribute('isDisabled');

        if (hasAttribute) {
            event.preventDefault();
            return;
        }
        targetElement.setAttribute('isDisabled', '');
    }

    getValidSelector(): HTMLElement[] {
        try {
            document.querySelectorAll(this.targetSelector);
        } catch (e) {
            return [];
        }
        return <HTMLElement[]>Array.from(document.querySelectorAll(this.targetSelector));
    }

    get targetSelector(): string {
        return this.getAttribute('target-selector');
    }

}

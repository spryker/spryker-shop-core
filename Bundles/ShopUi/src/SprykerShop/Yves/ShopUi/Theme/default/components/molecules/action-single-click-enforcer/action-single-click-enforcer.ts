import Component from '../../../models/component';

export default class ActionSingleClickEnforcer extends Component {
    readonly targets: HTMLElement[]

    constructor() {
        super();
        this.targets = this.hasSelector ? <HTMLElement[]>Array.from(document.querySelectorAll(this.targetSelector)) : [];
    }

    readyCallback(): void {
        if(this.hasSelector) {
            this.mapEvents();
        }
    }

    mapEvents(): void {
        this.targets.forEach((element: HTMLElement) => {
            element.addEventListener('click', (event: Event) => this.onTargetsClick(event));
        });
    }

    onTargetsClick(event: Event): void {
        const targetElement = <HTMLElement> event.currentTarget;
        const hasAttribute: boolean= targetElement.hasAttribute('disabled');

        if(hasAttribute) {
            event.preventDefault();
            return;
        }
        targetElement.setAttribute('disabled', 'disabled');
    }

    get targetSelector(): string {
        return this.getAttribute('target-selector');
    }

    get hasSelector(): boolean {
        if(this.targetSelector) {
            return true;
        }
        return false;
    }

}

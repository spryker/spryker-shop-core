import Component from '../../../models/component';

export default class ActionSingleClickEnforcer extends Component {
    readonly triggers: HTMLElement[]

    constructor() {
        super();
        this.triggers = <HTMLElement[]>Array.from(document.querySelectorAll(this.targetSelector));
    }

    readyCallback(): void {
        this.mapEvents();
    }

    mapEvents(): void {
        this.triggers.forEach((element: HTMLElement) => {
            element.addEventListener('click', (event: Event) => this.onTriggerClick(event));
        });
    }

    onTriggerClick(event: Event): void {
        let htmlElement = <HTMLElement> event.target;

        const hasAttribute: boolean= htmlElement.hasAttribute('disabled');
        if(hasAttribute) {
            event.preventDefault();
            return;
        }
        htmlElement.setAttribute('disabled', 'disabled');
    }

    get targetSelector(): string {
        return this.getAttribute('target-selector');
    }

}

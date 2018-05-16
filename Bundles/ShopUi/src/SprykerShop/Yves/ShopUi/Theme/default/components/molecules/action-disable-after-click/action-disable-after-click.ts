import Component from '../../../models/component';

export default class ActionDisableAfterClick extends Component {
    readonly classSelector: string
    readonly triggers: HTMLElement[]

    constructor() {
        super();
        this.classSelector = <string>this.getAttribute('disable-selector');
        this.triggers = <HTMLElement[]>Array.from(document.getElementsByClassName(this.classSelector));
    }

    readyCallback(): void {
        this.mapEvents();
    }

    mapEvents(): void {
        this.triggers.forEach((element: HTMLElement) => {
            element.addEventListener('click', (event: Event) => this.onTriggerClick(element, event));
        });
    }

    onTriggerClick(element: HTMLElement, event: Event): void {
        const hasClick: string = element.getAttribute('has-click');
        if(hasClick) {
            event.preventDefault();
            return;
        }
        element.setAttribute('has-click', 'true');
    }

}

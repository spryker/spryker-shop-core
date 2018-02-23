import Component from '../../../models/component';
import ClassToggler from '../class-toggler/class-toggler';

export default class ClassSelector extends Component {
    readonly togglers: ClassToggler[]

    constructor() {
        super();
        this.togglers = <ClassToggler[]>Array.from(this.querySelectorAll(`.${this.selector}__toggler`));
    }

    readyCallback(): void {
        this.mapEvents();
    }

    mapEvents(): void {
        this.togglers.forEach((toggler: ClassToggler) => toggler.addEventListener('toggle', (event: Event) => this.onToggleClick(event)));
    }

    onToggleClick(event: Event): void { 
        this.togglers.forEach((toggler: ClassToggler) => {
            if (!Object.is(event.currentTarget, toggler)) { 
                toggler.toggle(toggler.addClass);
            }
        });
    }
}

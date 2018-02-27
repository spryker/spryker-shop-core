import Component from '../../../models/component';
import TogglerRadio from '../toggler-radio/toggler-radio';

export default class ClassSelector extends Component {
    readonly togglers: TogglerRadio[]

    constructor() {
        super();
        this.togglers = <TogglerRadio[]>Array.from(this.querySelectorAll(`.${this.selector}__toggler`));
    }

    readyCallback(): void {
        this.mapEvents();
    }

    mapEvents(): void {
        this.togglers.forEach((toggler: TogglerRadio) => toggler.addEventListener('toggle', (event: Event) => this.onToggleClick(event)));
    }

    onToggleClick(event: Event): void { 
        this.togglers.forEach((toggler: TogglerRadio) => {
            if (!Object.is(event.currentTarget, toggler)) { 
                toggler.toggle(toggler.addClass);
            }
        });
    }
}

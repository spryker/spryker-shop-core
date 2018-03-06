import Component from '../../../models/component';

export default class SideDrawer extends Component {
    triggers: HTMLElement[]
    containers: HTMLElement[]

    readyCallback() { 
        this.triggers = <HTMLElement[]>Array.from(document.getElementsByClassName(this.triggerSelector));
        this.containers = <HTMLElement[]>Array.from(document.getElementsByClassName(this.containerSelector));
        this.mapEvents();
    }
    
    mapEvents() { 
        this.triggers.forEach((trigger: HTMLElement) => trigger.addEventListener('click', (event: Event) => this.onTriggerClick(event)));
    }

    onTriggerClick(event: Event) { 
        event.preventDefault();
        this.toggle();
    }

    toggle() { 
        const isShown = !this.classList.contains(`${this.name}--show`);
        this.classList.toggle(`${this.name}--show`, isShown);
        this.containers.forEach((conatiner: HTMLElement) => conatiner.classList.toggle(`is-not-scrollable`, isShown));
    }

    set triggerSelector(value: string) {
        this.setAttributeSafe('trigger-selector', value);
    }

    get triggerSelector(): string {
        return this.getAttributeSafe('trigger-selector');
    }

    set containerSelector(value: string) {
        this.setAttributeSafe('container-selector', value);
    }

    get containerSelector(): string {
        return this.getAttributeSafe('container-selector');
    }

}

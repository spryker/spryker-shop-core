import TogglerCheckbox from '../toggler-checkbox/toggler-checkbox';

export default class TogglerRadio extends TogglerCheckbox {
    togglers: TogglerRadio[]

    readyCallback() { 
        this.togglers = <TogglerRadio[]>Array.from(document.querySelectorAll(`${this.componentName}[group-name="${this.groupName}"]`));
        super.readyCallback();
    }

    onTriggerClick(event: Event): void {
        event.preventDefault();
        this.toggleAll();
    }

    toggleAll() { 
        this.togglers.forEach((toggler: TogglerRadio) => {
            toggler.toggle(toggler.addClass);
        });
    }

    get groupName(): string { 
        return this.getAttributeSafe('group-name');
    }
}

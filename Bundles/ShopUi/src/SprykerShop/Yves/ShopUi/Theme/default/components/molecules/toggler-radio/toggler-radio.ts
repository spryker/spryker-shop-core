import TogglerCheckbox from '../toggler-checkbox/toggler-checkbox';

export default class TogglerRadio extends TogglerCheckbox {
    /**
     * Collection of the toggle triggers.
     */
    togglers: TogglerRadio[];

    protected readyCallback(): void {
        this.togglers = <TogglerRadio[]>Array.from(document.querySelectorAll(
            `${this.name}[group-name="${this.groupName}"]`
        ));
        super.readyCallback();
    }

    protected onTriggerClick(event: Event): void {
        event.preventDefault();
        this.toggleAll();
    }

    /**
     * Toggles all the toggler elements.
     */
    toggleAll(): void {
        this.togglers.forEach((toggler: TogglerRadio) => {
            toggler.toggle(toggler.addClass);
        });
    }

    /**
     * Gets a group name.
     */
    get groupName(): string {
        return this.getAttribute('group-name');
    }
}

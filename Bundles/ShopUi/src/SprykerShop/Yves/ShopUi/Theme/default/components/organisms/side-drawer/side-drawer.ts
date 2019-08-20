import Component from '../../../models/component';

export default class SideDrawer extends Component {
    /**
     * Collection of the trigger elements.
     */
    triggers: HTMLElement[];
    /**
     * Collection of the container elements.
     */
    containers: HTMLElement[];

    protected readyCallback(): void {
        this.triggers = <HTMLElement[]>Array.from(document.getElementsByClassName(this.triggerSelector));
        this.containers = <HTMLElement[]>Array.from(document.getElementsByClassName(this.containerSelector));
        this.mapEvents();
    }

    protected mapEvents(): void {
        this.triggers.forEach((trigger: HTMLElement) => {
            trigger.addEventListener('click', (event: Event) => this.onTriggerClick(event));
        });
    }

    protected onTriggerClick(event: Event): void {
        event.preventDefault();
        this.toggle();
    }

    /**
     * Toggles the visibility of side-drawer and overlay.
     * Toggles the scrollability of containers.
     */
    toggle(): void {
        const isShown = !this.classList.contains(`${this.name}--show`);
        this.classList.toggle(`${this.name}--show`, isShown);
        this.containers.forEach((conatiner: HTMLElement) => conatiner.classList.toggle(`is-not-scrollable`, isShown));
    }

    /**
     * Gets a class name of the trigger element.
     */
    get triggerSelector(): string {
        return this.getAttribute('trigger-selector');
    }

    /**
     * Gets the css query selector of the container element.
     */
    get containerSelector(): string {
        return this.getAttribute('container-selector');
    }
}

import Component from 'ShopUi/models/component';

export default class ColorSelector extends Component {
    protected triggers: HTMLElement[];
    protected currentSelection: HTMLElement;

    protected readyCallback(): void {}

    protected init(): void {
        this.triggers = <HTMLElement[]>Array.from(this.getElementsByClassName(`${this.jsName}__item`));

        this.mapEvents();
    }

    protected mapEvents(): void {
        this.triggers.forEach((element: HTMLElement) => {
            element.addEventListener('mouseenter', (event: Event) => this.onTriggerSelection(event));
        });
    }

    protected onTriggerSelection(event: Event): void {
        event.preventDefault();
        this.currentSelection = <HTMLElement>event.currentTarget;
        this.setActiveItemSelection();
    }

    protected setActiveItemSelection(): void {
        this.triggers.forEach((element: HTMLElement) => {
            element.classList.remove(this.activeItemClassName);
        });

        this.currentSelection.classList.add(this.activeItemClassName);
    }

    protected get activeItemClassName(): string {
        return this.getAttribute('active-item-class-name');
    }
}

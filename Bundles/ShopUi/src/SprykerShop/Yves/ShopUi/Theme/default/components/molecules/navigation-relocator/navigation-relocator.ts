import Component from 'ShopUi/models/component';
import debounce from 'lodash-es/debounce';

export default class NavigationRelocator extends Component {
    protected blockToMove: HTMLElement;
    protected blockToPlace: HTMLElement;
    protected listSelector: string = 'nav > ul';
    protected listItemSelector: string = 'nav > ul > li';

    protected readyCallback(): void {}

    protected init(): void {
        this.blockToMove = <HTMLElement>document.getElementsByClassName(this.blockToMoveClassName)[0];
        this.blockToPlace = <HTMLElement>document.getElementsByClassName(this.blockToPlaceClassName)[0];

        this.discplaceNavigation();
        this.mapEvents();
    }

    protected mapEvents(): void {
        this.mapWindowResizeEvent();
    }

    protected mapWindowResizeEvent(): void {
        window.addEventListener('resize', debounce(() => this.discplaceNavigation(), this.debounceDelay));
    }

    protected discplaceNavigation(): void {
        const clonedNavigation = <HTMLElement>this.blockToPlace.getElementsByClassName(this.blockToMoveClassName)[0];

        if (window.innerWidth > this.breakpoint || clonedNavigation) {
            return;
        }

        this.cloneNavigation();
        this.replaceListClasses();
        this.replaceListItemClasses();
    }

    protected cloneNavigation(): void {
        const cloneNavigation = this.blockToMove.cloneNode(true);

        this.blockToPlace.appendChild(cloneNavigation);
    }

    protected replaceListClasses(): void {
        const list = <HTMLElement>this.blockToPlace.querySelector(this.listSelector);

        list.className = this.listClassesToReplace;
    }

    protected replaceListItemClasses(): void {
        const listItems = <HTMLElement[]>Array.from(this.blockToPlace.querySelectorAll(this.listItemSelector));

        listItems.forEach((element: HTMLElement) => element.className = this.listItemClassesToReplace);
    }

    protected get blockToMoveClassName(): string {
        return this.getAttribute('block-to-move-class-name');
    }

    protected get blockToPlaceClassName(): string {
        return this.getAttribute('block-to-place-class-name');
    }

    protected get listClassesToReplace(): string {
        return this.getAttribute('toggle-list-classes');
    }

    protected get listItemClassesToReplace(): string {
        return this.getAttribute('toggle-list-item-classes');
    }

    protected get breakpoint(): number {
        return Number(this.getAttribute('breakpoint'));
    }

    protected get debounceDelay(): number {
        return Number(this.getAttribute('debounce-delay'));
    }
}

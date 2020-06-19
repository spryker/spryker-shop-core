import Component from 'ShopUi/models/component';

export default class NavigationDisplacer extends Component {
    protected blockToMove: HTMLElement;
    protected blockToPlace: HTMLElement;
    protected listSelector: string = 'nav > ul';
    protected listItemSelector: string = 'nav > ul > li';
    protected timeout: number = 300;

    protected readyCallback(): void {}

    protected init(): void {
        this.blockToMove = <HTMLElement>document.getElementsByClassName(this.blockToMoveClassName)[0];
        this.blockToPlace = <HTMLElement>document.getElementsByClassName(this.blockToPlaceClassName)[0];

        this.initBlockMoving();
        this.mapEvents();
    }

    protected mapEvents(): void {
        this.mapWindowResizeEvent();
    }

    protected mapWindowResizeEvent(): void {
        window.addEventListener('resize', () => {
            setTimeout(() => this.initBlockMoving(), this.timeout);
        });
    }

    protected initBlockMoving(): void {
        const clonedNavigation = <HTMLElement>this.blockToPlace.getElementsByClassName(this.blockToMoveClassName)[0];

        if (window.innerWidth > this.breakpoint || clonedNavigation) {
            return;
        }

        this.cloneNavigation();
        this.listReplaceClasses();
        this.listItemReplaceClasses();
    }

    protected cloneNavigation(): void {
        const cloneNavigation = this.blockToMove.cloneNode(true);

        this.blockToPlace.appendChild(cloneNavigation);
    }

    protected listReplaceClasses(): void {
        const list = <HTMLElement>this.blockToPlace.querySelector(this.listSelector);

        list.className = this.listClassesToReplace;
    }

    protected listItemReplaceClasses(): void {
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
        return this.getAttribute('list-classes-to-replace');
    }

    protected get listItemClassesToReplace(): string {
        return this.getAttribute('list-item-classes-to-replace');
    }

    protected get breakpoint(): number {
        return Number(this.getAttribute('breakpoint'));
    }
}

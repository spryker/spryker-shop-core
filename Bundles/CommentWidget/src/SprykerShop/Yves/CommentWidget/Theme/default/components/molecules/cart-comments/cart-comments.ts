import Component from 'ShopUi/models/component';

export default class CartComments extends Component {
    /**
     * Elements targeted by click action.
     */
    readonly tabs: HTMLElement[];
    /**
     * Elements shows by click on tab.
     */
    readonly contentBlocks: HTMLElement[];
    /**
     * Active class for tabs.
     */

    constructor() {
        super();
        this.tabs = <HTMLElement[]>Array.from(this.querySelectorAll(this.tabSelector));
        this.contentBlocks = <HTMLElement[]>Array.from(this.querySelectorAll(this.contentBlockSelector));
    }

    protected readyCallback(): void {
        this.mapEvents();
    }

    protected mapEvents(): void {
        this.tabs.forEach((tab: HTMLElement) => {
            tab.addEventListener('click', (event: Event) => this.onTabClick(event));
        });
    }

    protected onTabClick(event: Event): void {
        const clickedTab = <HTMLElement>event.target;
        if (!clickedTab.classList.contains(this.tabClassToToggle)) {
            this.swithOnThisTab(clickedTab);
        }
    }

    protected swithOnThisTab(newActiveTab): void {
        const tabActiveClass = this.tabClassToToggle;
        const contentBlockActiveClass = this.contentBlockClassToToggle;
        const currentActiveTab = this.querySelector(`.${tabActiveClass}`);
        const currentActiveContentBlock = this.querySelector(`.${contentBlockActiveClass}`);
        const indexOfNeededContentBlock = this.tabs.findIndex(tab => tab===newActiveTab);
        currentActiveTab.classList.remove(tabActiveClass);
        newActiveTab.classList.add(tabActiveClass);
        currentActiveContentBlock.classList.remove(contentBlockActiveClass);
        this.contentBlocks[indexOfNeededContentBlock].classList.add(contentBlockActiveClass);

    }
    /**
     * Gets a querySelector of the tab element.
     */
    get tabSelector(): string {
        return this.getAttribute('tab-selector');
    }

    /**
     * Gets a querySelector of the content block element.
     */
    get contentBlockSelector(): string {
        return this.getAttribute('content-block-selector');
    }

    /**
     * Gets a class name for the active tab.
     */
    get tabClassToToggle(): string {
        return this.getAttribute('tab-class-to-toggle');
    }

    /**
     * Gets a class name for the active content block.
     */
    get contentBlockClassToToggle(): string {
        return this.getAttribute('content-block-class-to-toggle');
    }
}

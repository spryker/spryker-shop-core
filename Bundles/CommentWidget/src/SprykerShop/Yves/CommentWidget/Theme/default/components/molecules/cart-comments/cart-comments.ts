import Component from 'ShopUi/models/component';

export default class CartComments extends Component {
    protected tabs: HTMLElement[];
    protected contentBlocks: HTMLElement[];

    protected readyCallback(): void {}

    /**
     * Default callback, which is called when all web components are ready for use.
     */
    mountCallback(): void {
        this.tabs = <HTMLElement[]>Array.from(this.querySelectorAll(`.${this.jsName}__tab`));
        this.contentBlocks = <HTMLElement[]>Array.from(this.querySelectorAll(`.${this.jsName}__content-item`));
        this.mapEvents();
    }

    protected mapEvents(): void {
        this.tabs.forEach((tab: HTMLElement) => {
            tab.addEventListener('click', (event: Event) => this.onTabClick(event));
        });
    }

    protected onTabClick(event: Event): void {
        const targetTab = <HTMLElement>event.target;
        if (!targetTab.classList.contains(this.tabActiveClass)) {
            this.setActiveTab(targetTab);
        }
    }

    protected setActiveTab(newActiveTab: HTMLElement): void {
        const currentActiveTab = this.querySelector(`.${this.tabActiveClass}`);
        const currentActiveContentBlock = this.querySelector(`.${this.contentBlockActiveClass}`);
        const indexOfNeededContentBlock = this.tabs.findIndex(tab => tab === newActiveTab);
        currentActiveTab.classList.remove(this.tabActiveClass);
        newActiveTab.classList.add(this.tabActiveClass);
        currentActiveContentBlock.classList.remove(this.contentBlockActiveClass);
        this.contentBlocks[indexOfNeededContentBlock].classList.add(this.contentBlockActiveClass);
    }

    /**
     * Gets a class name for the active tab.
     */
    get tabActiveClass(): string {
        return this.getAttribute('tab-class-to-toggle');
    }

    /**
     * Gets a class name for the active content block.
     */
    get contentBlockActiveClass(): string {
        return this.getAttribute('content-block-class-to-toggle');
    }
}

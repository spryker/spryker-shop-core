import Component from 'ShopUi/models/component';

export default class CartCommentThreadList extends Component {
    protected commentThreadSelectComponent: HTMLElement;
    protected cartCommentThread: HTMLElement[];

    protected readyCallback(): void {
        this.commentThreadSelectComponent = <HTMLElement>document.querySelector(this.commentThreadSelectAttribute);
        this.cartCommentThread = <HTMLElement[]>Array.from(this.querySelectorAll(this.cartCommentThreadClass));
        this.initShowCartCommentThread();
        this.mapEvents();
    }

    protected mapEvents(): void {
        this.commentThreadSelectComponent.addEventListener('change', (event: Event) => this.onSelectChange(event));
    }

    protected initShowCartCommentThread(): void {
        const commentThreadSelect: HTMLSelectElement = this.commentThreadSelectComponent.querySelector('select');
        this.onShowCartCommentThread(commentThreadSelect.value);
        this.cartCommentThreadScrollDown();
    }

    protected onSelectChange(event: Event): void {
        const commentThreadSelect: HTMLSelectElement = (event.target as HTMLSelectElement);
        this.onShowCartCommentThread(commentThreadSelect.value);
        this.cartCommentThreadScrollDown();
    }

    protected onShowCartCommentThread(showNameComment: string): void {
        this.cartCommentThread.forEach((cartComment: HTMLElement) => {
            const cartCommentName = cartComment.getAttribute('name');

            if (cartCommentName !== showNameComment) {
                cartComment.classList.add(this.classToToggle);

                return;
            } else {
                cartComment.classList.remove(this.classToToggle);
            }
        });
    }

    protected cartCommentThreadScrollDown(): void {
        if (this.scrollHeight > this.clientHeight) {
            this.scrollTop = this.scrollHeight - this.clientHeight;
        }
    }

    /**
     * Gets a class name for the select.
     */
    get commentThreadSelectAttribute(): string {
        return this.getAttribute('comment-thread-select');
    }

    /**
     * Gets a class name from cart comment thread element.
     */
    get cartCommentThreadClass(): string {
        return this.getAttribute('cart-comment-thread');
    }

    /**
     * Gets a class name for the toggle action.
     */
    get classToToggle(): string {
        return this.getAttribute('class-to-toggle');
    }
}

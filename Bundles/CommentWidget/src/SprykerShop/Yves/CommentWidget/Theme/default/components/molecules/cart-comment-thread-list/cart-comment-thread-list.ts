import Component from 'ShopUi/models/component';

export default class CartCommentThreadList extends Component {
    protected commentThreadSelectComponent: HTMLElement;
    protected cartCommentThread: HTMLElement[];

    protected readyCallback(): void {
        this.commentThreadSelectComponent = <HTMLElement>document.getElementsByClassName(this.commentThreadSelectAttribute)[0];
        this.cartCommentThread = <HTMLElement[]>Array.from(this.getElementsByClassName(this.cartCommentThreadClass));
        this.initShowCartCommentThread();
        this.mapEvents();
    }

    protected mapEvents(): void {
        this.commentThreadSelectComponent.addEventListener('change', (event: Event) => this.onSelectChange(event));
    }

    protected initShowCartCommentThread(): void {
        const commentThreadSelect: HTMLSelectElement = this.commentThreadSelectComponent.getElementsByTagName('select')[0];
        const commentThreadSelectValue = commentThreadSelect.value;
        this.onShowCartCommentThread(commentThreadSelectValue);
        this.cartCommentThreadScrollDown();
    }

    protected onSelectChange(event: Event): void {
        const commentThreadSelect: HTMLSelectElement = (event.target as HTMLSelectElement);
        const commentThreadSelectValue = commentThreadSelect.value;
        this.onShowCartCommentThread(commentThreadSelectValue);
        this.cartCommentThreadScrollDown();
    }

    protected onShowCartCommentThread(showNameComment: string) {
        this.cartCommentThread.forEach((cartComment: HTMLElement) => {
            const cartCommentName = cartComment.getAttribute('name');

            if (cartCommentName !== showNameComment) {
                cartComment.classList.add('is-hidden');
            } else {
                cartComment.classList.remove('is-hidden');
            }
        });
    }

    protected cartCommentThreadScrollDown(): void {
        if (this.scrollHeight > this.clientHeight) {
            this.scrollTop = this.scrollHeight - this.clientHeight;
        }
    }

    get commentThreadSelectAttribute(): string {
        return this.getAttribute('comment-thread-select');
    }

    get cartCommentThreadClass(): string {
        return this.getAttribute('cart-comment-thread');
    }
}

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
    }

    protected onSelectChange(event: Event): void {
        const commentThreadSelect: HTMLSelectElement = (event.target as HTMLSelectElement);
        const commentThreadSelectValue = commentThreadSelect.value;
        this.onShowCartCommentThread(commentThreadSelectValue);
        this.cartCommentThreadScrollUp();
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

    protected cartCommentThreadScrollUp(): void {
        if (this.scrollTop > 0) {
            this.scrollTop = 0;
        }
    }

    get commentThreadSelectAttribute(): string {
        return this.getAttribute('comment-thread-select');
    }

    get cartCommentThreadClass(): string {
        return this.getAttribute('cart-comment-thread');
    }
}
